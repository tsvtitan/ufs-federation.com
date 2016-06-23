
module.exports = {

  connection: 'wwwConnection',
  migrate: 'safe',
  tableName: 'v_instrument_params',
  autoPK: false,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    
    instrument_id: {
      type: 'integer'
    },
    
    param_id: {
      type: 'integer'
    },
    
    locked: {
      type: 'datetime'
    },
    
    size: {
      type: 'integer' 
    },
    
    instrument_ident: {
      type: 'string'
    },
    
    instrument_locked: {
      type: 'datetime'
    },
    
    param_ident: {
      type: 'string'
    },
    
    param_type: {
      type: 'integer'
    },
    
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  }
  
}