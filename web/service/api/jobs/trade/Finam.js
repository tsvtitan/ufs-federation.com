// FinamJob

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
            'finam.disabled': [null,undefined,false]
          };
          
          Instruments.find({where:where,sort:{created:-1}},
                           {fields:{id:1,finam:1}},
                           function(err,instruments){
            result(err,instruments);
          });
        },
        
        function getTradeValues(instruments,result) {
          
          if (instruments && instruments.length>0) {
            
            log.debug('Found %d instruments',[instruments.length]);
            
            var arr = [];
            
            Utils.forEach(instruments,function(instrument){
              
              var finam = instrument.finam;
              if (finam && finam.parameters && finam.parameters.length>0) {
                
                var i = {
                  id: instrument.id,
                  nameId: finam.id,
                  name: finam.name,
                  parameters: []
                };
                
                Utils.forEach(finam.parameters,function(parameter){
                  
                  if (!parameter.disabled) {
                    
                    var p = {
                      ident: (parameter.ident)?parameter.ident:parameter.name,
                      name: (parameter.name)?parameter.name:parameter.ident,
                      type: (parameter.type)?parameter.type:'float'
                    };
                    i.parameters.push(p);
                  }
                });
                
                arr.push(i);
              }
            });
            
            FinamExtractor.getTradeValues(arr,function(err,values){
              result(err,values);
            });
            
          } else result(null,null);
        },
        
        function createTradeValues(tradeValues,result){
          
          if (tradeValues && tradeValues.length>0) {
            
            console.log(tradeValues);
            log.debug('Found %d trade values',[tradeValues.length]);
            
            Utils.forEach(tradeValues,function(tradeValue){
              
              var value = tradeValue.value;
              
              delete tradeValue.value;
              
              switch (tradeValue.type) {
                case 'float': {
                  tradeValue.valueFloat = value;
                  break;
                }
                case 'integer': {
                  tradeValue.valueInteger = value;
                  break;
                }
                case 'datetime': {
                  tradeValue.valueDatetime = moment(value).toDate(); 
                  break;
                }
                case 'string': {
                  tradeValue.valueString = value; 
                  break;
                }
              }
              
              delete tradeValue.type;
              
              tradeValue.source = 'finam';
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
