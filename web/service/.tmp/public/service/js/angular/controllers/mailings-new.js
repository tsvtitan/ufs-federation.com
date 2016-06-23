                             
app.controller('mailingsNew',['$scope','$state','$element','$timeout','$q',
                              'Auth','Mailings','Dictionary','Regexp','Const','Utils',
                              mailingsNewController]);

function mailingsNewController($scope,$state,$element,$timeout,$q,
                             Auth,Mailings,Dictionary,Regexp,Const,Utils) {

  $scope.tryAuth(); 
  $scope.dic = Dictionary.dic($element);
  
  $scope.emailPattern = Regexp.email;
  $scope.emailOrPhonePattern = Regexp.emailOrPhone;
  
  $scope.senderMenu = [];
  $scope.recipientsMenu = [];
  $scope.recipientsValue = '';
  $scope.attachmentsValue = '';
  $scope.replyToMenu = {};
  $scope.patterns = {};
  $scope.channels = {};
  $scope.testEmail = '';
  
  $scope.data = {
    name:'',
    contact:'',
    menu:'',
    subject:'', 
    body:'', 
    replyTo:'',
    keywords:'',
    pattern:'',
    headers:'',
    channel:'',
    delay:300,
    duration:60,
    emails:{},
    recipients:{},
    attachments:{}
  };
  
  $scope.state = {creating:false,testing:false,disabled:false,hide:true};
  
  $scope.$watch('ready',function(value){
    
    if (value) {
      
      function setRecipients() {
        
        var deferred = $q.defer();
        Mailings.lists(function(d){
          if (d.error) {
            $scope.recipientsMenu = [];
          } else {
            $scope.recipientsMenu = d.menu;
          }
          deferred.resolve();
        });
        return deferred.promise;
      }
      
      function setModel() {

        return $q(function(resolve) {

          $scope.data.contact = $scope.dic('defaultSender');
          $scope.senderMenu = [];
          var m1 = $scope.dic('senderMenu');
          if (angular.isArray(m1) && m1.length>0) {
            $scope.senderMenu = m1;
            if (!$scope.data.contact) {
              $scope.data.contact = m1[0];
            }
          }

          $scope.data.replyTo = '';
          $scope.replyToMenu = {};
          var m2 = $scope.dic('replyToMenu');
          if (angular.isObject(m2)) {
            var keys = Object.keys(m2);
            if (keys.length>0) {
              //$scope.data.replyTo = m2[keys[0]];
              $scope.replyToMenu = m2;
            }
          }

          $scope.data.pattern = '';
          $scope.patterns = {};
          var patterns = $scope.dic('patterns');
          if (angular.isObject(patterns)) {
            $scope.patterns = patterns;
            $scope.data.pattern = patterns[$scope.dic('defaultPattern')];
          }

          $scope.data.channel = '';
          $scope.channels = {};
          var channels = $scope.dic('channels');
          if (angular.isObject(channels)) {
            $scope.channels = channels;
            $scope.data.channel = channels[$scope.dic('defaultChannel')];
          }

          $scope.testEmail = Auth.user.email;
          resolve();
        });
      }
      
      setRecipients();
      
      $q.all([/*setRecipients(),*/setModel()]).finally(function() {
        $scope.hideSpinner();  
        $scope.state.hide = false;
      });
      
    }
  });
  
  $scope.recipientsChange = function(files) {
    
    if (files) {
      if (files.length>1) {
        $scope.recipientsValue = $scope.dic(Const.countFileSelected,{count:files.length});
      } else $scope.recipientsValue = (files.length>0)?files[0].name:'';
    } else $scope.recipientsValue = '';
    $scope.data.recipients = files;
    $scope.data.menu = '';
  }
  
  $scope.recipientsMenuClick = function(item) {
    $scope.recipientsValue = item.name;
    $scope.data.menu = item.menu;
    $scope.data.recipients = {};
  }
  
  $scope.attachmentsChange = function(files) {
   
    if (files) {
      if (files.length>1) {
        $scope.attachmentsValue = $scope.dic(Const.countFileSelected,{count:files.length});
      } else $scope.attachmentsValue = (files.length>0)?files[0].name:'';
    } else $scope.attachmentsValue = '';
    $scope.data.attachments = files;
  }
  
  $scope.replyToClick = function(value) {
    if (value) {
      $scope.data.replyTo = value;
    }
  }
  
  $scope.testEmailChange = function() {
    this.form.testEmail.setRequired(!Regexp.emailOrPhone.test($scope.testEmail));
  }
  
  $scope.test = function() {
    
    this.form.recipientsValue.setRequired(false);
    this.form.testEmail.setRequired(!Regexp.emailOrPhone.test($scope.testEmail));
    
    if (this.form.checkFields()) {
      
      $scope.state.disabled = $scope.state.testing = true;
      
      var data = Utils.clone(this.data);
      data.menu = '';
      data.recipients = {};
      data.emails = {};
      data.emails[this.testEmail] = 'TEST';
      data.test = true;
      
      Mailings.test(data,function(d) {
        
        $scope.state.disabled = $scope.state.testing = false;
        if (d.error) {
          $scope.showError(d.error);
        } else if (d.count){
          $scope.showInfo($scope.dic(Const.countMessageQueued,d));
        }
      });
      
    } else $scope.showError(Const.checkFields);
  }
  
  $scope.submit = function() {
    
    this.form.recipientsValue.setRequired(!$scope.recipientsValue);
    this.form.testEmail.setRequired(false);
    
    if (this.form.checkFields()) {
      
      $scope.state.disabled = $scope.state.creating = true;
      
      var data = Utils.clone(this.data);
      data.emails = {};
      data.test = false;
      
      Mailings.new(this.data,function(d) {

        if (d.error) {
          $scope.state.disabled = $scope.state.creating = false;
          $scope.showError(d.error);
        } else if (d.count){
          
          $scope.state.hide = true;
          $scope.showInfo($scope.dic(Const.countMessageQueued,d));
          
          $timeout(function(){
            $scope.state.disabled = $scope.state.creating = false;
            $state.go('mailings');
          },Const.timeoutHide);
        }
      });
      
    } else $scope.showError(Const.checkFields);
  }
  
  $scope.cancel = function() {
    
    $scope.state.hide = true;
    $scope.showSpinner();
    $timeout(function(){
      $state.go('mailings');
    },Const.timeoutHide);
  }
  
}
