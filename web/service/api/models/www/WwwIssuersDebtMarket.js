
module.exports = {

  connection: 'wwwConnection',
  migrate: 'safe',
  tableName: 'issuers_debt_market',
  autoPK: false,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    
     id: {
      type: 'integer',
      unique: true,
      primaryKey: true
    },
    
    volume: 'integer',
    rate: 'float',
    next_coupon: 'datetime',
    payments_per_year: 'integer',
    maturity_date: 'datetime',
    closing_price: 'float',
    income: 'float',
    duration: 'float',
    rating_sp: 'string',
    rating_moodys: 'string',
    rating_fitch: 'string',
    type: 'string',
    timestamp: 'datetime',
    lang: 'string',
    bloomberg_ident: 'string',
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  }
  
}
