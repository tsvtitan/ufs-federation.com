
var stackTrace = require('stack-trace');

function Log() {}

Log.prototype = {

  info: function(s,values) {
    sails.log.info(Utils.format(s,values));
  },

  error: function(s,values) {
    sails.log.error(Utils.format(s,values));
  },

  warn: function(s,values) {
    sails.log.warn(Utils.format(s,values));
  },

  debug: function(s,values) {
    sails.log.debug(Utils.format(s,values));
  },

  exception: function(e) {
    sails.log.error(e.message);
  },
  
  extend: function(obj,prefix,name,suffix,method) {
  
    if (obj.log) {
      return obj;
    }

    var n = (name)?name:obj.constructor.name;

    function removePhrase(s,phrase) {
      var s1 = s.substring(0,phrase.length);
      if (s1===phrase) {
        return s.substring(s1.length);
      } else return s;
    }

    function getTrace(traces,index,module) {

      var ret = '';
      var exp1 = new RegExp('^'+sails.config.appPath+'+');
      var exp2 = new RegExp('^'+sails.config.appPath+'/node_modules+');
      for (var i=index; i<traces.length; i++) {
        var t = traces[i];
        if (t) {
          var f = (t.methodName)?t.methodName:t.functionName;
          if (exp1.test(t.fileName) && !exp2.test(t.fileName) && f) {
            f = removePhrase(f,'module.exports.');
            ret = (!module)?Utils.format('.%s',[f]):Utils.format('.%s (%s, %d)',[f,t.fileName,t.lineNumber]);
            break;
          }
        }
      } 
      return ret;
    }

    function prepare(s,values,module,offset) {
      var pr = (prefix)?prefix+'/':'';
      var sf = (suffix)?suffix:'';
      var t = '';
      var traces = stackTrace.parse(new Error());
      if (method && traces && traces.length>(2+offset)) {
        offset = (offset)?offset:0;
        t = getTrace(traces,2+offset,module);
      }
      return Utils.format('[%s%s%s%s] => %s',[pr,n,sf,t,Utils.format(s,values)]);
    }

    var self = this;
    
    var ext = {

      log: {

        debug: function(s,values,offset) {
          self.debug(prepare(s,values,false,offset));
        },

        error: function(s,values,offset) {
          self.error(prepare(s,values,false,offset));
        },

        info: function(s,values,offset) {
          self.info(prepare(s,values,false,offset));
        },

        warn: function(s,values,offset) {
          self.warn(prepare(s,values,false,offset));
        },

        exception: function(e) {
          self.warn(prepare(e.message,null,true));
        }
      }
    }

    return Utils.extend(obj,ext);
  }
}

module.exports = function() {
  
  return new Log();
}