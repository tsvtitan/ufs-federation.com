// Permissions

module.exports = {

  migrate: 'safe',
  autoPK: true,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    
    login: {
      type: 'string',
      required: true
    },
  
    interface: {
      type: 'string',
      required: true
    },
    
    action: {
      type: 'string',
      required: true
    },
    
    access: {
      type: 'json'
    },
    
    locked: {
      type: 'datetime'
    },
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
    
  },
  
  getAccess: function (userOrLogin,interface,action,result) {
    
    var self = this;
    
    function get(login) {
      
      var where = {
        login: login,
        interface: interface,
        action: action
      }
      
      self.find({where:where},{fields:{access:1}},
                function(err,permissions){
        
        if (err) result(err,null)
        else if (Utils.isArray(permissions)) {
      
          var access = {};
          var need = true;
          
          for (var i in permissions) {
            
            var p = permissions[i];
            
            if (Utils.isObject(p.access)) {
              access = Utils.extend(access,Utils.makeFilter(p.access));
              need = false;
            }
          }
          
          if (need && Object.keys(access).length===0) 
            result(null,{creator:login});
          else result(null,access);
          
        } else result(null,{creator:login});
      });
    }
    
    if (Utils.isObject(userOrLogin))
      get(userOrLogin.login);
    else get(userOrLogin);
  }
};