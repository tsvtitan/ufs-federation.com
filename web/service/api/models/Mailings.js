// Mailings

var util = require('util');

module.exports = {

  migrate: 'safe',
  autoPK: true,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    
    created: {
      type: 'datetime',
      required: true,
      index: true,
      defaultsTo: function () { return new Date(); }
    },
    creator: {
      type: 'string'
    },
    begin: {
      type: 'datetime'
    },
    end: {
      type: 'datetime'
    },
    sender: {
      type: 'json'
    },
    recipients: {
      type: 'json'
    },
    subject: {
      type: 'string'
    },
    body: {
      type: 'text'
    },
    headers: {
      type: 'json'
    },
    keywords: {
      type: 'json'
    },
    attachments: {
      type: 'json'
    },
    pattern: {
      type: 'string'
    },
    channel: {
      type: 'string'
    },
    priority: {
      type: 'integer'
    },
    messageId: {
      type: 'string'
    },
    allCount: {
      type: 'integer'
    },
    sentCount: {
      type: 'integer'
    },
    deliveredCount: {
      type: 'integer'
    },
    errorCount: {
      type: 'integer'
    },
    test: {
      type: 'boolean'
    },
    canceled: {
      type: 'datetime'
    },
    suspended: {
      type: 'datetime'
    },
    sent: {
      type: 'datetime'
    },
    
    recipientCount: function() {
      return util.isArray(this.recipients)?this.recipients.length:0;
    },
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
    
  },
  
  fieldsForShow: {
    id:1,created:1,creator:1,subject:1,body:1,
    begin:1,end:1,sender:1,keywords:1,headers:1,recipients:1,
    'attachments.id':1,'attachments.name':1,'attachments.extension':1,'attachments.contentType':1,'attachments.size':1,
    allCount:1,sentCount:1,deliveredCount:1,canceled:1,suspended:1,sent:1,
    messageId:1
  },
  
  prepareForShow: function(mailing) {
  
    var m = mailing;
    
    if (m) {
      
      m.recipientCount = m.recipientCount();
      m.recipients = [];
      m.headers = Utils.isArray(m.headers)?m.headers:[];
      m.attachments = Utils.isArray(m.attachments)?m.attachments:[];
      
      if (m.attachments && m.attachments.length>0) {
        for (var j in m.attachments) {
          var a = m.attachments[j];
          delete a.data;
        }
      }
      
      m.keywords = Utils.isArray(m.keywords)?m.keywords:[];
      
      var begin = (m.begin)?m.begin:m.created;
      var end = (m.end)?m.end:m.created;

      // client-side decoding
      //m.body = (m.body)?m.body:null;
      //m.bodyDecoded = false;

      // server-side decoding
      //m.body = (m.body)?new Buffer(m.body,'base64').toString():null;
      //m.bodyDecoded = true;
      
      m.bodyExists = (m.body)?true:false;
      delete m.body;

      m.open = false;
      m.allCount = (m.allCount)?m.allCount:0;
      m.sentCount = (m.sentCount)?m.sentCount:0;
      
      var mBegin = moment(begin);
      var mEnd = moment(end);
      var mCurrent = moment();
      
      var dCanceldOrSuspended = (mCurrent.diff(mEnd)>0)?end:new Date();
      dCanceldOrSuspended = (m.canceled)?m.canceled:dCanceldOrSuspended;
      dCanceldOrSuspended = (m.suspended)?m.suspended:dCanceldOrSuspended;
      dCanceldOrSuspended = (m.sent)?m.sent:dCanceldOrSuspended;
      
      var mCanceldOrSuspended = moment(dCanceldOrSuspended);
      
      m.time = mCanceldOrSuspended.diff(mBegin,'seconds');
      
      m.canceled = (m.canceled)?true:false;
      m.canceled = (!m.canceled)?(mCurrent.diff(mEnd)>0):m.canceled;
      m.suspended = (m.suspended)?true:false;

      m.active = (m.allCount>0 && m.allCount>m.sentCount)?true:false;
      m.active = (mEnd.diff(mCanceldOrSuspended)>0)?m.active:false;   
      m.active = (!m.canceled)?m.active:false;
    }
    
    return m;
  },
  
  eventCreate: function(mailing) {
    
    if (mailing && !mailing.test) {
      Events.emit('events/Mailings.create',{id:mailing.id});
      Events.emit('jobs/Mailings');
    }
  },
  
  eventUpdate: function(mailing) {
    
    if (mailing && !mailing.test) {
      Events.emit('events/Mailings.update',{id:mailing.id});
    }
  }

}