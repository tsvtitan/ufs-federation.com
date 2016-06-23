// Instruments

module.exports = {

  connection: 'workConnection',
  migrate: 'safe',
  autoPK: true,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    
    created: {
      type: 'datetime',
      required: true,
      defaultsTo: function () { return new Date(); }
    },
    
    name: {
      type: 'string'
    },
    
    description: {
      type: 'string'
    },
    
    currency: {
      type: 'string'
    },
    
    isin: {
      type: 'string'
    },
    
    issuer: {
      type: 'string'  
    },
    
    bloomberg: {
      type: 'json'
    },
    
    quik: {
      type: 'json'
    },
    
    finam: {
      type: 'json'
    },
    
    disabled: {
      type: 'boolean'
    },
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  }
  
};