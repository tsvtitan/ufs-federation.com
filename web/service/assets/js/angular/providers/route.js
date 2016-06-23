
app.provider('Route',['$stateProvider','$urlRouterProvider',
                      function($stateProvider,$urlRouterProvider) {
    
  this.$get = function () {
    return {
      defaultUrl: function(url) {
        return $urlRouterProvider.otherwise(url);
      },
      state: function(name,definition) {
        return $stateProvider.state(name,definition);
      },
      clear: function() {
        return $stateProvider.clear();
      }
    }
  }
}]);

