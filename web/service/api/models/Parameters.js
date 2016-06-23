// Parameters

module.exports = {

  migrate: 'safe',
  autoPK: true,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    
    ident: {
      type: 'string'
    },
    
    name: {
      type: 'string'
    },
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  }
  
};