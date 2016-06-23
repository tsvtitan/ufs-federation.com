
app.config(['$provide','laddaProvider',function($provide,laddaProvider) {
    
  $provide.decorator('$controller', ['$delegate','$injector',function ($delegate,$injector) {

    return function(constructor,locals,later,indent) {
      if (typeof constructor == "string") {
        //locals.$scope.controllerName = constructor;
      } 
      return $delegate(constructor,locals,later,indent);
    }
  }]);  

  $provide.decorator('$templateCache',['$delegate',function($delegate) {
    var originalGet = $delegate.get;

    $delegate.get = function(key) {
      var value;
      value = originalGet(key);
      if (!value) {
        if (key in JST) {
          value = JST[key]();
          if (value) {
            $delegate.put(key, value);
          }
        }
      }
      return value;
    };

    return $delegate;
  }]);

  laddaProvider.setOption({
    style: 'expand-left',
    spinnerColor: '#000',
    spinnerSize: '20'
  });

  return this;
}]);
