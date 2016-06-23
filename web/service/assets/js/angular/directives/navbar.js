
app.directive('navbar',['$rootScope',function ($rootScope) {
  
  return {
    restrict: 'A',
    link: function (scope, element) {
      $rootScope.$on('$stateChangeSuccess', function () {
        if (element.hasClass('in')) {
          element.collapse('hide');
        }
      });
    }
  };
}]);