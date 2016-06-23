/**
 * Route Mappings
 * (sails.config.routes)
 *
 * Your routes map URLs to views and controllers.
 *
 * If Sails receives a URL that doesn't match any of the routes below,
 * it will check for matching files (images, scripts, stylesheets, etc.)
 * in your assets directory.  e.g. `http://localhost:1337/images/foo.jpg`
 * might match an image file: `/assets/images/foo.jpg`
 *
 * Finally, if those don't match either, the default 404 handler is triggered.
 * See `api/responses/notFound.js` to adjust your app's 404 logic.
 *
 * Note: Sails doesn't ACTUALLY serve stuff from `assets`-- the default Gruntfile in Sails copies
 * flat files from `assets` to `.tmp/public`.  This allows you to do things like compile LESS or
 * CoffeeScript for the front-end.
 *
 * For more information on configuring custom routes, check out:
 * http://sailsjs.org/#/documentation/concepts/Routes/RouteTargetSyntax.html
 */

module.exports.routes = {

  /***************************************************************************
  *                                                                          *
  * Make the view located at `views/homepage.ejs` (or `views/homepage.jade`, *
  * etc. depending on your default view engine) your home page.              *
  *                                                                          *
  * (Alternatively, remove this and add an `index.html` file in your         *
  * `assets` directory)                                                      *
  *                                                                          *
  ***************************************************************************/

  'get /service':  {view:'index'},

  /***************************************************************************
  *                                                                          *
  * Custom routes here...                                                    *
  *                                                                          *
  *  If a request to a URL doesn't match any of the custom routes above, it  *
  * is matched against Sails route blueprints. See `config/blueprints.js`    *
  * for configuration options and examples.                                  *
  *                                                                          *
  ***************************************************************************/

  'get /service/cookie': 'CookieController.get', 
  'get /service/captcha/login': 'CaptchaController.login',
  'get /service/mailings/:mailingId/attachments/:attachmentId': {controller:'MailingsController', action:'attachment', policy:'accessGranted'},
  
  'post /service/api/init': 'InitController.get',
 
  'post /service/api/login': 'AuthController.login',
  'post /service/api/logout': {controller:'AuthController', action:'logout', policy:'sessionAuth'},

  'post /service/api/mailings': {controller:'MailingsController', action:'index', policy:'accessGranted'},
  'post /service/api/mailings/new': {controller:'MailingsController', action:'new', policy:'accessGranted'},
  'post /service/api/mailings/test': {controller:'MailingsController', action:'test', policy:'accessGranted'},
  'post /service/api/mailings/lists': {controller:'MailingsController', action:'lists', policy:'accessGranted'},
  'post /service/api/mailings/get': {controller:'MailingsController', action:'get', policy:'accessGranted'},
  'post /service/api/mailings/cancel': {controller:'MailingsController', action:'cancel', policy:'accessGranted'},
  'post /service/api/mailings/suspend': {controller:'MailingsController', action:'suspend', policy:'accessGranted'},
  'post /service/api/mailings/resume': {controller:'MailingsController', action:'resume', policy:'accessGranted'},
  'post /service/api/mailings/subscribe': {controller:'MailingsController', action:'subscribe', policy:'accessGranted', corps:true},
  'post /service/api/mailings/unsubscribe': {controller:'MailingsController', action:'unsubscribe', policy:'accessGranted', corps:true},
  'post /service/api/mailings/recipients': {controller:'MailingsController', action:'recipients', policy:'accessGranted'},
  'post /service/api/mailings/body': {controller:'MailingsController', action:'body', policy:'accessGranted'},
  'post /service/api/mailings/send': {controller:'MailingsController', action:'send', policy:'accessGranted'},
  'post /service/api/mailings/accelerate': {controller:'MailingsController', action:'accelerate', policy:'accessGranted'},
  
  '/service/api/jobs/start': {controller:'JobsController', action:'start', policy:'accessGranted'},
  
  '/service/api/acs/register': {controller:'AcsController', action:'register', policy:'accessGranted'},
  
  //'/service/model/backLogin': {policy:'sessionAuth'},
  
  '/service/test': {view:'test'},
  '/service/test/req': {controller:'TestController', action:'req'/*, policy:'accessGranted'*/}
          
};
