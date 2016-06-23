// QuikJob

module.exports = {
  
  disabled: true,
  interval: '30 seconds',
  autoStart: true,
  data: null,
  
  execute: function(job,done) {
    
    var log = this.log;
    try {
      
      async.waterfall([
        
        function findInstruments(result){
          
          var where = {
            disabled: [null,undefined,false],
            'quik.disabled': [null,undefined,false]
          };
          
          Instruments.find({where:where,sort:{created:-1}},
                           {fields:{id:1,quik:1}},
                           function(err,instruments){
            result(err,instruments);
          });
        },
        
        function getTradeValues(instruments,result) {
          
          if (instruments && instruments.length>0) {
            
            log.debug('Found %d instruments',[instruments.length]);
            
            var arr = [];
            
            Utils.forEach(instruments,function(instrument){
              
              var quik = instrument.quik;
              if (quik && quik.parameters && quik.parameters.length>0) {
                
                var i = {
                  id: instrument.id,
                  name: quik.name,
                  parameters: {
                    Parameter: []
                  }
                };
                
                Utils.forEach(quik.parameters,function(parameter){
                  
                  if (!parameter.disabled) {
                    
                    var p = {
                      ident: (parameter.ident)?parameter.ident:parameter.name,
                      name: (parameter.name)?parameter.name:parameter.ident,
                      type: (parameter.type)?parameter.type:'float'
                    };
                    i.parameters.Parameter.push(p);
                  }
                });
                
                arr.push(i);
              }
            });
            
            QuikExtractor.getTradeValues(arr,function(err,values){
              result(err,values);
            });
            
          } else result(null,null);
        },
        
        function createTradeValues(tradeValues,result){
          
          if (tradeValues && tradeValues.length>0) {
            
            log.debug('Found %d trade values',[tradeValues.length]);
            
            Utils.forEach(tradeValues,function(tradeValue){
              
              var value = tradeValue.value;
              var s = (tradeValue.value.$value)?tradeValue.value.$value:null;

              delete tradeValue.value;
              
              switch (tradeValue.type) {
                case 'float': {
                  tradeValue.valueFloat = (s)?parseFloat(s):value;
                  break;
                }
                case 'integer': {
                  tradeValue.valueInteger = (s)?parseInt(s):value;
                  break;
                }
                case 'datetime': {
                  tradeValue.valueDatetime = (s)?moment(s).toDate():value; 
                  break;
                }
                case 'string': {
                  tradeValue.valueString = (s)?s:value; 
                  break;
                }
              }
              
              delete tradeValue.type;
              
              tradeValue.source = 'quik';
            });
            
            TradeValues.create(tradeValues,function(err){

              result(err,false);
            });
            
          } else result(null,false);
        }
        
      ],function(err,stop) {
        if (err) log.error(err);
        else if (stop) {
          job.stop();
        }
        done();
      });
      
    } catch(e) {
      log.exception(e);
      done();
    }
  }
}
