
app.controller('auth',['$scope','$state','$element','$timeout','Auth','Dictionary','Const','Regexp','Urls','Utils',
                       function($scope,$state,$element,$timeout,Auth,Dictionary,Const,Regexp,Urls,Utils) {
  
  $scope.tryAuth();
  $scope.dic = Dictionary.dic($element);
  
  $scope.captchaUrl = '';
  $scope.captchaPattern = Regexp.captcha;
  
  $scope.data = {
    login: "",
    pass: "",
    captcha: ""
  };
  
  $scope.state = {entering:false,hide:false,spinner:false};

  $scope.$watch('ready',function(value){
    
    if (value) {
      $scope.captchaRefresh();
      $scope.hideSpinner();
    }
  });
  
  
  $scope.captchaRefresh = function() {
    
    if (Auth.captcha) { 
      $scope.state.spinner = true;
      $scope.captchaUrl = Utils.format('%s?%d',[Urls.captchaLoginUrl,new Date().getTime()]);
    }
  }
  
  $scope.captchaAfterLoad = function(error) {
    
    $scope.data.captcha = '';
    $scope.state.spinner = false;
  }
  
  $scope.captchaChange = function() {
    this.form.captcha.setRequired(Auth.captcha && !Regexp.captcha.test($scope.data.captcha));
  }
  
  $scope.submit = function() {
    
    this.form.captcha.setRequired(Auth.captcha && !Regexp.captcha.test($scope.data.captcha));
    
    if (this.form.checkFields()) {
      
      $scope.state.entering = true;
      
      Auth.login(this.data,function(d) {
        
        if (d.error) {
          $scope.captchaRefresh();
          $scope.state.entering = false;
          $scope.showError(d.error);
        }
        if (d.user) {
          $scope.state.hide = true;
          $scope.showSpinner();
          $timeout(function(){
            $scope.state.entering = false;
            $scope.reload(d.user.state);
          },Const.timeoutHide);
        }
      });
      
    } else $scope.showError(Const.checkFields);
  }
}]);