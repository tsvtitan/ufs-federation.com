
app.service('Utils',['base64',function(base64) {

  function formatObj(s,values) {
    
    if (values) {
      values = typeof(values) === 'object' ? values : Array.prototype.slice.call(arguments, 1);

      if (typeof(s)==='string') {
        return s.replace(/\{\{|\}\}|\{(\w+)\}/g, function (m, n) {
            if (m == "{{") { return "{"; }
            if (m == "}}") { return "}"; }
            return values[n];
        });
      } else return s;
    } else return s;
  }
    
  this.format = function(s,values) {
    if (angular.isObject(s) || angular.isArray(s)) {
      return s;
    } else {
      if (angular.isArray(values)) {
        return vsprintf(s,values);
      } else {
        return formatObj(s,values);
      }
    }
  }
  
  this.clone = function(obj) {
    return _.clone(obj);
  }
  
  this.extend = function(obj1,obj2) {
    return _.extend(obj1,obj2);
  }
  
  this.reject = function(item,result) {
    return _.reject(item,result);
  }
  
  this.filter = function(arr,result){
    return _.filter(arr,result);
  }
  
  this.insert = function(arr,index,item) {
    arr.splice(index,0,item);
    return arr;
  }
  
  this.isArray = function(arr){
    return _.isArray(arr);
  }
  
  this.isDefined = function(obj) {
    return angular.isDefined(obj);
  }
  
  this.formatSeconds = function(seconds,fmt) {
    var ret = moment.duration(seconds,'seconds').format(fmt);
    return (seconds<0 && seconds>-3600)?'-'+ret:ret;
  }
  
  this.decodeBase64 = function(s) {
    return base64.decode(s);
  }
  
  this.formatSize = function (bytes) {
    if (bytes == 0) { return "0.00 B"; }
    var e = Math.floor(Math.log(bytes) / Math.log(1024));
    return (bytes/Math.pow(1024, e)).toFixed(2)+' '+' KMGTP'.charAt(e)+'B';
  }
}]);