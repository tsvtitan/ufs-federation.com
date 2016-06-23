// AcsService

var Firebird = require('node-firebird');

module.exports = {

  connect: function(result) {
    
    try {
      
      Firebird.attach(sails.config.acs.db,function(err,db){
        
        result(err,db);
      });
      
    } catch(e) {
      result(e.message);
    }  
  },
  
  disconnect: function(db,result) {
    
    if (db) {
      db.detach(result);
    } else result();   
  },
  
  checkIn: function(staffId,dateTime,result) {
    
    var self = this;
    
    async.waterfall([
      
      function(ret) {
        self.connect(ret);
      },

      function(db,ret) {

        if (db) {
          
          var query = 'INSERT INTO REG_EVENTS ({fields}) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
          
          var fields = ['INNER_NUMBER_EV','DATE_EV','TIME_EV','IDENTIFIER',
                        'CONFIGS_TREE_ID_CONTROLLER','CONFIGS_TREE_ID_RESOURCE','TYPE_PASS',
                        'CATEGORY_EV','SUBCATEGORY_EV','AREAS_ID','STAFF_ID','USER_ID',
                        'TYPE_IDENTIFIER','LAST_TIMESTAMP','IDENTIFIER_OWNER_TYPE','AVT_CAM_DBID','SUBDIV_ID'];
                      
          var d = moment(dateTime).format('DD.MM.YYYY');
          var t = moment(dateTime).format('HH:mm:ss');
          var dt = moment(dateTime).format('DD.MM.YYYY HH:mm:ss')
          
          var params = [31,d.toString(),t.toString(),396520,
                        5911,5977,1,
                        0,0,6215,parseInt(staffId),null,
                        0,dt,0,-1,7968];            
                      
          var q = Utils.format(query,{fields:fields.join(',')});
          
          db.execute(q,params,function(err1){
              
            self.disconnect(db);
            if (err1) ret(err1);
            else {
              ret();
            }
          });
          
        } else ret();
      }

    ],function(err) {
      result(err);
    });
    
  },
  
  checkOut: function(staffId,dateTime,result) {
    
    var self = this;
    
    async.waterfall([
      
      function(ret) {
        self.connect(ret);
      },

      function(db,ret) {

        if (db) {
          
          var query = 'INSERT INTO REG_EVENTS ({fields}) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
          
          var fields = ['INNER_NUMBER_EV','DATE_EV','TIME_EV','IDENTIFIER',
                        'CONFIGS_TREE_ID_CONTROLLER','CONFIGS_TREE_ID_RESOURCE','TYPE_PASS',
                        'CATEGORY_EV','SUBCATEGORY_EV','AREAS_ID','STAFF_ID','USER_ID',
                        'TYPE_IDENTIFIER','LAST_TIMESTAMP','IDENTIFIER_OWNER_TYPE','AVT_CAM_DBID','SUBDIV_ID'];
                      
          var d = moment(dateTime).format('DD.MM.YYYY');
          var t = moment(dateTime).format('HH:mm:ss');
          var dt = moment(dateTime).format('DD.MM.YYYY HH:mm:ss')
          
          var params = [17,d.toString(),t.toString(),396520,
                        5512,5687,1,
                        0,0,1,parseInt(staffId),null,
                        0,dt,0,-1,7968];            
                      
          var q = Utils.format(query,{fields:fields.join(',')});
          
          db.execute(q,params,function(err1){
              
            self.disconnect(db);
            if (err1) ret(err1);
            else {
              ret();
            }
          });
          
        } else ret();
      }

    ],function(err) {
      result(err);
    });
    
  },  
  
  getTable: function(db,query,fields,params,result) {
    
    if (db) {
      
      var self = this;
      var q = Utils.format(query,{fields:fields.join(',')});
      
      db.query(q,params,function(err,table){
              
        self.disconnect(db);
        if (err) result(err);
        else {
          
          // upper-case fields
          for (var i=0; i<table.length; i++) {
            var r = table[i];
            for (var k in r) {
              var upper = k.toUpperCase();
              r[upper] = r[k];
              delete r[k];
            }
          }
          
          result(err,table);
        }
      });
      
    } else result();
  },
  
  getReport: function(options,getTable,result) {

    var self = this;
    var log = this.log;
    try {
      
      async.waterfall([
      
        function(result) {
          self.connect(result);
        },
        
        function(db,result) {
          
          if (db) {
            
            getTable(db,options.query,options.fields,options.params,function(err,table){
              result(err,table);
            });
            
          } else result(null,null);
        },
        
        function getHtml(table,result) {
          
          if (table && table.length>0) {
           
            var transforms = (options.transforms)?options.transforms:false;
            
            if (transforms) {
              for (var i=0; i<table.length; i++) {
                var r = table[i];
                for (var j=0; j<options.fields.length; j++) {
                  var field = options.fields[j];
                  r[field] = (transforms && transforms[j])?transforms[j](r[field]):r[field];
                }
              }
            }
            
            var locals = {
              title: options.title,
              fields: options.fields,
              columns: options.columns,
              styles: options.styles,
              table: table
            }
            
            sails.renderView(options.view,locals,function(err,html){
              
              var headers = [];
              var attachments = [];
              
              if (html && options.attachment) {
                
                var id = 0;
                var buf = new Buffer(html);
                
                var a = {
                  id: id.toString(),
                  name: locals.title,
                  extension: 'html',
                  contentType: 'text/html',
                  data: buf.toString('base64'),
                  size: buf.length
                }
                attachments.push(a);
              }
              
              result(err,locals.title,html,options.recipients,headers,attachments);
            });
            
          } else result(null,null,null,null,null,null);
        },
        
        function trySend(subject,body,recipients,headers,attachments,result) {
          
          if (body || (attachments && attachments.length>0)) {
            
            var delay = (options.delay)?options.delay:0;
            var duration = (options.duration)?options.duration:30;
            
            var begin = moment().add({seconds:delay});
            var end = moment().add({seconds:delay}).add({minutes:duration});
              
            var mailing = {
              created: new Date(),
              creator: options.creator,
              subject: subject,
              body: (options.body)?new Buffer(body).toString('base64'):null,
              begin: begin.toDate(),
              end: end.toDate(),
              sender: sails.config.acs.sender,
              recipients: recipients,
              headers: headers,
              attachments: attachments,
              keywords: [],
              pattern: sails.config.acs.pattern,
              channel: sails.config.acs.channel,
              test: options.test
            }
            
            MessageGate.sendMailing(mailing,function(err,message){

              if (err) result(err,null);
              else {
                mailing.messageId = message.messageId;
                mailing.allCount = message.queueLength;
                result(null,mailing);
              }
            });
            
          } else result(null,null);
        },
        
        function createMailing(mailing,result) {
          
          if (mailing) {
            
            Mailings.create(mailing,function(err,m){

              Mailings.eventCreate(m);
              result(err,m);
            });
            
          } else result(null);  
        }
        
      ],function(err,m) {
        result(err,m);
      });
      
    } catch(e) {
      result(e.message);
    }  
  },
  
  getSimpleReport: function(options,result) {
    
    this.getReport(options,this.getTable,result);
  },
  
  getAbsenceDays: function(options,date,result) {
    
    var local = {
      view: 'acs/simpleReport',
      query: 'SELECT {fields} FROM UFS_GET_ABSENCE_DAYS(?) WHERE ABSENCE_DAYS>0 UNION ALL '+
             'SELECT {fields} FROM UFS_GET_WORK_DAY_HOURS(?) WHERE OUTSIDE_TIME>DATEADD(HOUR,4,CAST(? AS TIME)) '+
             'ORDER BY 1',
      fields: ['FIO'],
      params: [date,date,"'00:00:00'"],
      test: true,
      body: true,
      attachment:true
    }
    
    this.getSimpleReport(Utils.extend(local,options),result);
  },
  
  getWorkDayHours: function(options,date,result) {
    
    var local = {
      view: 'acs/simpleReport',
      query: 'SELECT {fields} FROM UFS_GET_WORK_DAY_HOURS(?) ORDER BY 1',
      fields: ['FIO','INSIDE_TIME','OUTSIDE_TIME','FIRST_DATETIME','LAST_DATETIME'],
      params: [date],
      styles: [false,'text-align:center','text-align:center','text-align:center','text-align:center'],
      transforms: [
        false,
        function (v) { return moment(v).format('HH:mm'); },
        function (v) { return moment(v).format('HH:mm'); },
        function (v) { return moment(v).format('DD.MM.YYYY HH:mm:ss'); },
        function (v) { return moment(v).format('DD.MM.YYYY HH:mm:ss'); }
      ],
      test: true,
      body: true,
      attachment:true
    }
    
    this.getSimpleReport(Utils.extend(local,options),result);
  },
  
  getWorkDayBegin: function(options,date,result) {
    
    var local = {
      view: 'acs/simpleReport',
      query: 'SELECT {fields} FROM UFS_GET_WORK_DAY_HOURS(?) ORDER BY 1',
      fields: ['FIO','FIRST_DATETIME'],
      params: [date],
      styles: [false,'text-align:center'],
      transforms: [
        false,
        function (v) { return moment(v).format('HH:mm:ss'); }
      ],
      test: true,
      body: true,
      attachment:true
    }
    
    this.getSimpleReport(Utils.extend(local,options),result);
  },
  
  getWorkWeekHours: function(options,dateFrom,dateTo,result) {
    
    var local = {
      view: 'acs/simpleReport',
      query: 'SELECT {fields} FROM UFS_GET_WORK_WEEK_HOURS(?) ORDER BY 1',
      fields: ['FIO','FIRST_DATE','LAST_DATE','INSIDE_HOURS','OUTSIDE_HOURS'],
      params: [dateTo],
      styles: [false,'text-align:center','text-align:center','text-align:right','text-align:right'],
      transforms: [
        false,
        function (v) { return moment(v).format('DD.MM.YYYY'); },
        function (v) { return moment(v).format('DD.MM.YYYY'); }
      ],
      test: true,
      body: true,
      attachment:true
    }
    
    this.getSimpleReport(Utils.extend(local,options),result);
  },
  
  getWorkMonthHours: function(options,dateFrom,dateTo,result) {
    
    var local = {
      view: 'acs/workMonthHours',
      query: "SELECT {fields}, CAST('00:00:00' AS TIME) AS ZERO_TIME FROM UFS_GET_WORK_MONTH_HOURS(?,?) ORDER BY 2,3",
      fields: ['ID','FIO','DAY_DATE','FIRST_DATETIME','LAST_DATETIME','INSIDE_TIME','OUTSIDE_TIME'],
      params: [dateFrom,dateTo],
      test: true,
      body: true,
      attachment:true
    }
    
    var self = this;
    
    function getLocalTable(db,query,fields,params,result) {
      
      self.getTable(db,query,fields,params,function(err,table){
        
        if (table) {
          
          var row = {}
          var temp = [];
          var oldID = false;
          var lastDateTime = false;
          var lastKey = false;
          var minDate = moment(dateFrom,'DD.MM.YYYY');
          var days = moment(dateTo,'DD.MM.YYYY').diff(minDate,'days');
          var weekDays = ['воск-ние','пон-ник','вторник','среда','четверг','пятница','суббота'];
          
          for (var i=0; i<table.length; i++) {
            
            var r = table[i];
            
            if (oldID && oldID!==r.ID) {
              temp.push(row);
              row = {} 
              lastDateTime = false;
              lastKey = false;
            }
            
            if (!row.FIO) row.FIO = r.FIO;
            
            if (!row.SECONDS) row.SECONDS = 0;
            row.SECONDS = row.SECONDS + moment(r.INSIDE_TIME).diff(moment(r.ZERO_TIME),'seconds');
            
            var dayDate = moment(r.DAY_DATE);
            var key = dayDate.format('DD.MM.YY');
            if (lastDateTime) {
              var outExists = moment(r.FIRST_DATETIME).diff(moment(lastDateTime),'seconds')>0;
              if (!outExists && lastKey) {
                row[lastKey].OUT = false;
              }
            }
            
            var dtOut = moment(r.LAST_DATETIME);
            var rowOUT = dtOut.format('HH:mm:ss')!=='23:59:59';
                    
            row[key] = {
              IN: moment(r.FIRST_DATETIME).format('HH:mm'),
              OUT: (rowOUT)?dtOut.format('HH:mm'):false
            }
            
            oldID = r.ID;
            lastDateTime = r.LAST_DATETIME;
            lastKey = key;
          }
          
          if (oldID) temp.push(row);
          
          for (var i in temp) {
            
            var row = temp[i];
            
            row.HOURS = Math.trunc(row.SECONDS/(60*60));
            row.DATES = [];
            
            minDate = moment(dateFrom,'DD.MM.YYYY');
            
            for (var d=0; d<=days; d++) {
              
              var dayDate = minDate;
              var key = dayDate.format('DD.MM.YY');
              var rowKey = row[key];  
              
              var obj = {
                DATE: key,
                DAY: weekDays[dayDate.day()]
              }
              if (rowKey) obj.IN = rowKey.IN;
              if (rowKey) obj.OUT = rowKey.OUT;
              
              row.DATES.push(obj);
              
              if (rowKey) {
                delete row[key];
              }
              
              dayDate = dayDate.add({days:1});
            }
          }
          
          result(err,temp);
          
        } else result(err);
      });
    }
    
    this.getReport(Utils.extend(local,options),getLocalTable,result);
  }
  
}