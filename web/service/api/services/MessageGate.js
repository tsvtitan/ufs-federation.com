// MessageGate

var soap = require('soap');

function prepareArray(arr) {
  
  var ret = arr;
  if (Utils.isArray(arr)) {
    
    ret = Utils.clone(ret);
    
    for (var i in ret) {
      
      var v = ret[i];
      if (Utils.isObject(v)) {
        
        for (var j in v) {
          v[j] = (v[j]===null)?'':v[j];
        }
      }
    }
  }
  return ret;
}

module.exports = {
  
  GATE_DATETIME_FORMAT: 'YYYY-MM-DD HH:mm:ss',
  
  connect: function(result) {
    
    var options = {};
    
    try {
      soap.createClient(sails.config.messageGate.url,options,function(err,client) {
        if (client) {
          client.addSoapHeader('');
        }
        result(err,client);
      });
    } catch(e) {
      result(e.message);
    }  
  },
  
  sendMailing: function(mailing,result) {
    
    var log = this.log;
    try {
      var fmt = this.GATE_DATETIME_FORMAT;
      
      this.connect(function(err,client) {

        if (err) result(err);
        else {
          
          var recipients = prepareArray(mailing.recipients);
          var headers = prepareArray(mailing.headers);
          
          var keywords = [];
          if (mailing.keywords) {
            for (var i in mailing.keywords) {
              keywords.push({keyword:mailing.keywords[i],deliveryType:undefined});
            }
          }
          
          var attachments = prepareArray(mailing.attachments);
          if (attachments) {
            for (var i in attachments) {
              delete attachments[i].path;
            } 
          }
          
          var begin = (mailing.begin)?moment(mailing.begin).format(fmt):undefined;
          
          var end = (mailing.end)?moment(mailing.end).format(fmt):undefined;

          var args = {
            message: {
              channelId: (mailing.channel) ? mailing.channel : sails.config.messageGate.channel,
              senderName: (mailing.sender && mailing.sender.name) ? mailing.sender.name : undefined,
              senderContact: (mailing.sender && mailing.sender.contact) ? mailing.sender.contact : undefined,
              recipients: (recipients) ? recipients : undefined,
              subject: (mailing.subject) ? mailing.subject : undefined,
              body: (mailing.body) ? mailing.body : undefined,
              patterns: [{pattern: (mailing.pattern) ? mailing.pattern : undefined, deliveryType: undefined}],
              headers: (headers) ? headers : undefined,
              keywords: (keywords.length) ? keywords : undefined,
              attachments: (attachments) ? attachments : undefined,
              vars: undefined,
              begin: begin,
              end: end,
              priority: (mailing.priority) ? (mailing.priority) : 999,
              unique: sails.config.messageGate.unique
            }
          }
          
          try {

            client.send(args,function(err,r) {

              if (err) result(err);
              else if (r && r.return) {

                if (r.return.messageId) {

                  result(null,r.return);

                } else result('No message id');
              } else result('No return');
            });
            
          } catch (e) {
            result(e.message);
          }  
        }
      });
    } catch(e) {
      result(e.message);
    }
  },
  
  cancelMailing: function(mailing,result) {
    
    try {
      
      this.connect(function(err,client) {
        
        if (err) result(err);
        else {
          
          var args = {
            messageIds: mailing.messageId
          }
          
          try {
            
            client.cancel(args,function(err,r) {

              if (err) result(err);
              else if (r) {
                result(null,(r.return)?true:false);
             } else result('No return');
            });
            
          } catch(e) {
            result(e.message);
          }
        }
        
      });
    } catch(e) {
      result(e.message);
    }  
  },
  
  accelerateMailing: function(mailing,result) {
    
    try {
      
      this.connect(function(err,client) {
        
        if (err) result(err);
        else {
          
          var args = {
            messageIds: mailing.messageId
          }
          
          try {
            
            client.accelerate(args,function(err,r) {

              if (err) result(err);
              else if (r) {
                result(null,(r.return)?true:false);
             } else result('No return');
            });
            
          } catch(e) {
            result(e.message);
          }
        }
        
      });
    } catch(e) {
      result(e.message);
    }  
  },
  
  suspendMailing: function(mailing,result) {
    
    try {
      
      this.connect(function(err,client) {
        
        if (err) result(err);
        else {
          
          var args = {
            messageIds: mailing.messageId
          }
          
          try {
            
            client.suspend(args,function(err,r) {

              if (err) result(err);
              else if (r) {
                result(null,(r.return)?true:false);
             } else result('No return');
            });
            
          } catch(e) {
            result(e.message);
          }  
        }
        
      });
    } catch(e) {
      result(e.message);
    }  
  },
  
  resume: function(messageIds,result) {
    
    try {
      
      this.connect(function(err,client) {
        
        if (err) result(err);
        else {
          
          var args = {
            messageIds: messageIds
          }
          
          try {
            
            client.resume(args,function(err,r) {

              if (err) result(err);
              else if (r) {
                result(null,(r.return)?true:false);
             } else result('No return');
            });
            
          } catch(e) {
            result(e.message);
          }  
        }
        
      });
    } catch(e) {
      result(e.message);
    }  
  },
  
  resumeMailing: function(mailing,result) {
    this.resume(mailing.messageId,result);
  },
  
  getStatus: function(messageIds,result) {
    
    try {
      
      this.connect(function(err,client) {
        
        if (err) result(err);
        else {
          
          var args = {
            messageIds: messageIds
          }
          
          try {
            
            client.getStatus(args,function(err,r) {

              if (err) result(err);
              else if (r) {
                result(null,r.return);
             } else result('No return');
            });
            
          } catch(e) {
            result(e.message);
          }  
        }
        
      });
    } catch(e) {
      result(e.message);
    }  
  },
  
  getStatusMailings: function(mailings,result) {
    
    if (mailings && mailings.length>0) {
      
      var messageIds = []; 
      
      for (var i in mailings) {
        var m = mailings[i];
        if (m.messageId) {
          messageIds.push(m.messageId);
        }
      }
      
      if (messageIds && messageIds.length>0) {
        
        this.getStatus(messageIds,result);
        
      } else result(null,[]);
    } else result(null,[]);
  }
  
};