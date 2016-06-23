
app.service('Payload',['$window','Utils',
                      function($window,Utils) {
  
  
  this.size = function() {
    return {width:$window.innerWidth,height:$window.innerHeight};
  }
  
  this.get = function(obj) {
    
    var obj1 = {
      payload: {
        size: this.size()
      }
    }
    
    return Utils.extend(obj1,obj); 
  }
  
}]);