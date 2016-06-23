// BloombergExtractor

var soap = require('soap');

module.exports = {
  
  connect: function(result) {
    
    var options = {};
    
    try {
      soap.createClient(sails.config.quikExtractor.url,options,function(err,client) {
        if (client) {
          client.addSoapHeader('');
        }
        result(err,client);
      });
    } catch(e) {
      result(e.message);
    }  
  },
  
  getTradeValues: function(instruments,result) {
    
    try {
      
      this.connect(function(err,client) {
        
        if (err) result(err);
        else {
          
          var args = {
            instruments: {
              Instrument: instruments
            }
          };
          
          client.getTradeValues(args,function(err,r) {

            if (err) result(err);
            else if (r.getTradeValuesResult) {
              result(null,r.getTradeValuesResult.TradeValue);
           } else result('No getTradeValuesResult');
          });
        }
        
      });
    } catch(e) {
      result(e.message);
    }  
  }
}

