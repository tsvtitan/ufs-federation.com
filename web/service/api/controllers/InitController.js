// InitController

module.exports = {

  get: function(req,res) {
    
    var log = this.log;
    
    function error(s,values) {
      log.error(s,values,1);
      res.jsonError('Initialization error');
    }
    
    function getAuth(result) {
      
      if (req.session && req.session.userId) {
        
        Users.findOneById(req.session.userId,function(err,user){
          
          if (err) result(err,null,[],false);
          else if (user) {
            
            Templates.getByUser(user,function(err,tpls){
              result(err,user,tpls,false);
            });
            
          } else result(null,null,[],true);
        });
        
      } else result(null,null,[],false);
    }
    
    var log = this.log;
    
    try {
      var i18n = sails.config.i18n;
      var locale = i18n.defaultLocale;
      if (req.locale) {
        locale = req.locale;
      }
      var fileName = res.fmt('%s/%s%s',[i18n.directory,locale,i18n.extension]);

      var fs = require('fs');
      if (fs.exists(fileName,function(exists){

        if (exists) {

          getAuth(function(err,user,tpls,reset) {

            if (err) error(err)
            else if (!reset) {
              
              var d = require(fileName);

              delete d['back'];
              var f = d['front'];
              delete d['front'];

              for (var k in f) {
                d[k] = f[k];
              }
              
              var u = false;
              if (user && !user.locked) {
                u = {
                  id: user.id,
                  login: user.login,
                  email: user.email
                }
              }
              
              var loginCount = (req.session.loginCount)?req.session.loginCount:0;
              
              var data = {
                auth: {
                  user: u,
                  templates: tpls,
                  captcha: loginCount>2
                },
                dictionary:d
              }
              
              if (user)
                res.jsonSuccess(data);
              else
                res.jsonError('User is not found',null,data)

            } else {
              
              log.warn('Session needs to be cleared');
              
              req.session.destroy();
              req.session = null;  
              
              res.jsonSuccess({auth:false});
            }
          });

        } else error('Init file {name} is not found',{name:fileName});
        
      }));
      
    } catch(e) {
      error(e.message);
    }  
  }
};
