// WwwService

module.exports = {

  sendMailing: function(options,body,result) {
    
    var log = this.log;
    try {
      
      async.waterfall([
        
        function trySend(ret) {
          
          if (body) {
            
            console.log(body);
            var delay = (options.delay)?options.delay:0;
            var duration = (options.duration)?options.duration:30;
            
            var begin = moment().add({seconds:delay});
            var end = moment().add({seconds:delay}).add({minutes:duration});
              
            var mailing = {
              created: new Date(),
              creator: options.creator,
              subject: options.subject,
              body: new Buffer(body).toString('base64'),
              begin: begin.toDate(),
              end: end.toDate(),
              sender: sails.config.www.sender,
              recipients: options.recipients,
              pattern: sails.config.www.pattern,
              channel: sails.config.www.channel,
              test: options.test
            }
            
            MessageGate.sendMailing(mailing,function(err,message){

              if (err) ret(err,null);
              else {
                mailing.messageId = message.messageId;
                mailing.allCount = message.queueLength;
                ret(null,mailing);
              }
            });
            
          } else ret(null,null);
        },
        
        function createMailing(mailing,ret) {
          
          if (mailing) {
            
            Mailings.create(mailing,function(err,m){

              Mailings.eventCreate(m);
              ret(err,m);
            });
            
          } else ret(null);  
        }
        
      ],function(err,m) {
        result(err,m);
      });
      
    } catch(e) {
      result(e.message);
    }    
  },
  
  sendError: function(options,err,result) {
    this.sendMailing(options,Utils.format('Ошибка: %s',[err]),result);
  },
  
  recalcRecommendations: function(options,result) {
     
    var self = this; 
    var log = this.log;
    try {
      
      async.waterfall([
      
        function getRecommendations(ret) {
          
          var where = {
            lang: ['ru','en'],
            bloomberg_ident: {'!':null},
            price_fair_original: {'!':null}
          }
          
          var fields = {
            id:1,currency:1,lang:1,price_fair_original:1,bloomberg_ident:1,default:1
          }
          
          WwwRecommendations.find({where:where},{fields:fields},
                                  function(err,recommendations){
            
            ret(err,recommendations);
          });
        },
        
        function getBloombergData(recommendations,ret) {
          
          if (Utils.isArray(recommendations) && recommendations.length>0) {
            
            var instruments = [];
            
            Utils.forEach(recommendations,function(r){
              
              var i = {
                id: r.id,
                name: r.bloomberg_ident.toUpperCase(),
                parameters: {
                  Parameter: [{
                    ident: "PX_CLOSE_1D",
                    name: "PX_CLOSE_1D",
                    type: "float"
                  }]
                }
              }
              
              instruments.push(i);
            });
            
            BloombergExtractor.getTradeValues(instruments,function(err,values){
              
              if (err && Utils.isObject(err)) {
                log.error(err);
                ret(null,null,null);
              } else ret(err,recommendations,values);
            });
            
          } else ret('нет рекомендаций для обновления',null,null);
        },
        
        function recalcRecommendations(recommendations,values,ret) {
          
          if (Utils.isArray(recommendations) && Utils.isArray(values) && values.length>0) {
            
            var descriptions = {
              en: {
                buy: 'buy',
                hold: 'hold',
                sell: 'sell',
                revision: 'revision'
              },
              ru: {
                buy: 'покупать',
                hold: 'держать',
                sell: 'продавать',
                revision: 'пересмотр'
              }
            };
            
            var revisions = [descriptions.ru.revision,descriptions.en.revision];
            
            Utils.forEach(recommendations,function(r){
              
              Utils.filter(values,function(v){
                
                return v.instrumentId == r.id;
                
              }).forEach(function(v){
                
                if (v.value) {
                  var s = (v.value.$value)?v.value.$value:null;
                  var value = (s)?parseFloat(s):v.value;
                  var desc = r.recommendation;
                  var usable = revisions.indexOf(desc)===-1 && !Utils.isEmpty(r.default);

                  if (value && value!==0.0) {

                    var s = value.toString().replace('.',',');
                    r.price_currency = s+' '+r.currency;

                    if (usable) {
                      var ratio = parseFloat((r.price_fair_original/value-1).toFixed(2));
                      r.potential = Math.round(ratio*100);

                      if (ratio<0.2) {
                        r.recommendation = (ratio>=-0.05)?descriptions[r.lang].hold:descriptions[r.lang].sell;
                      } else r.recommendation = descriptions[r.lang].buy;
                    }
                  }
                }
              });
            });
            
            ret(null,recommendations);
            
          } else ret('нет данных от блумберга',null);  
        },
        
        function setRecommendations(recommendations,ret) {
          
          if (Utils.isArray(recommendations)) {
            
            var t = moment().toDate();
            
            async.eachSeries(recommendations,function(r,callback){
              
              if (r) {
                
                var r1 = {
                  timestamp: t,
                  price_currency: r.price_currency,
                  potential: r.potential,
                  recommendation: r.recommendation
                }
                
                WwwRecommendations.update({id:r.id},r1,function(err){
                  
                  callback(err);
                });
                
              } else callback(null);
              
            },function(err){
              ret(err);
            });
            
          } else ret(null);  
        }
        
      ],function(err) {
        
        if (err) {
          self.sendError(options,err,function(){
            result(err);
          });
        } else result(null);
      });
      
    } catch(e) {
      result(e.message);
    }   
  },
  
  recalcCommodities: function(options,result) {
     
    var self = this; 
    var log = this.log;
    try {
      
      async.waterfall([
      
        function getCommodities(ret) {
          
          var where = {
            lang: ['ru','en'],
            bloomberg_ident: {'!':null},
            price_fair_original: {'!':null}
          }
          
          var fields = {
            id:1,lang:1,price_fair_original:1,bloomberg_ident:1,default:1
          }
          
          WwwCommodities.find({where:where},{fields:fields},
                              function(err,commodities){
            
            ret(err,commodities);
          });
        },
        
        function getBloombergData(commodities,ret) {
          
          if (Utils.isArray(commodities) && commodities.length>0) {
            
            var instruments = [];
            
            Utils.forEach(commodities,function(r){
              
              var i = {
                id: r.id,
                name: r.bloomberg_ident.toUpperCase(),
                parameters: {
                  Parameter: [{
                    ident: "PX_CLOSE_1D",
                    name: "PX_CLOSE_1D",
                    type: "float"
                  }]
                }
              }
              
              instruments.push(i);
            });
            
            BloombergExtractor.getTradeValues(instruments,function(err,values){
              
              if (err && Utils.isObject(err)) {
                log.error(err);
                ret(null,null,null);
              } else ret(err,commodities,values);
            });
            
          } else ret('нет товарного рынка для обновления',null,null);
        },
        
        function recalcCommodities(commodities,values,ret) {
          
          if (Utils.isArray(commodities) && Utils.isArray(values) && values.length>0) {
            
            var descriptions = {
              en: {
                buy: 'buy',
                hold: 'hold',
                sell: 'sell',
                revision: 'revision'
              },
              ru: {
                buy: 'покупать',
                hold: 'держать',
                sell: 'продавать',
                revision: 'пересмотр'
              }
            };
            
            var revisions = [descriptions.ru.revision,descriptions.en.revision];
            
            Utils.forEach(commodities,function(c){
              
              Utils.filter(values,function(v){
                
                return v.instrumentId == c.id;
                
              }).forEach(function(v){
                
                if (v.value) {
                  var s = (v.value.$value)?v.value.$value:null;
                  var value = (s)?parseFloat(s):v.value;
                  var desc = c.recommendation;
                  var usable = revisions.indexOf(desc)===-1 && !Utils.isEmpty(c.default);

                  if (value && value!==0.0) {

                    var s = value.toString().replace('.',',');
                    c.price_current = s;

                    if (usable) {
                      var ratio = parseFloat((c.price_fair_original/value-1).toFixed(2));
                      c.potential = Math.round(ratio*100);

                      if (ratio<0.2) {
                        c.recommendation = (ratio>=-0.05)?descriptions[c.lang].hold:descriptions[c.lang].sell;
                      } else c.recommendation = descriptions[c.lang].buy;
                    }
                  }
                }
              });
            });
            
            ret(null,commodities);
            
          } else ret('нет данных от блумберга',null);  
        },
        
        function setCommodities(commodities,ret) {
          
          if (Utils.isArray(commodities)) {
            
            var t = moment().toDate();
            
            async.eachSeries(commodities,function(c,callback){
              
              if (c) {
                
                var c1 = {
                  timestamp: t,
                  price_current: c.price_current,
                  potential: c.potential,
                  recommendation: c.recommendation
                }
                
                WwwCommodities.update({id:c.id},c1,function(err){
                  
                  callback(err);
                });
                
              } else callback(null);
              
            },function(err){
              ret(err);
            });
            
          } else ret(null);  
        }
        
      ],function(err) {
        
        if (err) {
          self.sendError(options,err,function(){
            result(err);
          });
        } else result(null);
      });
      
    } catch(e) {
      result(e.message);
    }   
  },
  
  recalcDividendCalendar: function(options,result) {
     
    var self = this; 
    var log = this.log;
    try {
      
      async.waterfall([
      
        function getCalendar(ret) {
          
          var where = {
            lang: ['ru','en'],
            bloomberg_ident: {'!':null},
            dividends: {'!':null},
            close_date: {'!':null}
          }
          
          var fields = {
            id:1,lang:1,dividends:1,close_date:1,bloomberg_ident:1
          }
          
          WwwDividendCalendar.find({where:where},{fields:fields},
                              function(err,calendar){
            
            ret(err,calendar);
          });
        },
        
        function getBloombergData(calendar,ret) {
          
          if (Utils.isArray(calendar) && calendar.length>0) {
            
            var instruments = [];
            
            Utils.forEach(calendar,function(r){
              
              var i = {
                id: r.id,
                name: r.bloomberg_ident.toUpperCase(),
                parameters: {
                  Parameter: [{
                    ident: "PX_LAST",
                    name: "PX_LAST",
                    type: "float"
                  }]
                }
              }
              
              instruments.push(i);
            });
            
            BloombergExtractor.getTradeValues(instruments,function(err,values){
              
              if (err && Utils.isObject(err)) {
                log.error(err);
                ret(null,null,null);
              } else ret(err,calendar,values);
            });
            
          } else ret('нет дивидедного календаря для обновления',null,null);
        },
        
        function recalcCalendar(calendar,values,ret) {
          
          if (Utils.isArray(calendar) && Utils.isArray(values) && values.length>0) {
            
            Utils.forEach(calendar,function(c){
              
              Utils.filter(values,function(v){
                
                return v.instrumentId == c.id;
                
              }).forEach(function(v){
                
                if (v.value) {
                  var s = (v.value.$value)?v.value.$value:null;
                  var value = (s)?parseFloat(s):v.value;
                  
                  var current = moment().startOf('day');
                  var close = moment(c.close_date);
                  
                  var usable = close.diff(current)>0;

                  if (value && value!==0.0 && usable) {
                    
                    var dividends = parseFloat(c.dividends);
                    var dividend_yield = (dividends/value)*100;
                    
                    c.dividend_yield = dividend_yield.toFixed(2)+'%';
                    c.price = value.toString();
                  }
                }
              });
            });
            
            ret(null,calendar);
            
          } else ret('нет данных от блумберга',null);  
        },
        
        function setCalendar(calendar,ret) {
          
          if (Utils.isArray(calendar)) {
            
            var t = moment().toDate();
            
            async.eachSeries(calendar,function(c,callback){
              
              if (c) {
                
                var c1 = {
                  timestamp: t,
                  price: c.price,
                  dividend_yield: c.dividend_yield
                }
                
                WwwDividendCalendar.update({id:c.id},c1,function(err){
                  
                  callback(err);
                });
                
              } else callback(null);
              
            },function(err){
              ret(err);
            });
            
          } else ret(null);  
        }
        
      ],function(err) {
        
        if (err) {
          self.sendError(options,err,function(){
            result(err);
          });
        } else result(null);
      });
      
    } catch(e) {
      result(e.message);
    }   
  },
  
  recalcIssuersDebtMarket: function(options,result) {
     
    var self = this; 
    var log = this.log;
    try {
      
      async.waterfall([
      
        function getIssuersDebtMarket(ret) {
          
          var where = {
            type: ['euro','rur','int_euro'],
            lang: ['ru'],
            bloomberg_ident: {'!':null}
          }
          
          var fields = {
            id:1,lang:1,type:1,bloomberg_ident:1
          }
          
          WwwIssuersDebtMarket.find({where:where},{fields:fields},
                                    function(err,issuers){
            
            ret(err,issuers);
          });
        },
        
        function getBloombergData(issuers,ret) {
          
          if (Utils.isArray(issuers) && issuers.length>0) {
            
            var instruments = [];
            
            Utils.forEach(issuers,function(r){
              
              var i = {
                id: r.id,
                name: r.bloomberg_ident.toUpperCase(),
                parameters: {
                  Parameter: [
                    {ident:"MATURITY",name:"MATURITY",type:"datetime"}, //maturity_date
                    {ident:"PX_CLOSE_1D",name:"PX_CLOSE_1D",type:"float"}, //closing_price
                    {ident:"YLD_1D",name:"YLD_1D",type:"float"}, //income
                    {ident:"YAS_YLD_FLAG=15",name:"YAS_YLD_FLAG=15",type:"flag"},
                    {ident:"DUR_MID",name:"DUR_MID",type:"float"}, //duration
                    {ident:"YLD_FLAG=15",name:"YLD_FLAG=15",type:"flag"},
                    {ident:"COUPON",name:"COUPON",type:"float"}, //rate
                    {ident:"NXT_CPN_DT",name:"NXT_CPN_DT",type:"datetime"}, //next_coupon
                    {ident:"AMT_ISSUED",name:"AMT_ISSUED",type:"integer"}, //volume
                    {ident:"CPN_FREQ",name:"CPN_FREQ",type:"integer"}, //payments_per_year
                    {ident:"RTG_SP",name:"RTG_SP",type:"string"}, //rating_sp
                    {ident:"RTG_MOODY",name:"RTG_MOODY",type:"string"}, //rating_moodys
                    {ident:"RTG_FITCH",name:"RTG_FITCH",type:"string"} //rating_fitch
                  ]
                }
              }
              
              instruments.push(i);
            });
            
            BloombergExtractor.getTradeValues(instruments,function(err,values){
              
              if (err && Utils.isObject(err)) {
                log.error(err);
                ret(null,null,null);
              } else ret(err,issuers,values);
            });
            
          } else ret('нет эмитентов долгового рынка для обновления',null,null);
        },
        
        function recalcIssuersDebtMarket(issuers,values,ret) {
          
          if (Utils.isArray(issuers) && Utils.isArray(values) && values.length>0) {
            
            Utils.forEach(issuers,function(c){
              
              Utils.filter(values,function(v){
                
                return v.instrumentId == c.id;
                
              }).forEach(function(v){
                
                if (v.value && v.parameterIdent) {
                  
                  var value = v.value;
                  var s = (v.value.$value)?v.value.$value:null;
                  
                  switch (v.parameterIdent) {
                    
                    case 'MATURITY': {
                      c.maturity_date = (s)?moment(s).toDate():value;
                      break;
                    }
                            
                    case 'PX_CLOSE_1D': {
                      c.closing_price = (s)?parseFloat(s):value;  
                      break;  
                    }
                    
                    case 'YLD_1D': {
                      c.income = (s)?parseFloat(s):value;
                      break;
                    }
                    
                    case 'DUR_MID': {
                      c.duration = (s)?parseFloat(s):value;
                      break;
                    }
                    
                    case 'COUPON': {
                      c.rate = (s)?parseFloat(s):value;
                      break;
                    }
                    
                    case 'NXT_CPN_DT': {
                      c.next_coupon = (s)?moment(s).toDate():value;
                      break;
                    }
                    
                    case 'AMT_ISSUED': {
                      c.volume = (s)?parseInt(s):value;
                      if (c.volume!==0.0) {
                        c.volume = c.volume / 1000000;
                      }
                      break;
                    }
                    
                    case 'CPN_FREQ': {
                      c.payments_per_year = (s)?parseInt(s):value;
                      break;
                    }
                    
                    case 'RTG_SP': {
                      c.rating_sp = (s)?s:value;
                      break;
                    }
                    
                    case 'RTG_MOODY': {
                      c.rating_moodys = (s)?s:value;
                      break;
                    }
                    
                    case 'RTG_FITCH': {
                      c.rating_fitch = (s)?s:value;
                      break;
                    }
                  }
                }
              });
            });
            
            ret(null,issuers);
            
          } else ret('нет данных от блумберга',null);  
        },
        
        function setIssuersDebtMarket(issuers,ret) {
          
          if (Utils.isArray(issuers)) {
            
            var t = moment().toDate();
            
            async.eachSeries(issuers,function(i,callback){
              
              if (i) {
                
                var i1 = {
                  timestamp: t,
                  maturity_date: i.maturity_date,
                  closing_price: i.closing_price,
                  income: i.income,
                  duration: i.duration,
                  rate: i.rate,
                  next_coupon: i.next_coupon,
                  volume: i.volume,
                  payments_per_year: i.payments_per_year,
                  rating_sp: i.rating_sp,
                  rating_moodys: i.rating_moodys,
                  rating_fitch: i.rating_fitch
                }
                
                WwwIssuersDebtMarket.update({id:i.id},i1,function(err){
                  
                  callback(err);
                });
                
              } else callback(null);
              
            },function(err){
              ret(err);
            });
            
          } else ret(null);  
        }
        
      ],function(err) {
        
        if (err) {
          self.sendError(options,err,function(){
            result(err);
          });
        } else result(null);
      });
      
    } catch(e) {
      result(e.message);
    }   
  },
  
  getInstrumentValues: function(options,result) {
    
    var self = this;
    var log = this.log;
    try {
      
      async.waterfall([
      
        function getInstrumentParams(ret) {
          
          var where = {
            instrument_ident: {'!':null},
            instrument_locked: null,
            param_ident: {'!':null},
            param_type: 0, // float only
            locked: null
          }
          
          var fields = {
            instrument_id:1,param_id:1,instrument_ident:1,param_ident:1
          }
          
          WwwVInstrumentParams.find({where:where},{fields:fields},
                              function(err,instrumentParams){
            ret(err,instrumentParams);
          });
        },
        
        function getBloombergData(instrumentParams,ret) {
          
          if (Utils.isArray(instrumentParams) && instrumentParams.length>0) {
            
            var instruments = [];
            
            Utils.forEach(instrumentParams,function(ip){
             
              ip.tempId = Utils.format('{instrument_id}-{param_id}',ip);
              var i = {
                id: ip.tempId,
                name: ip.instrument_ident.toUpperCase(),
                parameters: {
                  Parameter: [{
                    ident: ip.param_ident.toUpperCase(),
                    name: ip.param_ident.toUpperCase(),
                    type: "float"
                  }]
                }
              }
              instruments.push(i);
              
            });
            
            BloombergExtractor.getTradeValues(instruments,function(err,values){
              
              if (err && Utils.isObject(err)) {
                log.error(err);
                ret(null,null,null);
              } else ret(err,instrumentParams,values);
            });
            
          } else ret('нет параметров для обновления',null);
        },
        
        function prepareInstumentParams(instrumentParams,values,ret) {
          
          if (Utils.isArray(instrumentParams) && Utils.isArray(values) && values.length>0) {
            
            Utils.forEach(instrumentParams,function(ip){
            
              Utils.filter(values,function(v){
                
                return v.instrumentId == ip.tempId;
                
              }).forEach(function(v){
                
                if (v.value) {
                  var s = (v.value.$value)?v.value.$value:null;
                  var value = (s)?parseFloat(s):v.value;

                  if (value) {
                    ip.value = value;
                  }
                }
                
              });
            });
            
            ret(null,instrumentParams);
            
          } else ret('нет данных от блумберга',null);
        },
        
        function setInstrumentValues(instrumentParams,ret) {
          
          if (Utils.isArray(instrumentParams)) {
            
            var created = moment().toDate();
            var to_date = moment().add({days:-1}).startOf('day').hours(23).minutes(45).toDate();
            
            async.eachSeries(instrumentParams,function(ip,callback){
              
              if (ip && ip.value) {
                
                var ip1 = {
                  param_id: ip.param_id,
                  instrument_id: ip.instrument_id,
                  created: created,
                  to_date: to_date,
                  value_number: ip.value
                }
                
                WwwInstrumentValues.create(ip1,function(err){
                  
                  callback(err);
                });
                
              } else callback(null);
              
            },function(err){
              ret(err);
            });
            
          } else ret(null);  
        }
        
      ],function(err) {
        
        if (err) {
          self.sendError(options,err,function(){
            result(err);
          });
        } else result(null);
      });
      
    } catch(e) {
      result(e.message);
    }   
  }
  
  
}
