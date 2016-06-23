/**
 * Built-in Log Configuration
 * (sails.config.log)
 *
 * Configure the log level for your app, as well as the transport
 * (Underneath the covers, Sails uses Winston for logging, which
 * allows for some pretty neat custom transports/adapters for log messages)
 *
 * For more information on the Sails logger, check out:
 * http://sailsjs.org/#/documentation/concepts/Logging
 */

var winston = require('winston');

function tryCustomLogger(colorize) {
  
  var logger = new winston.Logger({
    levels: {
      'silent': 6,
      'error': 5,
      'warn': 4,
      'debug': 3,
      'info': 2,
      'verbose': 1,
      'silly': 0
    },
    transports: [
      new winston.transports.File({
        level: 'silly',
        filename: 'silly.log',
        dirname: __dirname+'/../.tmp/logs',
        json: false,
        colorize: false,
        handleExceptions: true,
        maxsize: 1*1024*1024*1024,
        maxFiles: 10,
        tailable: true
      }),
      new winston.transports.Console({
        level: 'info',
        colorize: colorize,
        handleExceptions: false,
        json: false
      })
    ],
    exceptionHandlers: [
      new winston.transports.Console({
        level: 'info',
        colorize: colorize,
        handleExceptions: true,
        json: true
      })
    ],
    exitOnError: false
  });
  return logger;
}

//var customLogger = tryCustomLogger();


module.exports.log = {

  /***************************************************************************
  *                                                                          *
  * Valid `level` configs: i.e. the minimum log level to capture with        *
  * sails.log.*()                                                            *
  *                                                                          *
  * The order of precedence for log levels from lowest to highest is:        *
  * silly, verbose, info, debug, warn, error                                 *
  *                                                                          *
  * You may also set the level to "silent" to suppress all logs.             *
  *                                                                          *
  ***************************************************************************/

  level: 'silly',
  colors: false,
  custom: tryCustomLogger(false)
};
