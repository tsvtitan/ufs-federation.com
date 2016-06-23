
module.exports = {

  connection: 'wwwConnection',
  migrate: 'safe',
  tableName: 'logins',
  autoPK: false,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    id: {
      type: 'integer',
      unique: true,
      primaryKey: true
    },
    login: {
      type: 'string'
    },
    pass: {
      type: 'string'
    },
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  }
  
}

