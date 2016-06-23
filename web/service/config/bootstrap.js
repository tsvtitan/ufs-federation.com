/**
 * Bootstrap
 * (sails.config.bootstrap)
 *
 * An asynchronous bootstrap function that runs before your Sails app gets lifted.
 * This gives you an opportunity to set up your data model, run jobs, or perform some special logic.
 *
 * For more information on bootstrapping your app, check out:
 * http://sailsjs.org/#/documentation/reference/sails.config/sails.config.bootstrap.html
 */

var Parser = require('ua-parser-js'),
    Init = require('./../api/init/init.js');

module.exports.bootstrap = function(cb) {

  Init();
  
  sails.on('router:route', function(requestState) {
    
    var req = requestState.req;
    var res = requestState.res;
    
    if (!res.fmt && !res.dic) {
      
      req.fmt = res.fmt = Utils.format;

      var delim = sails.config.i18n.objectNotation;
      if (typeof(delim)==='boolean' && delim) {
        delim = '.';
      }

      function getDic(arr,name,values) {

        if (util.isArray(arr) && arr.length>0) {

          var s1 = arr.join(delim)+delim+name;
          var s2 = res.i18n(s1);

          if (s1!==s2) {
            return Utils.format(s2,values);
          } else {
            arr.pop();
            return getDic(arr,name,values);
          }
        } return res.i18n(Utils.format(name,values));

      }

      req.dic = function(name,values) {
        return getDic(['back',req.options.controller || req.options.view || res.locals.view],name,values);
      }
      res.dic = req.dic;
      res.locals.dic = res.dic;
    }
    
    if (res.view && Utils.isFunction(res.view) && !res.oldView) {
      res.oldView = res.view;
      res.view = function(name,values,result) {
        return res.oldView(name,Utils.extend(values,{view:name}),result);
      }
    }
    
    if (!req.userAgent) req.userAgent = new Parser().setUA(req.headers['user-agent']).getResult();
    
    if (req.session) {
      req.session.payload = (req.body && req.body.payload)?req.body.payload:false;
    } 
    
    req.options.jsonp = req.wantsJSON && /callback=/.test(req.url);
    
  });
  
  
  
  // It's very important to trigger this callback method when you are finished
  // with the bootstrap!  (otherwise your server will never lift, since it's waiting on the bootstrap)
  cb();
};
