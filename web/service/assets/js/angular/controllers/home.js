
app.controller('home',['$scope','$element','Dictionary',
                       function($scope,$element,Dictionary) {
  $scope.tryAuth();
  $scope.dic = Dictionary.dic($element);
  $scope.hideSpinner();
}]);

