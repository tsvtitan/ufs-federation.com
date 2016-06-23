// User

var bcrypt = require('bcrypt'),
    md5 = require('MD5');    

module.exports = {

  migrate: 'safe',
  autoPK: true,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    /*id: {
      type: 'integer',
      unique: true,
      primaryKey: true,
      required: true
    },*/
    created: {
      type: 'datetime',
      required: true,
      defaultsTo: function () { return new Date(); }
    },
    login: {
      type: 'string',
      unique: true,
      required: true
    },
    email: {
      type: 'email'
    },
    pass: {
      type: 'string'
    },
    access: {
      type: 'json'
    },
    templates: {
      type: 'json'
    },
    state: {
      type: 'string'
    },
    remote: {
      type: 'boolean'  
    },
    locked: {
      type: 'datetime'
    },
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  },
  
  setPassHash: function(user,result) {
    
    var log = Users.log;
    
    if (user.pass) {
      bcrypt.genSalt(10, function(err,salt) {
        bcrypt.hash(user.pass,salt,function(err,hash) {
          if(err) {
            log.error(err);
            result(err);
          } else {
            user.pass = hash;
            result(null,user);
          }
        });
      });
    } else result(null,user);
  },
  
  beforeCreate: function(user,result) {
    
    this.setPassHash(user,result);
  },
  
  beforeUpdate: function(user,result) {
    
    this.setPassHash(user,result);
  },
  
  isGranted: function (userOrId,url,params,result) {

    function granted(user) {

      function exists(props) {

        function check(v1,v2) {

          var r = false;
          if (typeof(v2)==='string') {

            r = v1==='*';
            if (!r) {
              r = v2.search(v1)!==-1;
            }

          } else if (Utils.isObject(v2)) {

            for (var k in v2) {

              var v = v2[k];
              if (typeof(v)==='string') {

                r = v1==='*';
                if (!r) {
                  r = v.search(v1)!==-1;
                }
                if (r) break;
              }
            }
          }
          return r;
        }

        var ret = true;
        for (var prop in props) {

          var v1 = props[prop];
          var v2 = params[prop];

          if (v2) {

            if (typeof(v1)==='string') {

              ret = ret && check(v1,v2);

            } else if (Utils.isObject(v1)) {

              var r = true;
              for (var k in v1) {

                var v = v1[k];
                if (typeof(v)==='string') {
                  r = check(v,v2);
                  if (r) break;
                } 
              }
              ret = ret && r;
            }
          }
        }
        return ret;
      } 

      if (user) {

        if (user.access) {

          if (Utils.isObject(user.access) && url) {

            for (var prop in user.access) {

              if (url.search(prop)!==-1) {
                var p = user.access[prop];
                if (typeof(p)==='string') {
                  return p==='*';
                } else if (Utils.isObject(p)) {
                  return exists(p);
                }
              }
            }
          } else return (Utils.isObject(user.access) && user.access==='*');

        } else return false;
      }
      return false;
    }
    
    if (Utils.isObject(userOrId)) {
      
      result(null,granted(userOrId));
      
    } else {
      
      this.findOneById(userOrId,function(err,user){
        result(err,granted(user));
      });
    }
  },
  
  getOrCreateByLogin: function(login,pass,lockOnFail,result) {

    var self = this;
    var log = this.log;
    
    if (login) {
      
      log.debug('Trying to find the user (%s) in the local db...',[login]);
      
      self.findOneByLogin(login,function onFindUser(err,user){

        if (err) result(err,null,[]);
        else if (user && !user.remote) {
          
          log.debug('Checking the user credentials...');
          
          if (user.locked) {
            
            log.debug('User\'s credentials are invalid and locked');
            result(null,null,[]);
            
          } else {
            
            if (user.pass && pass) {

              bcrypt.compare(pass,user.pass,function(err,res) {

                if (err) result(err,null,[]);
                else if (res===true) {

                  log.debug('User\'s credentials are fine. Getting templates...');

                  Templates.getByUser(user,function(err,tpls){
                    result(err,user,tpls);
                  });

                } else {

                  if (lockOnFail) {

                    log.debug('User\'s credentials are invalid. Locking...');

                    self.update({id:user.id},{locked:new Date()},function(err,u){
                      result(null,null,[]);
                    });
                  } else result(null,null,[]);
                }
              });

            } else { 

              if (!user.pass && !pass) {

                log.debug('User\'s credentials have not set');

                Templates.getByUser(user,function(err,tpls){
                  result(err,user,tpls);
                });                

              } else {
                
                if (lockOnFail) {

                  log.debug('User\'s credentials are invalid. Locking...');

                  self.update({id:user.id},{locked:new Date()},function(err,u){
                    result(null,null,[]);
                  });
                } else result(null,null,[]);
              }
            }
          }

        } else {
          
          if (!user)
            log.debug('No user has found');
          else 
            log.debug('User is marked as the remote');
          
          try {
            
            log.debug('Trying to find the login (%s) in the remote db...',[login]);
            
            WwwLogins.findOneByLogin(login,function onFindLogin(err,uLogin){
              
              if (err) { 
                
                log.error(err);
                result(null,null,[]);
                
              } else if(uLogin) {
                
                log.debug('Checking the login credentials...');
                
                if (uLogin.pass && pass &&
                    uLogin.pass===md5(pass)) {
                  
                  log.debug('Login\'s credentials are fine. Processing a user...');
                  
                  var newUser = {
                    login: login,
                    pass: pass,
                    access: {
                      '^/service/api/+':'*',
                      '^/service/mailings/+':'*'
                    },
                    templates: {
                      '^mailings+':'*'
                    },
                    state: 'mailings',
                    remote: true
                  }
                  
                  if (!user) {
                    
                    self.create(newUser,function onCreateUser(err,u){

                      if (err) {
                        log.error(err);
                        result(null,null,[]);
                      } else if (u) {

                        log.debug('User\'s has created. Getting templates...');

                        Templates.getByUser(u,function(err,tpls){
                          result(err,u,tpls);
                        });

                      } else {
                        log.debug('No user has created');
                        result(null,null,[]);
                      }
                    });
                    
                  } else {
                    
                    self.update({id:user.id},newUser,function onUpdateUser(err,u){

                      if (err) {
                        log.error(err);
                        result(null,null,[]);
                      } else if (u && u.length>0) {
                        
                        log.debug('User\'s has updated. Getting templates...');

                        Templates.getByUser(u[0],function(err,tpls){
                          result(err,u[0],tpls);
                        });

                      } else {
                        log.debug('No user has updated');
                        result(null,null,[]);
                      }
                    });
                  }  
                  
                } else {
                  log.debug('Login\'s credentials are invalid');
                  result(null,null,[]);
                }
                
              } else {
                log.debug('No login has found');
                result(null,null,[]);
              }
            });
            
          } catch(e) {
            log.exception(e);
            result(null,null,[]);
          }
        }
      });
    } else result(null,null,[]);
    
  }
  
};

