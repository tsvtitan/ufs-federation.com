
app.service('Log',['Utils',function(Utils) {
    
  function logType(type,s,values) {
    if (console) {
      var s1 = Utils.format(s,values);
      console.log(Utils.format('%s: %s',[type,s1]));
    }
  }
    
  this.write = function(s,values) {
  
    if (console) {
      if (values)
        console.log(Utils.format(s,values));
      else 
        console.log(s);
    }
  } 
  
  this.info = function(s,values){
    logType('info',s,values);
  }
  
  this.error = function(s,values) {
    logType('error',s,values);
  }
  
  this.warn = function(s,values){
    logType('warn',s,values);
  }
  
  this.debug = function(s,values){
    logType('debug',s,values);
  }
  
  this.exception = function(e) {
    if (e && typeof(e) === 'object') {
      //logType('exception',e.)
    }
  }
  
}]);