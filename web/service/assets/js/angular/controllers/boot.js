
app.controller('boot',['$rootScope','$scope','$state','$element','$timeout','$location','$window',
                       'Init','Auth','Route','Dictionary','Const',
                       function($rootScope,$scope,$state,$element,$timeout,$location,$window,
                                Init,Auth,Route,Dictionary,Const) {
  
  $scope.auth = Auth;
  $scope.dic = Dictionary.dic($element);
  $scope.visible = false;
  $scope.ready = false;
  $scope.alerts = [];
  $scope.spinner = false;
  $scope.lastPath = $location.path();
  
  Route.clear();
  Route.defaultUrl('/');
  
  Auth.setDefTemplates({
    auth:{url:'/auth',templateUrl:'auth.html'},
    home:{url:'/home',templateUrl:'home.html'}
  });
  
  $scope.showSpinner = function() { $scope.spinner = true; }
  $scope.hideSpinner = function() { $scope.spinner = false; }
  
  $rootScope.$on('$stateChangeStart',function() {
    $scope.showSpinner();
  });
  
  $rootScope.$on('$stateChangeSuccess',function() {
    //$scope.showSpinner();
  });
  
  $scope.tryAuth = function() {
    if ($scope.visible) {
      if (!Auth.user && $state.current.name!=='auth') {
        $state.go('auth');
      } else if (Auth.user && (($state.current.name==='auth') || 
                               ($state.current.name===''))) {
        $state.go('home');
      }
    }
  }
  
  $scope.reload = function(name) {
    if (!name) { name = 'home'; }
    $state.transitionTo(name,{},{reload:true,inherit:false,notify:true});
  }
  
  $scope.showAlert = function(m,v,t,k) {
    
    var alert = {msg:this.dic(m,v),type:k,hide:false};
    
    alert.close = function() {
      var self = this;
      self.hide = true;
      $timeout(function(){
        $scope.alerts.splice($scope.alerts.indexOf(self),1);
      },Const.timeoutHide);
    }
    
    alert.queueClose = function(tm) {
      var self = this;
      $timeout(function(){
        if ($scope.alerts.indexOf(self)!==-1) {
          self.close();
        }
      },tm);
    }
    
    $scope.alerts.push(alert);
    if ($scope.alerts.length>Const.limitAlerts) {
      $scope.alerts[0].close();
    }
    
    alert.queueClose((t)?t:Const.timeoutAlert);
  }
  
  $scope.showError = function(m,v,t) { $scope.showAlert(m,v,(t)?t:Const.timeoutError,'danger'); }
  $scope.showInfo = function(m,v,t) { $scope.showAlert(m,v,(t)?t:Const.timeoutInfo,'info'); }
  $scope.showSuccess = function(m,v,t) { $scope.showAlert(m,v,(t)?t:Const.timeoutSuccess,'success'); }
  $scope.showWarn = function(m,v,t) { $scope.showAlert(m,v,(t)?t:Const.timeoutWarn,'warning'); }
  
  Init.get(function(d){
    
    Dictionary.init(d.dictionary);
    Auth.user = d.auth.user;
    Auth.captcha = d.auth.captcha;
    Auth.setTemplates(d.auth.templates);
    
    $scope.visible = true;
    if (Auth.user && $scope.lastPath && $scope.lastPath!=='/') {
      $location.path($scope.lastPath);
    } else $scope.tryAuth();
    
    $scope.ready = true;
    //$scope.$broadcast('read');
  });
  
}]);