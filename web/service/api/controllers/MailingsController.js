// MailingsController

var fs = require('fs'),
    contentDisposition = require('content-disposition');

function getTimeout(req) {
  
  if (req.session && req.session.userId) {
    return 500;
  } else return 0;
}

module.exports = {
	
  index: function(req,res) {
    
    res.jsonSuccess({});
  },
  
  new: function(req,res) {
  
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('New error');
    }
    
    function userNotFound(){
      return error('User is not found');
    }
    
    function csvToObject(csv) {
      
      var obj = [];
      var arr = csv.split('\n');

      arr.forEach(function(rowString) {

        var row = rowString.split(',');
        var len = 0;
        for ( var colNum = 0; colNum < 2; colNum++) {
          
          var r = row[colNum];
          if (r) {
            var colData = r.replace(/^['"]|['"]$/g, "");
            if (colNum==0) {
              len = obj.push({contact:colData.trim()});
            } else {
              obj[len-1].name = colData.trim();
            }
          }   
        }
      });
      return obj;
    }
    
    function extractRecipients(path,result) {
      
      fs.readFile(path,function(err,data) {
        
        if (err) result(err,null);
        else if (data) {
          try {
            var obj = csvToObject(data.toString());
            fs.unlinkSync(path);
            result(null,obj);
          } catch(e) {
            result(e.message,null);
          }
        } else result(null,null);
      });
    }
    
    function getRecipients(files,result) {
      
      var recipients = [];
      try {
        
        if (req.body.emails) {
          var emails = JSON.parse(req.body.emails);
          if (emails) {
            for (var e in emails) {
              recipients.push({contact:e,name:emails[e]});
            }
          }
        }
        
        async.waterfall([
        
          function tryMenu(ret) {
            
            var rcpts = [];
            
            if (req.body.menu) {
              switch (req.body.menu) {
                case 'subscription': {
                    
                  DataService.subscriptionRecipients('ru',function(err,r){
                    
                    if (r) {
                      rcpts = rcpts.concat(r);
                      ret(err,rcpts);
                    } else ret(err,rcpts);
                  });  
                    
                  break;
                }
                default: {
                    
                  Mailings.findOneById(req.body.menu,function(err,m){
                    
                    if (m) {
                      rcpts = rcpts.concat(m.recipients);
                      ret(err,rcpts);
                    } else ret(err,rcpts);
                  });
                }
              }
            } else ret(null,rcpts);
          },
          
          function tryFiles(rcpts,ret) {
            
            var types = ['text/csv','text/plain'];
            var arr = [];

            for (var i in files) {
              var f = files[i];
              if (types.indexOf(f.type)!==-1 && f.field==='recipients') {
                arr.push(f.fd);
              }
            }
            
            async.map(arr,extractRecipients,function(err,results) {

              if (!err) {
                for (var i in results) {
                  rcpts = rcpts.concat(results[i]);
                }
              }
              ret(err,rcpts);
            });
          }
          
        ],function(err,rcpts){
          recipients = recipients.concat(rcpts);
          if (!err && recipients && recipients.length==0) {
            err = res.dic('Recipients are not found');
          }
          result(err,recipients);
        });
        
      } catch(e) {
        result(e.message,recipients);
      }
    }
    
    function loadAttachments(file,result) {
      
      fs.readFile(file.path,function(err,data) {
        
        if (err) result(err,null);
        else if (data) {
          try {
            var obj = {data:data.toString('base64'),size:data.length};
            fs.unlinkSync(file.path);
            result(null,obj);
          } catch(e) {
            result(e.message,null);
          }
        } else result(null,null);
      });
    }
    
    function getAttachments(files,result) {
      
      var attachments = [];
      try {
        
        var id = 0;
        for (var i in files) {
          var f = files[i];
          if (f.field==='attachments') {
            var name = f.filename.substr(0,f.filename.lastIndexOf('.')) || f.filename;
            var extension = f.filename.substr((~-f.filename.lastIndexOf('.') >>> 0) + 2);
            attachments.push({id:id.toString(),name:name,extension:extension,contentType:f.type,path:f.fd});
            id++;
          }
        }

        async.map(attachments,loadAttachments,function(err,results) {

          if (!err) {
            for (var i in results) {
              attachments[i].data = results[i].data;
              attachments[i].size = results[i].size;
            }
          }
          result(err,attachments);
        });
            
      } catch(e) {
        result(e.message,attachments);
      }
    }
    
    try {
      if (req.session && req.session.userId) { 

        if (!req.body) error('Body of the request is empty');
        else if (!req.body.body) error('Body of the mailing is empty');
        else {

          async.waterfall([

            function getFiles(result) {

              async.map(['recipients','attachments'],function(name,cb){

                req.file(name).upload(function(err,files){
                  cb(err,files);
                });

              },function(err,results){

                var files = [];
                if (!err) {
                  for (var i in results) {
                    files = files.concat(results[i]);
                  }
                }
                result(err,files);
              });            
            },

            function findRecipients(files,result) {

              getRecipients(files,function(err,recipients) {
                result(err,recipients,files);
              });
            },

            function findAttachments(recipients,files,result) {

              getAttachments(files,function(err,attachments) {
                result(err,recipients,attachments);
              })
            },

            function findUser(recipients,attachments,result) {

              Users.findOneById(req.session.userId,function(err,user){
                if (err) result(err,null,null,null);
                else if (!user) userNotFound();
                else result(null,recipients,attachments,user);
              });
            },

            function trySend(recipients,attachments,user,result) {

              var name = req.body.name;
              var contact = (req.body.contact)?req.body.contact:user.email;
              
              var headers = [];
              if (req.body.replyTo) {
                headers.push({name:'Reply-To',value:req.body.replyTo});
              }
              if (req.body.headers) {
                var rawHeaders = req.body.headers.split('\n');
                if (rawHeaders) {
                  for (var i in rawHeaders) {
                    var h = rawHeaders[i].split(':');
                    if (h.length===2) {
                      headers.push({name:h[0].trim(),value:h[1].trim()});
                    }
                  }
                }
              }

              var keywords = [];
              if (req.body.keywords) {
                keywords = req.body.keywords.split(',');
                for (var i in keywords) {
                  keywords[i] = keywords[i].trim();
                }
              }

              var delay = Utils.isNumber(req.body.delay)?req.body.delay:300;
              delay = Utils.isString(req.body.delay)?parseInt(req.body.delay):delay;
              delay = (req.body.test)?0:delay;
              
              var duration = Utils.isNumber(req.body.duration)?req.body.duration:60;
              duration = Utils.isString(req.body.duration)?parseInt(req.body.duration):duration;

              var begin = moment().add({seconds:delay});
              var end = moment().add({seconds:delay}).add({minutes:duration});

              var mailing = {
                created: new Date(),
                creator: user.login,
                subject: req.body.subject,
                body: (req.body.body) ? new Buffer(req.body.body).toString('base64') : null,
                begin: begin.toDate(),
                end: end.toDate(),
                sender: {name:name,contact:contact},
                recipients: recipients,
                headers: headers,
                keywords: keywords,
                attachments: attachments,
                pattern: req.body.pattern,
                channel: req.body.channel,
                test: (req.body.test)?true:false
              }
              
              MessageGate.sendMailing(mailing,function onSendMailing(err,message){

                if (err) result(err,null);
                else {
                  mailing.messageId = message.messageId;
                  mailing.allCount = message.queueLength;
                  result(null,mailing);
                }
              });
            },

            function createMailing(mailing,result) {

              Mailings.create(mailing,function(err,m){
                
                Mailings.eventCreate(m);
                result(err,m);
              });
            }

          ],function(err,mailing) {
            
            if (err) error(err);
            else if (!mailing) error('Mailing is not found');
            else {
              setTimeout(function() {
                res.jsonSuccess({id:mailing.id,count:mailing.allCount});
              },getTimeout(req));
            }
          });
        }

      } else userNotFound(); 
      
    } catch(e) {
      error(e.message);
    }
  },
  
  test: function(req,res) {
    
    if (req.body && !req.body.test) {
      req.body.test = true;
    }
    this.new(req,res);
  },
  
  lists: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Lists error');
    }
    
    function userNotFound(){
      return error('User is not found');
    }
    
    try {
      if (req.session && req.session.userId) {
      
        async.waterfall([

          function findUser(result) {

            Users.findOneById(req.session.userId,function(err,user){
              if (err) result(err,null);
              else if (!user) userNotFound();
              else result(null,user);
            });
          },
          
          function getAccess(user,result){
            
            Permissions.getAccess(user,'mailings','show',function(err,access){
              result(err,access);
            });
          },

          function findMailings(access,result) {

            var where = {
              allCount: {'>':0},
              test: false
            }
            
            where = Utils.extend(where,access);
            
            Mailings.find({where:where,sort:{created:-1},limit:8},
                          {fields:{subject:1,recipients:1}},
                          function(err,mailings){
              result(err,mailings);
            });
          },

          function getSubscription(mailings,result) {

            DataService.subscriptionCount('ru',function onSubscriptionCount(err,count){
              if (err) log.warn(err);
              result(null,mailings,count);
            });
          },

          function buildMenu(mailings,count,result) {

            var menu = [];
            if (count && count>0) {
              var n = res.dic('{count} from Subscription',{count:count});
              menu.push({name:n,menu:'subscription'});
            }
            if (mailings) {
              for (var i in mailings) {
                var m = mailings[i];
                var s = (m.subject)?m.subject:res.dic('Empty subject');
                var n = res.dic('{count} from {subject}',{count:m.recipientCount(),subject:s});
                menu.push({name:n,menu:m.id}); 
              }
            }
            result(null,menu);
          }

        ],function(err,menu) {
          
          if (err) error(err);
          else if (!menu) error('Menu is not found');
          else res.jsonSuccess({menu:(menu)?menu:false});
        });

      } else userNotFound(); 
      
    } catch(e) {
      error(e.message);
    }
  },
  
  get: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Get error');
    }
    
    function userNotFound(){
      return error('User is not found');
    }
    
    try {
      
      if (req.session && req.session.userId) {
        
        async.waterfall([
          
          function findUser(result) {

            Users.findOneById(req.session.userId,function(err,user){
              if (err) result(err,null);
              else if (!user) userNotFound();
              else result(null,user);
            });
          },
          
          function getAccess(user,result){
            
            Permissions.getAccess(user,'mailings','show',function(err,access){
              result(err,access);
            });
          },
          
          function findCount(access,result){
            
            var where = {
              allCount:{'>':0},
              test:false
            }
            
            where = Utils.extend(where,access);
            
            Mailings.count(where,function(err,count){
              result(err,where,count);
            });
          },

          function findMailings(where,count,result) {
            
            var limit = (req.body && Utils.isNumber(req.body.limit))?req.body.limit:10;
            limit = (limit>0)?limit:10;
            
            Mailings.find({where:where,sort:{created:-1},limit:limit},
                          {fields:Mailings.fieldsForShow},
                          function(err,mailings){
                            
              result(err,count,mailings);
            });
          },
          
          function prepareMailings(count,mailings,result) {
            
            
            if (mailings) {
              
              var arr = [];
              
              for (var i in mailings) {
                arr.push(Mailings.prepareForShow(mailings[i]));
              }
              
              result(null,count,arr);
              
            } else result(null,count,null);
          }
        
        ],function(err,count,mailings){
          
          if (err) error(err);
          else if (!mailings) error('Mailings are not found');
          else res.jsonSuccess({mailings:(mailings)?mailings:false,count:count});
        });
        
      } else userNotFound(); 
      
    } catch(e) {
      error(e.message);
    }
  },
  
  cancel: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Cancel error');
    }
    
    try {
      
      async.waterfall([

        function tryAuth(result) {

          if (req.body && req.body.auth) {
            
            Users.getOrCreateByLogin(req.body.auth.login,req.body.auth.pass,false,function(err,user){
              result(err,user);
            });
            
          } else if (req.session && req.session.userId) {
            
            Users.findOneById(req.session.userId,function(err,user){
              if (err) result(err,null);
              else result(null,user);
            });
            
          } else result(null,null);
        },

        function findMailing(user,result) {

          if (user && req.body && req.body.id) {
            
            Mailings.find({where:{id:req.body.id}},
                          {fields:Mailings.fieldsForShow},
                          function(err,mailings){
              var m = null;
              if (mailings && mailings.length>0) {
                m = mailings[0];
              }          
              result(err,m);
            });
          } else result('Id is not found');
        },

        function cancelMailing(mailing,result) {

          if (mailing) {
            if (!mailing.canceled) {

              MessageGate.cancelMailing(mailing,function onCancelMailing(err,canceled){

                result(err,mailing,canceled);
              });
            } else result(null,mailing,true);
          } else result('Mailing is not found',null,false);
        },

        function updateMailing(mailing,canceled,result) {

          if (mailing && !mailing.canceled && canceled) {

            var c = (canceled)?new Date():null;

            Mailings.update({id:mailing.id},{canceled:c},function(err,updated){

              Mailings.eventUpdate(mailing);
              result(err,(updated)?canceled:false);
            });
            
          } else result(null,canceled);
        }

      ],function(err,canceled){

        if (err) error(err);
        else {
          setTimeout(function(){
            res.jsonSuccess({canceled:canceled});
          },getTimeout(req));
        }
      });
      
    } catch(e) {
      error(e.message);
    }
  },
  
  suspend: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Suspend error');
    }
    
    try {
      
      async.waterfall([

        function tryAuth(result) {

          if (req.body && req.body.auth) {
            
            Users.getOrCreateByLogin(req.body.auth.login,req.body.auth.pass,false,function(err,user){
              result(err,user);
            });
            
          } else if (req.session && req.session.userId) {
            
            Users.findOneById(req.session.userId,function(err,user){
              if (err) result(err,null);
              else result(null,user);
            });
            
          } else result(null,null);
        },

        function findMailing(user,result) {

          if (user && req.body && req.body.id) {
            Mailings.find({where:{id:req.body.id}},
                          {fields:Mailings.fieldsForShow},
                          function(err,mailings){
              var m = null;
              if (mailings && mailings.length>0) {
                m = mailings[0];
              }          
              result(err,m);
            });
          } else result('Id is not found');
        },

        function suspendMailing(mailing,result) {

          if (mailing) {
            if (!mailing.suspended && !mailing.canceled) {

              MessageGate.suspendMailing(mailing,function onSuspendMailing(err,suspended){

                result(err,mailing,suspended);
              });
            } else result(null,mailing,!mailing.canceled);
          } else result('Mailing is not found',null,false);
        },

        function updateMailing(mailing,suspended,result) {

          if (mailing && !mailing.suspended && suspended) {

            var s = (suspended)?new Date():null;

            Mailings.update({id:mailing.id},{suspended:s},function(err,updated){

              var flag = (updated)?suspended:false;
              
              Mailings.eventUpdate(mailing);
              result(err,flag);
            });
            
          } else result(null,suspended);
        }

      ],function(err,suspended){

        if (err) error(err);
        else {
          setTimeout(function(){
            res.jsonSuccess({suspended:suspended});
          },getTimeout(req));
        }
      });
      
    } catch(e) {
      error(e.message);
    }
  },
  
  resume: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Resume error');
    }
    
    try {
      
      async.waterfall([

        function tryAuth(result) {

          if (req.body && req.body.auth) {
            
            Users.getOrCreateByLogin(req.body.auth.login,req.body.auth.pass,false,function(err,user){
              result(err,user);
            });
            
          } else if (req.session && req.session.userId) {
            
            Users.findOneById(req.session.userId,function(err,user){
              if (err) result(err,null);
              else result(null,user);
            });
            
          } else result(null,null);
        },

        function findMailing(user,result) {

          if (user && req.body && req.body.id) {
            
            Mailings.find({where:{id:req.body.id}},
                          {fields:Mailings.fieldsForShow},
                          function(err,mailings){
              var m = null;
              if (mailings && mailings.length>0) {
                m = mailings[0];
              }          
              result(err,m);
            });
          } else result('Id is not found');
        },

        function resumeMailing(mailing,result) {

          if (mailing) {
            if (mailing.suspended && !mailing.canceled) {

              MessageGate.resumeMailing(mailing,function onResumeMailing(err,resumed){

                result(err,mailing,resumed);
              });
            } else result(null,mailing,!mailing.canceled);
          } else result('Mailing is not found',null,false);
        },

        function updateMailing(mailing,resumed,result) {

          if (mailing && mailing.suspended && resumed) {

            Mailings.update({id:mailing.id},{suspended:null},function(err,updated){

              var flag = (updated)?resumed:false;
              
              Mailings.eventUpdate(mailing);
              result(err,flag);
            });
            
          } else result(null,resumed);
        }

      ],function(err,resumed){

        if (err) error(err);
        else {
          setTimeout(function(){
            res.jsonSuccess({suspended:!resumed});
          },getTimeout(req));
        }
      });
      
    } catch(e) {
      error(e.message);
    }
  },
  
  subscribe: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Subscribe error');
    }
    
    function userNotFound(){
      return error('User is not found');
    }
    
    try {
      
      if (req.isSocket) {
        
        if (req.session && req.session.userId) {
        
          async.waterfall([

            function findUser(result) {

              Users.findOneById(req.session.userId,function(err,user){
                if (err) result(err,null);
                else if (!user) userNotFound();
                else result(null,user);
              });
            },

            function getAccess(user,result){

              Permissions.getAccess(user,'mailings','show',function(err,access){
                result(err,access);
              });
            },

            function findMailings(access,result) {

              var limit = (req.body && Utils.isNumber(req.body.limit))?req.body.limit:10;
              limit = (limit>0)?limit:10;
          
              var where = {
                allCount: {'>':0},
                test: false,
                sent: [null,undefined,false],
                canceled: [null,undefined,false]
              }

              where = Utils.extend(where,access);
              
              Mailings.find({where:where,sort:{created:-1},limit:limit},
                            {fields:{id:1}},
                            function(err,mailings){
                result(err,mailings);
              });
            }
            
          ],function(err,mailings) {

            if (err) error(err);
            else {
              Mailings.subscribe(req.socket,mailings);
              Mailings.watch(req.socket);
              res.jsonSuccess();
            }
          });
          
        } else userNotFound();
        
      } else error('Socket is needed');

    } catch(e) {
      error(e.message);
    }
  },
  
  unsubscribe: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Unsubscribe error');
    }
    
    function userNotFound(){
      return error('User is not found');
    }
    
    try {
      
      if (req.isSocket) {
        
        if (req.session && req.session.userId) {
          
          async.waterfall([

            function findUser(result) {

              Users.findOneById(req.session.userId,function(err,user){
                if (err) result(err,null);
                else if (!user) userNotFound();
                else result(null,user);
              });
            },

            function getAccess(user,result){

              Permissions.getAccess(user,'mailings','show',function(err,access){
                result(err,access);
              });
            },

            function findMailings(access,result) {

              var limit = Utils.isNumber(req.body.limit)?req.body.limit:10;
              limit = (limit>0)?limit:10;
          
              var where = {
                allCount: {'>':0},
                test: false
              }

              where = Utils.extend(where,access);
              
              Mailings.find({where:where,sort:{created:-1},limit:limit},
                            {fields:{id:1}},
                            function(err,mailings){
                result(err,mailings);
              });
            }
            
          ],function(err,mailings) {

            if (err) error(err);
            else {
              Mailings.unsubscribe(req.socket,mailings);
              Mailings.unwatch(req.socket);
              res.jsonSuccess();
            }
          });
          
        } else userNotFound();
        
      } else error('Socket is needed');

    } catch(e) {
      error(e.message);
    }
  },
  
  attachment: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.serverError('Attachment error');
    }
    
    try {
      
      var mailingId = req.param('mailingId');
      var attachmentId = req.param('attachmentId');
      
      if (mailingId && attachmentId) {
        
        var where = {
          id: mailingId,
          'attachments.id': attachmentId
        }
        
        Mailings.find({where:where,limit:1},
                      {fields:{attachments:1}},
                      function(err,mailings){
          
          if (err) error(err);
          else {
            
            if (mailings.length>0 && mailings[0] && mailings[0].attachments) {
              
              var atts = Utils.filter(mailings[0].attachments,function(attachment){
                return attachment.id === attachmentId;  
              });
              
              if (atts.length>0 && atts[0] && atts[0].data) {
                
                var a = atts[0];
                
                var contentType = (a.contentType)?a.contentType:'application/octet-stream';
                res.set('Content-Type',contentType);
                
                if (a.name) {
                  var name = a.name+((a.extension)?'.'+a.extension:'');
                  res.set('Content-Disposition',contentDisposition(name,{fallback:false}));
                }
                
                res.send(new Buffer(a.data,'base64'));
                
              } else res.notFound();
            } else res.notFound(); 
          }
          
        });
        
      } else res.notFound();
      
    } catch(e) {
      error(e.message);
    }
  },
  
  recipients: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Recipients error');
    }
    
    try {
      
      if (req.body.id) {
        
        Mailings.find({where:{id:req.body.id}},
                      {fields:{recipients:1}},
                      function(err,mailings){
                        
          if (err) error(err);
          else {
            if (mailings && mailings.length>0) {

              var m = mailings[0];
              var recipients = (m && m.recipients)?m.recipients:[];
              
              setTimeout(function(){
                res.jsonSuccess({recipients:recipients});
              },getTimeout(req));
              
            } else error('Mailing is not found');
          }
        });
        
      } else error('Id is not found');
        
    } catch(e) {
      error(e.message);
    }
  },
  
  body: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Body error');
    }
    
    try {
      
      if (req.body.id) {
        
        Mailings.find({where:{id:req.body.id}},
                      {fields:{body:1}},
                      function(err,mailings){
                        
          if (err) error(err);
          else {
            if (mailings && mailings.length>0) {

              var m = mailings[0];
              var body = (m && m.body)?new Buffer(m.body,'base64').toString():null;
              
              setTimeout(function(){
                res.jsonSuccess({body:body,bodyDecoded:true});
              },getTimeout(req));
              
            } else error('Mailing is not found');
          }
        });
        
      } else error('Id is not found');
        
    } catch(e) {
      error(e.message);
    }
  },
  
  send: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Send error');
    }
    
    try {
      
      async.waterfall([
        
        function tryAuth(result){
          
          
          if (req.body && req.body.auth) {
            
            Users.getOrCreateByLogin(req.body.auth.login,req.body.auth.pass,false,function(err,user){
              result(err,user);
            });
            
          } else if (req.session && req.session.userId) {
            
            Users.findOneById(req.session.userId,function(err,user){
              if (err) result(err,null);
              else result(null,user);
            });
            
          } else result(null,null);
        },
        
        function trySend(user,result){
          
          if (user && req.body && req.body.mailing) {
            
            var input = req.body.mailing;

            var delay = Utils.isNumber(input.delay)?input.delay:300;
            delay = (input.test)?0:delay;
           
            var duration = Utils.isNumber(input.duration)?input.duration:60;

            var begin = moment().add({seconds:delay});
            var end = moment().add({seconds:delay}).add({minutes:duration});
            
            var body = null;
            if (input.bodyEncoding) {
              switch (input.bodyEncoding) {
                case 'base64': body = input.body; break;
              }
            } else body = new Buffer(input.body).toString('base64');
            
            if (input.attachments && Utils.isArray(input.attachments)) {
              
              Utils.forEach(input.attachments,function(attachment){
                
                if (attachment.data) {
                  if (!attachment.size) {
                    
                    var buf = new Buffer(attachment.data);
                    attachment.size = buf.length;
                    attachment.data = buf.toString('base64');
                    
                  }
                }
              });
            }
            
            var priority = Utils.isNumber(input.priority)?input.priority:999;

            var mailing = {
              created: new Date(),
              creator: user.login,
              subject: input.subject,
              body: body,
              begin: begin.toDate(),
              end: end.toDate(),
              sender: input.sender,
              recipients: input.recipients,
              headers: input.headers,
              keywords: input.keywords,
              attachments: input.attachments,
              pattern: input.pattern,
              channel: input.channel,
              priority: priority,
              test: (input.test)?true:false
            }
            
            MessageGate.sendMailing(mailing,function onSendMailing(err,message){

              if (err) result(err,null);
              else {
                mailing.messageId = message.messageId;
                mailing.allCount = message.queueLength;
                result(null,user,mailing);
              }
            });
            
          } else result(null,null);
        },
        
        function tryCreate(user,mailing,result){
          
          if (mailing) {
            
            Mailings.create(mailing,function(err,m){

              Mailings.eventCreate(m);
              result(err,m);
            });
            
          } else result(null,null);  
        }
        
      ],function(err,mailing){
        
        if (err) error(err);
        else if (mailing) {
          res.jsonSuccess({
            mailingId: mailing.id,
            messageId: mailing.messageId,
            allCount: mailing.allCount  
          });
        } else error('Mailing is not found');
      });
      
    } catch(e) {
      error(e.message);
    }
  },
  
  accelerate: function(req,res) {
    
    var log = this.log;
    
    function error(s) {
      log.error(s,null,1);
      res.jsonError('Accelerate error');
    }
    
    try {
      
      async.waterfall([

        function tryAuth(result) {

          if (req.body && req.body.auth) {
            
            Users.getOrCreateByLogin(req.body.auth.login,req.body.auth.pass,false,function(err,user){
              result(err,user);
            });
            
          } else if (req.session && req.session.userId) {
            
            Users.findOneById(req.session.userId,function(err,user){
              if (err) result(err,null);
              else result(null,user);
            });
            
          } else result(null,null);
        },

        function findMailing(user,result) {
          
          if (user && req.body && req.body.id) {
            
            Mailings.find({where:{id:req.body.id}},
                          {fields:Mailings.fieldsForShow},
                          function(err,mailings){
              var m = null;
              if (mailings && mailings.length>0) {
                m = mailings[0];
              }          
              result(err,m);
            });
          } else result('Id is not found');
        },

        function accelerateMailing(mailing,result) {

          if (mailing) {
            if (!mailing.canceled) {

              MessageGate.accelerateMailing(mailing,function onAccelerateMailing(err,accelerated){

                result(err,mailing,accelerated);
              });
            } else result(null,mailing,true);
          } else result('Mailing is not found',null,false);
        },

        function updateMailing(mailing,accelerated,result) {

          if (mailing && accelerated) {
            
            mailing.begin = new Date();
            
            Mailings.update({id:mailing.id},{begin:mailing.begin},function(err,updated){
              
              Mailings.eventUpdate(mailing);
              result(err,(updated)?accelerated:false);
            });
            
          } else result(null,accelerated);
        }

      ],function(err,accelerated){

        if (err) error(err);
        else {
          setTimeout(function(){
            res.jsonSuccess({accelerated:accelerated});
          },getTimeout(req));
        }
      });
      
    } catch(e) {
      error(e.message);
    }
  }
  
  
};
