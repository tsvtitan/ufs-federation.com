
app.controller('mailings',['$rootScope','$scope','$state','$element','$timeout','$q',
                           '$interval','$sce','$window','filterFilter',
                           'Mailings','Dictionary','Const','Utils','Value','Log',
                           function($rootScope,$scope,$state,$element,$timeout,$q,
                                    $interval,$sce,$window,filterFilter,
                                    Mailings,Dictionary,Const,Utils,Value,Log) {
    
  $scope.tryAuth(); 
  $scope.dic = Dictionary.dic($element);
          
  $scope.all = [];
  $scope.filtered = [];
  $scope.mailings = [];
  $scope.limit = 10;
  $scope.page = 1;
  $scope.search = '';
  $scope.interval = false;
  
  $scope.state = {hide:false,recalc:false,canceling:false,suspending:false,ready:false};
  
  $scope.loopTime = function() {
    
    if ($scope.interval) {
      $interval.cancel($scope.interval); 
      $scope.interval = false;
    }
    if ($scope.mailings.length>0) {
      $scope.interval = $interval(function(){
        for (var i in $scope.mailings) {
          var m = $scope.mailings[i];
          if (m.active && !(m.canceled || m.suspended)) {
            m.time++;
          }
        }
      },1000);
    }
  }
  
  $scope.humanTime = function(m) {
    return Utils.formatSeconds(m.time,'h:mm:ss');
  }
  
  $scope.humanBody = function(m) {
    
    if (m.bodyLoaded) {
      if (!m.bodyDecoded) { 
        m.body = Utils.decodeBase64(m.body);
        m.bodyDecoded = true;
      }
      if (!m.bodySanitized) {
        m.body = $sce.trustAsHtml(m.body);
        m.bodySanitized = true;
      }
      return m.body;
      
    } else return '';
  }
  
  $scope.humanDateTime = function(d,fmt) {
    
    return moment(d).format($scope.dic(fmt));
  }
  
  $scope.humanSize = function(size) {
   
    return Utils.formatSize(size);
  }
  
  $scope.new = function() {
    
    $scope.state.hide = true;
    $scope.showSpinner();
    $timeout(function(){
      $scope.reload("mailingsNew");
    },Const.timeoutHide);
  }
  
  $scope.pageChange = function(apply) {
    var start = ($scope.page-1)*$scope.limit;
    $scope.mailings = $scope.filtered.slice(start,start+$scope.limit);
    if (apply) $scope.$apply();
  }  
  
  $scope.filterApply = function(value,apply) {
    
    $scope.filtered = filterFilter($scope.all,value);
    $scope.page = 1;
    $scope.pageChange(apply);
  }

  $scope.recalcLimit = function(h) {
    var l = parseInt(((h-380)/40).toFixed());
    if (l!==$scope.limit) {
      $scope.limit = (l>=3)?l:3;
      $scope.pageChange();
    }
  }
  
  $scope.progress = function(m) {
    return parseInt(((m.sentCount*100)/m.allCount).toFixed());
  }
  
  $scope.$watch(function(){
    return $window.innerHeight;
  },function(v1,v2){
    if (v1!==v2 && !$scope.state.recalc) {
      $scope.state.recalc = true;
      try {
        $scope.recalcLimit(v1);
      } finally {
        $scope.state.recalc = false;
      }  
    }
  });
  
  $scope.cancel = function(m) {
    
    m.canceling = true;
    
    Mailings.cancel({id:m.id},function(d){
      
      m.canceling = false;
      if (d.error) {
        $scope.showError(d.error);
      } else {
        m = Utils.extend(m,d);
      }
    });
  }
  
  $scope.suspendOrResume = function(m) {
    
    m.suspending = true;
    
    if (!m.suspended) {
      
      Mailings.suspend({id:m.id},function(d){
        m.suspending = false;
        if (d.error) {
          $scope.showError(d.error);
        } else {
          m = Utils.extend(m,d);
        }
      });
      
    } else {
      
      Mailings.resume({id:m.id},function(d){
        m.suspending = false;
        if (d.error) {
          $scope.showError(d.error);
        } else {
          m = Utils.extend(m,d);
        }
      });
    }
  }
  
  $scope.recipientsShow = function(m) {
    
    if (m.recipients && m.recipients.length>0) {
      m.recipientsCollapsed = !m.recipientsCollapsed;
    } else if (m.recipientCount>0) {
      
      m.recipientsGetting = true;
      
      Mailings.recipients({id:m.id},function(d){
        
        delete m.recipientsGetting;
        
        if (d.error) {
          $scope.showError(d.error);
        } else {
          m.recipients = d.recipients;
          m.recipientsCollapsed = false;
        }   
      });
    }
  }
  
  $scope.bodyShow = function(m) {
    
    if (m.bodyLoaded) {
      m.bodyCollapsed = !m.bodyCollapsed;
    } else if (m.bodyExists) {
      
      m.bodyGetting = true;
      
      Mailings.body({id:m.id},function(d){
        
        delete m.bodyGetting;
        
        if (d.error) {
          $scope.showError(d.error);
        } else {
          m.body = d.body;
          m.bodyDecoded = d.bodyDecoded;
          m.bodyLoaded = true;
          m.bodyCollapsed = false;
        }   
      });
    }
  }
  
  $scope.arrayEmpty = function(arr) {
    return !Utils.isArray(arr) || (Utils.isArray(arr) && arr.length==0);
  }
  
  function onMailings(d) {

      if (d) {
        var l = $scope.all.length;
        var needApply = false;
        switch (d.verb) {
          case 'created': {
            if (d.data) {
              var temp = Utils.filter($scope.all,function(m){
                return m.id === d.id;
              });
              temp.forEach(function(m){
                m = Utils.extend(m,d.data);  
              });
              if (temp.length==0) {
                $scope.all.splice(0,0,d.data);
                needApply = (l!==$scope.all.length);
              }
            }
            break;
          }
          case 'updated': {
            if (d.data) {
              Utils.filter($scope.all,function(m){
                return m.id === d.id;
              }).forEach(function(m){
                m = Utils.extend(m,d.data);  
              });
            }
            break;
          }
          case 'destroyed': {
            $scope.all = Utils.reject($scope.all,function(m) {
              return m.id === d.id;
            });
            needApply = (l!==$scope.all.length);
            break;
          }
        }
        if (needApply) $scope.filterApply($scope.search,true);
      }
    }

  function init(){
    
    if (!Value.mailingsReady) {
      $rootScope.$on('$stateChangeStart',function(event,toState,toParams,fromState,fromParams){

        if (fromState && fromState.name === 'mailings') {
          Mailings.unsubscribe({limit:Const.limitMailings},function(d){
            if (d.error) Log.warn(d.error);
          });
        }
      });
    }

    function subscibeMailings() {

      var deferred = $q.defer();
      Mailings.subscribe({limit:Const.limitMailings},function(d){
        if (d.error) {
          deferred.resolve(d.error);
        } else deferred.resolve(false);
      },onMailings);
      return deferred.promise;
    }

    function getMailings() {

      var deferred = $q.defer();
      Mailings.get({limit:Const.limitMailings},function(d){
        if (d.error) {
          deferred.reject(d.error);
        } else deferred.resolve(d.mailings);
      });
      return deferred.promise;
    }

    Value.mailingsReady = true;
    
    subscibeMailings();
    
    $q.all([/*subscibeMailings(),*/getMailings()]).then(function(arr){

      /*if (arr[0]) Log.warn(arr[0]);
      $scope.all = (arr[1])?arr[1]:[];*/
      $scope.all = (arr[0])?arr[0]:[];

    }).catch(function(error){

      $scope.showError(error);
      $scope.all = [];

    }).finally(function(){

      $scope.filtered = $scope.all;
      $scope.recalcLimit($window.innerHeight);
      $scope.loopTime();
      $scope.hideSpinner();
      $scope.state.hide = false;
      $scope.state.ready = true;
    });
  }
               
  $scope.$watch('ready',function(value){
    if (value) init();
  });
  
  $scope.$watch('search',function(value){
    if ($scope.state.ready) $scope.filterApply(value);
  });
  
}]);