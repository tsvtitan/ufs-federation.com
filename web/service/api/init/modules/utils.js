
function Utils() {}

Utils.prototype = {

  format: function(s,values) {

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

    if (util.isArray(values)) {
      var arr = _.clone(values);
      arr.unshift(s);
      for (var i in arr) {
        if (typeof(arr[i]) === 'object') {
          arr[i] = JSON.stringify(arr[i],null,' ');
        }
      }
      return util.format.apply(util,arr);
    } else {
      return formatObj(s,values);
    }
  },

  path2obj: function(obj,path,delim) {
    var items = path.split(delim);
    for (var i in items) {
      var o = obj[items[i]];
      if (o) {
        obj = o;
      } else {
        obj = null;
        break;
      }
    }
    return obj;
  },
  
  makeFilter: function(obj) {
      
    function setPathByObject(key,root,parent,delim) {

      for (var k1 in parent) {

        var v = parent[k1];
        var prefix = (key)?key.concat(delim):'';
        var path = prefix.concat(k1);

        if (_.isObject(v) && !_.isArray(v)) {

          setPathByObject(path,root,v,delim);

        } else {
          root[path] = v;
        }
      }
    }

    function setPathByArray(key,root,array,delim) {

      var temp = [];

      for (var k1 in array) {

        var v = array[k1];
        var o1 = {}

        if (_.isObject(v) && !_.isArray(v)) {

          setPathByObject(false,o1,v,delim);
        }
        temp.push(o1);
      }
      root[key] = temp;
    }

    var delim = '.';

    for (var k in obj) {

      var o = obj[k];
      if (_.isObject(o)) {

        if (k.toLowerCase()==='or' && _.isArray(o)) {

          setPathByArray(k,obj,o,delim);

        } else if (!_.isArray(o)) {

          setPathByObject(k,obj,o,delim);
          delete obj[k];
        }
      }
    }
    return obj;
  },

  clone: function(obj) {
    return _.clone(obj);
  },

  extend: function(obj1,obj2) {
    return _.extend(obj1,obj2);
  },
  
  extendSeries: function(arr) {
    
    if (_.isArray(arr)) {
      
      var r = {};
      
      _.forEach(arr,function(a){
        r = _.extend(r,a || {});
      });
      
      return r;
      
    } else return arr;
  },

  reject: function(item,result) {
    return _.reject(item,result);
  },

  filter: function(arr,result){
    return _.filter(arr,result);
  },

  forEach: function(arr,result) {
    return _.forEach(arr,result);
  },

  isObject: function(obj) {
    return _.isObject(obj);
  },

  isArray: function(obj) {
    return _.isArray(obj);
  },

  isFunction: function(obj) {
    return _.isFunction(obj);
  },

  isDefined: function(obj) {
    return !_.isUndefined(obj);
  },

  isNumber: function(obj) {
    return _.isNumber(obj);
  },

  isString: function(obj) {
    return _.isString(obj);
  },
    
  isEmpty: function(obj) {

    if (_.isString(obj)) {
      return obj.trim()!=='';
    } else return !(obj);
  },
  
  isBoolean: function(obj) {
    return !_.isBoolean(obj);
  }

}

module.exports = function() {
  return new Utils();
};
