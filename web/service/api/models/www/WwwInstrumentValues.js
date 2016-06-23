
module.exports = {

  connection: 'wwwConnection',
  migrate: 'safe',
  tableName: 'instrument_values',
  autoPK: false,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    
     instrument_value_id: {
      type: 'integer',
      unique: true,
      primaryKey: true
    },
    
    param_id: 'integer',
    instrument_id: 'integer',
    currency_id: 'integer',
    created: 'datetime',
    to_date: 'datetime',
    value_number: 'float',
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  }
  
}
