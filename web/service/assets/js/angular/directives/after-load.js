
app.directive('afterLoad',['$parse', 
                            function ($parse) {
  return {
    restrict: 'A',
    link: function (scope,element,attrs) {
      
      var fn = $parse(attrs['afterLoad']);
      
      element.on('load',function(e){
        
        scope.$apply(function(){
          fn(scope,{});
        });
      });
      
      element.on('error',function(e){
        
        scope.$apply(function(){
          fn(scope,{error:true});
        });
      });
    }
  };
}]);