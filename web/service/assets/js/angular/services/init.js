
app.service('Init',['$http','$location','$window','Urls','Dictionary','Payload',
                    function($http,$location,$window,Urls,Dictionary,Payload) {
    
  this.get = function(result) {
    $http.post(Urls.init,Payload.get(),{cache:true})
         .success(function(d){
                    if (d.reload) {
                      $window.location.reload();
                    } else result(d);
                  })
         .error(function(d){ result({error:Dictionary.connectionFailed(d)}); });
  }
}]);