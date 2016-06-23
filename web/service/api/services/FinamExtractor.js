// FinamService

var util = require('util'),
    request = require('request'),
    Buffer = require('buffer').Buffer,
    Iconv  = require('iconv').Iconv;
    
var windows1251ToUTF8 = new Iconv('CP1251','UTF-8');

var Sections = {
  
  MICEX: 1,          // ММВБ Акции
  MICEX_PIF: 29,     // ММВБ ПИФы
  MICEX_OBL: 2,      // ММВБ облигации
  MICEX_OBL_V: 12,   // ММВБ Внесписочные облигации
  RTS: 3,            // РТС
  RTS_GAZ: 10,       // РТС-GAZ
  RTS_GTS: 11,       // РТС-GTS
  RTS_BOARD: 20,     // RTS Board
  RTS_Standard: 38,  // RTS Standard
  FUTURES_FORTS: 14, // Фьючерсы ФОРТС
  CURR: 5,           // Валюты
  INDEX: 6,          // Индексы
  FUTURES: 7,        // Фьючерсы
  ADR: 8,            // АДР
  MATERIAL: 24,      // Сырье
  SPFR: 9,           // СПФБ
  ARC_FORTS: 17,     // ФОРТС Архив
  ARC_MICEX: 16,     // ММВБ Архив
  ARC_RTS: 18,       // РТС Архив
  USA_STOCK: 25,     // Акции США
  US_BOND: 26,       // US Bonds/Notes
  USA_INDUSTRY: 27,  // Отрасли экономики США
  ETF: 28,           // ETF
  INDEX_IND: 30      // Индексы мировой экономики
}

var TimeFrames = {
  
  TIC: 1,
	MIN1: 2,
	MIN5: 3,
	MIN10: 4,
	MIN15: 5,
	MIN30: 6,
	MIN60: 11,
	HOUR: 7,
	DAY: 8,
	WEEK: 9,
	MONTH: 10
}

var DateFormats = {
  YYYYMMDD: 1 // 20151201
}

var TimeFormats = {
  'HH:MM:SS': 3 // 10:59:30
}

function makeValue(instrument,parameter,value,obtained) {
  
  var v = {
    instrumentId: instrument.id,
    parameterIdent: parameter.ident,
    obtained: (obtained)?obtained:new Date(),
    value: null,
    type: parameter.type
  }
  
  try {
    switch(v.type) {
      case 'float': v.value = parseFloat(value); break;
      case 'integer': v.value = parseInt(value); break;
      case 'string': v.value = value.toString(); break;
      case 'datetime': v.value = moment(value).toDate(); break;
      default: throw new Exception('Unknown type');  
    }
  } catch(e) {
    v.error = e.message;
  }  
  return v;
}

function makeHeaders() {
 
  var headers = {
    'Referer': 'http://www.finam.ru/analysis/export/default.asp',
    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36',
    'Accept-Language': 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'Connection': 'keep-alive'
  }
  
  return headers;
}

function getHistoryValues(instrument,result) {
  
  try {
    
    async.waterfall([
      
      function getData(next){
        
        var stamp = moment();
        var previous = moment().add({days:-1});
        
        var params = {
          id: instrument.nameId,
          name: instrument.name,
          section: Sections.MICEX,
          timeFrame: TimeFrames.DAY,
          day1: previous.format('D'),
          month1: previous.month(),
          year1: previous.format('YYYY'),
          day2: stamp.format('D'),
          month2: stamp.month(),
          year2: stamp.format('YYYY'),
          dateFormat: DateFormats.YYYYMMDD,
          timeFormat: TimeFormats['HH:MM:SS']
        }
        
        var options = {
          url: Utils.format(sails.config.finamExtractor.historyUrl,params),
          headers: makeHeaders()
        }
        
        request(options,function(err,response,data){

          if (err) next(err,null);
          else {
            data = new Buffer(data,'binary');
            data = windows1251ToUTF8.convert(data).toString();
            next(err,data);
          }
        });
        
      },
      
      //<DATE>,<TIME>,<OPEN>,<HIGH>,<LOW>,<CLOSE>,<VOL>
      //20150320,00:00:00,137.1000000,138.5900000,134.5100000,137.8800000,35849000
      function parseData(data,next) {
        
        console.log(data);
        var vls = [];
        if (data) {
          
          var obj = {
            CLOSE: null,
            OPEN: null,
            LAST: null,
            TICKER: instrument.name
          }
         
          var arr = data.trim().split('\n');
          if (arr.length>0) {
            arr.splice(0,1);
          }

          var index = 0;
          Utils.forEach(arr,function(rowString) {

            var row = rowString.split(',');
            for (var colNum = 0; colNum < 6; colNum++) {

              var r = row[colNum];
              if (r) {
                
                switch (colNum) {
                  case 2: obj.OPEN = parseFloat(r); break;
                  case 5: {
                    if (index==0) 
                      obj.CLOSE = parseFloat(r);
                    else
                      obj.LAST = parseFloat(r);
                    break;  
                  }
                }
              }   
            }
            index++;
          });
          
          Utils.forEach(instrument.parameters,function(parameter){
            
            var v = obj[parameter.name];
            if (v!==undefined) {
              vls.push(makeValue(instrument,parameter,v));
            }
          });
        }
        next(null,vls);
      }
      
    ],function(err,values){
      result(err,values);
    });
    
  } catch(e) {
    result(e.message);
  }
}

function getTicValues(instrument,values,result) {
  
  try {
    
    async.concatSeries(instrument.parameters,function(parameter,nextConcat){
    
      if (parameter.ident==='TIC') {
        
        async.waterfall([
      
          function getData(next){
            
            var stamp = moment();

            var params = {
              id: instrument.nameId,
              name: instrument.name,
              section: Sections.MICEX,
              timeFrame: TimeFrames.TIC,
              day1: stamp.format('D'),
              month1: stamp.month(),
              year1: stamp.format('YYYY'),
              day2: stamp.format('D'),
              month2: stamp.month(),
              year2: stamp.format('YYYY'),
              dateFormat: DateFormats.YYYYMMDD,
              timeFormat: TimeFormats['HH:MM:SS']
            }

            var options = {
              url: Utils.format(sails.config.finamExtractor.ticUrl,params),
              headers: makeHeaders()
            }

            request(options,function(err,response,data){

              if (err) next(err,null);
              else {
                data = new Buffer(data,'binary');
                data = windows1251ToUTF8.convert(data).toString();
                next(err,data);
              }
            });
            
          },
          
          //<DATE>,<TIME>,<LAST>,<VOL>
          //20150323,09:59:59,241.850000000,200
          //20150323,09:59:59,241.850000000,10
          function parseData(data,next) {
            
            var vls = [];
            if (data) {
          
              var arr = data.trim().split('\n');
              if (arr.length>0) {
                arr.splice(0,1);
              }
              
              if (arr.length>0) {
                
                var row = arr[arr.length-1].split(',');
                if (row && row.length>=2) {
                  
                  var v = parseFloat(row[2]);
                  vls.push(makeValue(instrument,parameter,v));
                }
              }
            }
            next(null,vls);
          }
          
        ],function(err,values){
          nextConcat(err,values);
        });
        
      } else nextConcat(null,[]);
      
    },function(err,results){
      results = values.concat(results);
      result(err,results);
    });
    
  } catch(e) {
    result(e.message);
  }
}

module.exports = {

  getTradeValues: function(instruments,result) {
    
    try {
      
      if (instruments && instruments.length>0) {

        async.concatSeries(instruments,function(instrument,nextConcat){
          
          async.waterfall([
            
            function(next) {
              
              getHistoryValues(instrument,function(err,values){
                next(err,instrument,values);
              });
            },
            
            function(instrument,values,next) {
              
              /*getTickValues(instrument,values,function(err,values){
                next(err,values);
              });*/
              next(null,values);
            }
            
          ],function(err,values) {
            nextConcat(err,values);
          });

        },function(err,results) {
          result(err,results);
        });
        
      } else 
        result();
      
    } catch(e) {
      result(e.message);
    }  
  }
}
