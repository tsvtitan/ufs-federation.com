/**
 * Development environment settings
 *
 * This file can include shared settings for a development team,
 * such as API keys or remote database passwords.  If you're using
 * a version control solution for your Sails app, this file will
 * be committed to your repository unless you add it to your .gitignore
 * file.  If your repository will be publicly viewable, don't add
 * any private information to this file!
 *
 */

module.exports = {

  /***************************************************************************
   * Set the default database connection for models in the development       *
   * environment (see config/connections.js and config/models.js )           *
   ***************************************************************************/
  port: process.env.PORT || 1337,
  environment: process.env.NODE_ENV || 'development',
  
  connections: {
    
    workConnection: {
      adapter: 'sails-mongo',
      host: 'test.ufs-federation.com',
      //host: 'db1.ufs-federation.com',
      port: 27017,
      // user: 'username',
      // password: 'password',
      database: 'service'
    },
    
    wwwConnection: {
      adapter: 'sails-mysql',
      host: 'test.ufs-federation.com',
      //host: 'db1.ufs-federation.com',
      user: 'ufs',
      password: 'XIqGeug9',
      database: 'ufs-federation.com'
    }
  },
  
  models: {
    connection: 'workConnection',
    migrate: 'alter'
  },
  
  /*session: {
    adapter: 'mongo',
    //host: 'test.ufs-federation.com',
    host: 'db1.ufs-federation.com',
    port: 27017,
    db: 'service',
    collection: 'sessions',
    autoReconnect: true
  },*/
  
  session: {
    adapter: 'redis',
    host: 'db1.ufs-federation.com',
    db: 1
  },
  
  sockets: {
    adapter: 'socket.io-redis',
    host: 'db1.ufs-federation.com'
  },
  
  messageGate: {
   //url: 'http://192.168.34.2:8080/MessageGate/WebService?wsdl',
   url: 'http://app.ufs-federation.com:8080/MessageGate/WebService?wsdl',
   unique: false
  },
  
  dataService: {
    subscriptionCountUrl: 'http://ru.test.ufs-federation.com/data/subscription?count&lang={lang}',
    subscriptionUrl: 'http://ru.test.ufs-federation.com/data/subscription?lang={lang}'
  },
  
  bloombergExtractor: {
    //url: 'http://w8-vs2013:7000/BloombergExtractor/WebService?wsdl',
    url: 'http://blp1.ufs-federation.com:7000/BloombergExtractor/WebService?wsdl'
  },
  
  quikExtractor: {
    url: 'http://w8-vs2013:7000/QuikExtractor/WebService?wsdl'
  },
  
  finamExtractor: {
    historyUrl: 'http://195.128.78.52/file.txt?m={section}&em={id}&p={timeFrame}&df={day1}&mf={month1}&yf={year1}&dt={day2}&mt={month2}&yt={year2}&dtf={dateFormat}&tmf={timeFormat}&MSOR=0&cn={name}&sep=1&sep2=1&datf=5&at=1',
    ticUrl: 'http://195.128.78.52/file.txt?m={section}&em={id}&p={timeFrame}&df={day1}&mf={month1}&yf={year1}&dt={day2}&mt={month2}&yt={year2}&dtf={dateFormat}&tmf={timeFormat}&MSOR=0&cn={name}&sep=1&sep2=1&datf=9&at=1'
  },
  
  cors: {
    allRoutes: true
  },
  
  jobs: {
    
    disabled: false,
    
    db: {
      address: 'test.ufs-federation.com:27017/service',
      //address: 'db1.ufs-federation.com:27017/service',
      collection: 'jobs'
    },
    
    acs: {
      
      AbsenceDays: {
        disabled: true,
        interval: '1 minutes',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: false
        }
      },
      
      WorkDayHours: {
        disabled: true,
        interval: '5 minutes',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: true
        }
      },
      WorkDayBegin: {
        disabled: true,
        interval: '5 minutes',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: true
        }
      },
      WorkWeekHours: {
        disabled: true,
        interval: '5 minutes',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: true
        }
      },
      WorkMonthHours: {
        disabled: true,
        interval: '5 minutes',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: true
        }
      }
    },
    
    www: {
      
      Recommendations: {
        disabled: true,
        interval: '10 minutes',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: true
        }
      },
      
      InstrumentValues: {
        disabled: true,
        interval: '10 minutes',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: true
        }
      },
      
      Commodities: {
        disabled: true,
        interval: '10 minutes',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: true
        }
      },
      
      DividendCalendar: {
        disabled: true,
        interval: '10 minutes',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: true
        }
      },
      
      IssuersDebtMarket: {
        disabled: true,
        interval: '05 10 * * *',
        options: {
          recipients: [
            {name:'tsv',contact:'tsv@ufs-financial.ch'}
          ],
          test: true
        }
      }
      
    },
    
    Mailings: {
      disabled: false,
      interval: '10 seconds'
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
    pattern: 'EmptyPattern',
    channel: ''
  },
  
  www: {
    
    sender: {
      name: 'Система обновления данных',
      email: 'mailer@lists.ufs-financial.ch'
    },
    pattern: 'EmptyPattern',
    channel: ''
  },
  
  events: {
    
    disable: false,
    
    host: 'db1.ufs-federation.com',
    scope: 'service',
    
    Mailings: {
      disabled: false
    }
    
  }
  
};
