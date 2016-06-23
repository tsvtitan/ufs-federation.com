
app.controller('header',['$rootScope','$scope','$state','$element','Auth','Dictionary',
                         function($rootScope,$scope,$state,$element,Auth,Dictionary) {
  
  $scope.dic = Dictionary.dic($element);
  $scope.state = {logout:false};

  $scope.logout = function() {
    
    $scope.state.logout = true;
            
    if (Auth.user) {
      
      Auth.logout(function(d) {
        
        $scope.state.logout = false;
        if (d.error) {
          $scope.showError(d.error);
        } else {
          Auth.user = false;
          $scope.reload('auth');
        }
      });
    } else $scope.state.logout = false;
  }
}]);