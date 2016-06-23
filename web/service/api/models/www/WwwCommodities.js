
module.exports = {

  connection: 'wwwConnection',
  migrate: 'safe',
  tableName: 'commodities',
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
    price_current: {
      type: 'string'
    },
    price_fair: {
      type: 'string'
    },
    potential: {
      type: 'string'
    },
    recommendation: {
      type: 'string'
    },
    price_fair_original: {
      type: 'float'
    },
    bloomberg_ident: {
      type: 'string'
    },
    default: {
      type: 'string'
    },
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  }
  
}
