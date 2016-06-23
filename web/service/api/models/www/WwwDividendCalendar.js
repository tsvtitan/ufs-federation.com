
module.exports = {

  connection: 'wwwConnection',
  migrate: 'safe',
  tableName: 'dividend_calendar',
  autoPK: false,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    
    id: {
      type: 'integer',
      unique: true,
      primaryKey: true
    },
    timestamp: {
      type: 'datetime'
    },
    lang: {
      type: 'string'
    },
    dividends: {
      type: 'string'
    },
    dividend_yield: {
      type: 'string'
    },
    close_date: {
      type: 'date'
    },
    price: {
      type: 'string'
    },
    bloomberg_ident: {
      type: 'string'
    },
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  }
  
}
