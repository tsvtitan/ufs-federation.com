
app.factory('Mailings',['$http','Urls','Dictionary','Payload','Event','Const',
                       function($http,Urls,Dictionary,Payload,Event,Const) {

  var mailing = {
  
    new: function(d,onResult,url) {
      
      var form = new FormData();
      var arr = ['recipients','attachments'];
      var files = [];
      
      d = Payload.get(d);
      
      for (var v in d) {

        if (arr.indexOf(v)!==-1) {
          
          var fs = d[v];
          for (var i=0; i<fs.length; i++) {
            
            files.push({name:v,file:fs[i]});
          }
        } else {
          
          var r = d[v];
          if (angular.isObject(r)) {
            form.append(v,JSON.stringify(r));
          } else {
            form.append(v,r);
          }  
        }
      }
       
      for (var i in files) {
        form.append(files[i].name,files[i].file); 
      }
      
      $http.post((url)?url:Urls.mailingsNew,form,{
                  headers: {'Content-Type':undefined},
                            transformRequest:angular.identity
                })
           .success(onResult)
           .error(function(d) { onResult({error:Dictionary.connectionFailed(d)}); });
    },
    
    test: function(d,onResult) {
      
      if (d && !d.test) {
        d.test = true;
      }
      this.new(d,onResult,Urls.mailingsTest);
    },
    
    lists: function(onResult) {
      
      $http.post(Urls.mailingsLists,Payload.get())
           .success(onResult)
           .error(function(d){ onResult({error:Dictionary.connectionFailed(d)}); });
    },
    
    get: function(d,onResult) {
      
      $http.post(Urls.mailingsGet,Payload.get(d))
           .success(onResult)
           .error(function(d){ onResult({error:Dictionary.connectionFailed(d)}); });  
    },
    
    cancel: function(d,onResult) {
      
      $http.post(Urls.mailingsCancel,Payload.get(d))
           .success(onResult)
           .error(function(d){ onResult({error:Dictionary.connectionFailed(d)}); });
    },
    
    suspend: function(d,onResult) {
      
      $http.post(Urls.mailingsSuspend,Payload.get(d))
           .success(onResult)
           .error(function(d){ onResult({error:Dictionary.connectionFailed(d)}); });
    },
    
    resume: function(d,onResult) {
      
      $http.post(Urls.mailingsResume,Payload.get(d))
           .success(onResult)
           .error(function(d){ onResult({error:Dictionary.connectionFailed(d)}); });
    },
    
    subscribe: function(d,onResult,onEvent) {
      
      Event.subscribe(Const.eventMailings,Urls.mailingsSubscribe,
                      Payload.get(d),onResult,onEvent);
    },
    
    unsubscribe: function(d,onResult) {
      
      Event.unsubscribe(Const.eventMailings,Urls.mailingsUnsubscribe,
                        Payload.get(d),onResult);
    },
    
    recipients: function(d,onResult) {
      
      $http.post(Urls.mailingsRecipients,Payload.get(d))
           .success(onResult)
           .error(function(d){ onResult({error:Dictionary.connectionFailed(d)}); });
    },
    
    body: function(d,onResult) {
      
      $http.post(Urls.mailingsBody,Payload.get(d))
           .success(onResult)
           .error(function(d){ onResult({error:Dictionary.connectionFailed(d)}); });
    }
  }
  
  return mailing;
  
}]);