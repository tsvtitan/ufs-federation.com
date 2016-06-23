/**
 * HTTP Server Settings
 * (sails.config.http)
 *
 * Configuration for the underlying HTTP server in Sails.
 * Only applies to HTTP requests (not WebSockets)
 *
 * For more information on configuration, check out:
 * http://sailsjs.org/#/documentation/reference/sails.config/sails.config.http.html
 */

module.exports.http = {

  /****************************************************************************
  *                                                                           *
  * Express middleware to use for every Sails request. To add custom          *
  * middleware to the mix, add a function to the middleware config object and *
  * add its key to the "order" array. The $custom key is reserved for         *
  * backwards-compatibility with Sails v0.9.x apps that use the               *
  * `customMiddleware` config option.                                         *
  *                                                                           *
  ****************************************************************************/

  middleware: {

  /***************************************************************************
  *                                                                          *
  * The order in which middleware should be run for HTTP request. (the Sails *
  * router is invoked by the "router" middleware below.)                     *
  *                                                                          *
  ***************************************************************************/

    order: [
       'startRequestTimer',
       'cookieParser',
       'session',
       //'rawBody',
       'bodyParser',
       'handleBodyParserError',
       'compress',
       'methodOverride',
       'poweredBy',
       '$custom',
       'router',
       'www',
       'favicon',
       '404',
       '500'
    ],

  /****************************************************************************
  *                                                                           *
  * Example custom middleware; logs each request to the console.              *
  *                                                                           *
  ****************************************************************************/

    /*rawBody: function (req, res, next) {
      
      
      if (req.headers['content-length']) {
       
        console.log(req.headers);
        
        var getBody = require('raw-body');
        var opts = { limit:1000000, expected:req.headers['content-length'] };

        getBody(req,opts,function (err,buf) {

          if (err) return next(err);

          // Make string from buffer
          buf = buf.toString('utf8').trim();

          console.log(buf);
          return next();
        });
        
      } else return next();
    },*/
     
    poweredBy: function xPoweredBy(req, res, next) {
      res.header('X-Powered-By', 'UFS IC Service');
      return next();
    },


  /***************************************************************************
  *                                                                          *
  * The body parser that will handle incoming multipart HTTP requests. By    *
  * default as of v0.10, Sails uses                                          *
  * [skipper](http://github.com/balderdashy/skipper). See                    *
  * http://www.senchalabs.org/connect/multipart.html for other options.      *
  *                                                                          *
  ***************************************************************************/

    // bodyParser: require('skipper')
  },
  
  bodyParser: function() {
    var opts = {
      limit:'10mb'
    }
    return require('./../node_modules/sails/node_modules/skipper')(opts);
  }
  
  

  /***************************************************************************
  *                                                                          *
  * The number of seconds to cache flat files on disk being served by        *
  * Express static middleware (by default, these files are in `.tmp/public`) *
  *                                                                          *
  * The HTTP static cache is only active in a 'production' environment,      *
  * since that's the only time Express will cache flat-files.                *
  *                                                                          *
  ***************************************************************************/

  // cache: 31557600000
};
