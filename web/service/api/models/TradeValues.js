// TradeValues

module.exports = {

  migrate: 'safe',
  autoPK: true,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    
    created: {
      type: 'datetime',
      defaultsTo: function (){ return new Date(); }
    },
    
    obtained: {
      type: 'datetime'
    },
    
    instrumentId: {
      type: 'string'
    },
    
    source: {
      type: 'string'
    },
    
    parameterId: {
      type: 'string'
    },
    
    valueFloat: {
      type: 'float'
    },
    
    valueInteger: {
      type: 'integer'
    },
    
    valueString: {
      type: 'string'
    },
    
    valueDatetime: {
      type: 'datetime'
    },
    
    error: {
      type: 'string'
    },
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  }
  
};