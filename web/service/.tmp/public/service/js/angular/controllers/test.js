
app.controller('test', ['$scope','Mailings', 
                        function($scope,Mailings){
  
  $scope.default = [
    { open:false, subject:'Проверка #1',progress:64},
    { open:false, subject:'Проверка #2',progress:false,sentCount:1999},
    { open:false, subject:'Проверка #3 Проверка #2 Проверка #1',progress:10},
    { open:false, subject:'Проверка #4'},
    { open:false, subject:'Проверка #5'},
    { open:false, subject:'Проверка #6'},
    { open:false, subject:'Проверка #7'},
    { open:false, subject:'Проверка #8'},
    { open:false, subject:'Проверка #9'},
    { open:false, subject:'Проверка #10'},
    { open:false, subject:'Проверка #11'},
    { open:false, subject:'Проверка #12'},
    { open:false, subject:'Проверка #13'},
    { open:false, subject:'Проверка #14'}
  ];
  
  $scope.limit = 5;
  $scope.count = $scope.default.length;
  $scope.page = 1;
  
  $scope.fromDate = new Date();
  $scope.fromOpened = false;
  
  $scope.fromOptions = {
    formatYear: 'yy',
    startingDay: 1
  };
  
  $scope.fromOpen = function($event) {
    $event.preventDefault();
    $event.stopPropagation();

    $scope.fromOpened = !$scope.fromOpened;
  };
  
  var start = ($scope.page-1)*$scope.limit;
  $scope.mailings = $scope.default.slice(start,start+$scope.limit);
  
  $scope.pageChange = function() {
    
    var start = ($scope.page-1)*$scope.limit;
    $scope.mailings = $scope.default.slice(start,start+$scope.limit);
   
  }
  
  /*
  var data = {
    limit: 3,
    page: 1 
  }
  
  Mailings.get(data,function(d){
    if (d.error) {
      alert(d.error);
      $scope.mailings = [];
      $scope.count = 0;
    } else {
      $scope.mailings = d.mailings;
      $scope.count = d.count;
    }
  });
  */

  
    
}]);
