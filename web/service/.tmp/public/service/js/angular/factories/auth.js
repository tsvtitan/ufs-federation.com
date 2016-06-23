
app.factory('Auth',['$http','Route','Urls','Dictionary','Payload',
                    function($http,Route,Urls,Dictionary,Payload) {
  
  var auth = {
    
    user: false,
    captcha: false,
    defTemplates: {},
    templates: {},
    menu: {},
    
    setTemplates: function(tpls) {
      if (tpls) {
        
        Route.clear();
        for (var k in this.defTemplates) {
          var t = this.defTemplates[k];
          Route.state(k,t);
        }
        
        this.templates = {};
        this.menu = {};
        for (var k in tpls) {
          var t = tpls[k];
          Route.state(k,{url:t.url,templateUrl:t.template});
          this.templates[k] = t;
          if (t.menu) {
            this.menu[k] = t;
          }
        }
      }
    },
    
    setDefTemplates: function(tpls) {
      this.defTemplates = tpls;
    },

    login: function(data,result) {
      var self = this;
      if (!self.user) {
        $http.post(Urls.authLogin,Payload.get(data)).success(function(d){
          self.user = d.user;
          self.captcha = d.captcha;
          self.setTemplates(d.templates);
          result(d);
        }).error(function(d){
          result({error:Dictionary.connectionFailed(d),user:this.user});
        });
      } else {
        result({error:false,user:this.user});
      }
    },

    logout: function (result) {
      var self = this;
      if (self.user) {
        $http.post(Urls.authLogout,{userId:self.user.id}).success(function(d){
          if (!d.error) {
            self.user = false;
            self.captcha = false;
            self.setTemplates({});
          }
          result(d);
        }).error(function(d){
          result({error:Dictionary.connectionFailed(d)});
        });
      } else { result({error:false}); }
    }
  }
  
  return auth;
}]);