// DataService

var request = require('request');
        
module.exports = {

  subscriptionCount: function(lang,result) {
    
    var log = this.log;
    try {
      
      var url = Utils.format(sails.config.dataService.subscriptionCountUrl,{lang:lang});
      request(url,function(err,response,body){

        if (err) result(err);
        else {
          try {
            var data = JSON.parse(body);
            result(err,data.count);
          } catch(e) {
            result(Utils.format('Couldn\'t parse json: %s',[e.message]));
          }
        }
      });
    } catch(e) {
      result(e.message);
    }
  },
  
  subscriptionRecipients: function(lang,result) {
    
    try {
      
      var url = Utils.format(sails.config.dataService.subscriptionUrl,{lang:lang});
      request(url,function(err,response,body){

        if (err) result(err);
        else {
          try {
            var data = JSON.parse(body);
            result(err,data.recipients);
          } catch(e) {
            result(Utils.format('Couldn\'t parse json: %s',[e.message]));
          }  
        }
      });
    } catch(e) {
      result(e.message);
    }
  }
  
}
