/**
 * Production environment settings
 *
 * This file can include shared settings for a production environment,
 * such as API keys or remote database passwords.  If you're using
 * a version control solution for your Sails app, this file will
 * be committed to your repository unless you add it to your .gitignore
 * file.  If your repository will be publicly viewable, don't add
 * any private information to this file!
 *
 */

module.exports = {

  /***************************************************************************
   * Set the default database connection for models in the production        *
   * environment (see config/connections.js and config/models.js )           *
   ***************************************************************************/
  
  port: process.env.PORT || 1337,
  environment: process.env.NODE_ENV || 'production',
  
  connections: {
    
    workConnection: {
      adapter: 'sails-mongo',
      host: 'db1.ufs-federation.com',
      database: 'service'
    },
    
    wwwConnection: {
      adapter: 'sails-mysql',
      host: 'www.ufs-federation.com',
      user: 'ufs',
      password: 'XIqGeug9',
      database: 'ufs-federation.com'
    }
  },
  
  models: {
    connection: 'workConnection',
    migrate: 'alter'
  },
  
  session: {
    adapter: 'redis',
    host: 'db1.ufs-federation.com'
  },
  
  sockets: {
    adapter: 'socket.io-redis',
    host: 'db1.ufs-federation.com'
  },
  
  messageGate: {
    url: 'http://app.ufs-federation.com:8080/MessageGate/WebService?wsdl',
    unique: true,
    channel: 'UFSExchangeOutgoing'
  },
  
  dataService: {
    subscriptionCountUrl: 'http://ru.ufs-federation.com/data/subscription?count&lang={lang}',
    subscriptionUrl: 'http://ru.ufs-federation.com/data/subscription?lang={lang}'
  },
  
  bloombergExtractor: {
    url: 'http://blp1.ufs-federation.com:7000/BloombergExtractor/WebService?wsdl'
  },
  
  cors: {
    allRoutes: true
  },
  
  jobs: {
    
    disabled: true,
    
    db: {
      address: 'db1.ufs-federation.com:27017/service',
      collection: 'jobs'
    }
  },
  
  acs: {
    
    db: {
      host: '192.168.31.2',
      port: 3050,
      database: 'c:\\SCD17K.FDB',
      user: 'SYSDBA',
      password: 'masterkey'
    },
    
    sender: {
      name: 'Система контроля рабочего времени',
      email: 'mailer@lists.ufs-financial.ch'
    },
    pattern: 'EmptyPattern'//,
    //channel: 'UFSExchangeOutgoing'
  },
  
  www: {
    
    sender: {
      name: 'Система обновления данных',
      email: 'mailer@lists.ufs-financial.ch'
    },
    pattern: 'EmptyPattern'//,
    //channel: 'UFSExchangeOutgoing'
  },
  
  events: {
    
    disable: false,
    
    host: 'db1.ufs-federation.com',
    scope: 'service'
  }
  
};
