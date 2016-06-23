package ufsic.scheme;

import java.sql.Timestamp;

import java.util.concurrent.ConcurrentHashMap;

import ufsic.providers.Filter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Message extends SchemeRecord {

  final private Attachments attacments = new Attachments();
          
  public Message() {
    super();  
  }

  public Message(Scheme scheme) {
    super(scheme);  
    attacments.setScheme(scheme);
  }
  
  public Message(SchemeTable table, Record record) {
    super(table, record);
    if (isNotNull(table)) {
      attacments.setScheme(table.getScheme());
    }
  }

  public Message(SchemeTable table) {
    this(table, null);
  }
  
  @Override
  public void copyFrom(Record source, boolean newValues) {
    
    super.copyFrom(source);
    if (isNotNull(source) && (source instanceof Message)) {
      
      if (newValues) {
        Message m = (Message)source;
        
        if (m.getMessageId().isNotNull()) setMessageId(new Value(m.getMessageId().asString()));
        if (m.getParentId().isNotNull()) setParentId(new Value(m.getParentId().asString()));
        if (m.getCreated().isNotNull()) setCreated(new Value(m.getCreated().asTimestamp()));
        if (m.getCreatorId().isNotNull()) setCreatorId(new Value(m.getCreatorId().asString()));
        if (m.getSenderId().isNotNull()) setSenderId(new Value(m.getSenderId().asString()));
        if (m.getSenderContact().isNotNull()) setSenderContact(new Value(m.getSenderContact().asString()));
        if (m.getSenderName().isNotNull()) setSenderName(new Value(m.getSenderName().asString()));
        if (m.getRecipientId().isNotNull()) setRecipientId(new Value(m.getRecipientId().asString()));
        if (m.getRecipientContact().isNotNull()) setRecipientContact(new Value(m.getRecipientContact().asString()));
        if (m.getRecipientName().isNotNull()) setRecipientName(new Value(m.getRecipientName().asString()));
        if (m.getSubject().isNotNull()) setSubject(new Value(m.getSubject().asString()));
        if (m.getBody().isNotNull()) setBody(new Value(m.getBody().asString()));
        if (m.getSent().isNotNull()) setSent(new Value(m.getSent().asTimestamp()));
        if (m.getError().isNotNull()) setError(new Value(m.getError().asString()));
        if (m.getBegin().isNotNull()) setBegin(new Value(m.getBegin().asTimestamp()));
        if (m.getEnd().isNotNull()) setEnd(new Value(m.getEnd().asTimestamp()));
        if (m.getPriority().isNotNull()) setPriority(new Value(m.getPriority().asInteger()));
        if (m.getDelivered().isNotNull()) setDelivered(new Value(m.getDelivered().asTimestamp()));
        if (m.getChannelId().isNotNull()) setChannelId(new Value(m.getChannelId().asString()));
        if (m.getLockId().isNotNull()) setLockId(new Value(m.getLockId().asString()));
        if (m.getRemoteId().isNotNull()) setRemoteId(new Value(m.getRemoteId().asString()));
        if (m.getContentType().isNotNull()) setContentType(new Value(m.getContentType().asString()));
        if (m.getPatternId().isNotNull()) setPatternId(new Value(m.getPatternId().asString()));
        if (m.getHeaders().isNotNull()) setHeaders(new Value(m.getHeaders().asString()));
        if (m.getLocked().isNotNull()) setLocked(new Value(m.getLocked().asTimestamp()));
        
      }
    }
  }
  
  @Override
  public void setProvider(Provider provider) {
    
    super.setProvider(provider);
    attacments.setProvider(provider);
  }
  
  public Value getMessageId() {
    
    return getValue(Messages.MessageId);
  }
  
  public void setMessageId(Value messageId) {
    
    if (!setValue(Messages.MessageId,messageId)) {
      add(Messages.MessageId,messageId);
    }
  }

  public Value getParentId() {
    
    return getValue(Messages.ParentId);
  }
  
  public void setParentId(Value parentId) {
    
    if (!setValue(Messages.ParentId,parentId)) {
      add(Messages.ParentId,parentId);
    }
  }

  public Value getCreated() {
    
    return getValue(Messages.Created);
  }
  
  public void setCreated(Value created) {
    
    if (!setValue(Messages.Created,created)) {
      add(Messages.Created,created);
    }
  }

  public Value getCreatorId() {
    
    return getValue(Messages.CreatorId);
  }
  
  public void setCreatorId(Value creatorId) {
    
    if (!setValue(Messages.CreatorId,creatorId)) {
      add(Messages.CreatorId,creatorId);
    }
  }

  public Value getSenderId() {
    
    return getValue(Messages.SenderId);
  }
  
  public void setSenderId(Value senderId) {
    
    if (!setValue(Messages.SenderId,senderId)) {
      add(Messages.SenderId,senderId);
    }
  }

  public void setSenderId(Object senderId) {

    setSenderId(new Value(senderId));
  }
  
  public Value getSenderContact() {
    
    return getValue(Messages.SenderContact);
  }
  
  public void setSenderContact(Value senderContact) {
    
    if (!setValue(Messages.SenderContact,senderContact)) {
      add(Messages.SenderContact,senderContact);
    }
  }
  
  public void setSenderContact(String senderContact) {

    setSenderContact(new Value(senderContact));
  }
  

  public Value getSenderName() {
    
    return getValue(Messages.SenderName);
  }
  
  public void setSenderName(Value senderName) {
    
    if (!setValue(Messages.SenderName,senderName)) {
      add(Messages.SenderName,senderName);
    }
  }

  public void setSenderName(String senderName) {

    setSenderName(new Value(senderName));
  }
  
  public Value getRecipientId() {
    
    return getValue(Messages.RecipientId);
  }
  
  public void setRecipientId(Value recipientId) {
    
    if (!setValue(Messages.RecipientId,recipientId)) {
      add(Messages.RecipientId,recipientId);
    }
  }

  public void setRecipientId(Object recipientId) {
    
    setRecipientId(new Value(recipientId));
  }
  
  public Value getRecipientContact() {
    
    return getValue(Messages.RecipientContact);
  }
  
  public void setRecipientContact(Value recipientContact) {
    
    if (!setValue(Messages.RecipientContact,recipientContact)) {
      add(Messages.RecipientContact,recipientContact);
    }
  }
  
  public void setRecipientContact(String recipientContact) {

    setRecipientContact(new Value(recipientContact));
  }
  
  public Value getRecipientName() {
    
    return getValue(Messages.RecipientName);
  }

  public void setRecipientName(Value recipientName) {
    
    if (!setValue(Messages.RecipientName,recipientName)) {
      add(Messages.RecipientName,recipientName);
    }
  }
  
  public void setRecipientName(String recipientName) {
    
    setRecipientName(new Value(recipientName));
  }
  
  public Value getSubject() {
    
    return getValue(Messages.Subject);
  }
  
  public void setSubject(Value subject) {
    
    if (!setValue(Messages.Subject,subject)) {
      add(Messages.Subject,subject);
    }
  }

  public void setSubject(String subject) {

    setSubject(new Value(subject));
  }
  
  public Value getBody() {
    
    return getValue(Messages.Body);
  }
  
  public void setBody(Value body) {
    
    if (!setValue(Messages.Body,body)) {
      add(Messages.Body,body);
    }
  }

  public void setBody(String body) {

    setBody(new Value(body));
  }
  
  public Value getSent() {
    
    return getValue(Messages.Sent);
  }
  
  public void setSent(Value sent) {
    
    if (!setValue(Messages.Sent,sent)) {
      add(Messages.Sent,sent);
    }
  }
  
  public void setSent(Object sent) {

    setSent(new Value(sent));
  }

  public Value getError() {
    
    return getValue(Messages.Error);
  }
  
  public void setError(Value error) {
    
    if (!setValue(Messages.Error,error)) {
      add(Messages.Error,error);
    }
  }
  
  public void setError(String error) {

    setError(new Value(error));
  }
  

  public Value getBegin() {
    
    return getValue(Messages.Begin);
  }
  
  public void setBegin(Value begin) {
    
    if (!setValue(Messages.Begin,begin)) {
      add(Messages.Begin,begin);
    }
  }

  public void setBegin(Timestamp begin) {
   
    setBegin(new Value(begin));
  }
  
  public Value getEnd() {
    
    return getValue(Messages.End);
  }
  
  public void setEnd(Value end) {
    
    if (!setValue(Messages.End,end)) {
      add(Messages.End,end);
    }
  }

  public void setEnd(Timestamp end) {
   
    setEnd(new Value(end));
  }
  
  public Value getPriority() {
    
    return getValue(Messages.Priority);
  }
  
  public void setPriority(Value priority) {
    
    if (!setValue(Messages.Priority,priority)) {
      add(Messages.Priority,priority);
    }
  }

  public void setPriority(Integer priority) {
    
    setPriority(new Value(priority));
  }
  
  public Value getDelivered() {
    
    return getValue(Messages.Delivered);
  }
  
  public void setDelivered(Value delivered) {
    
    if (!setValue(Messages.Delivered,delivered)) {
      add(Messages.Delivered,delivered);
    }
  }

  public Value getChannelId() {
    
    return getValue(Messages.ChannelId);
  }
  
  public void setChannelId(Value channelId) {
    
    if (!setValue(Messages.ChannelId,channelId)) {
      add(Messages.ChannelId,channelId);
    }
  }
  
  public void setChannelId(Object channelId) {

    setChannelId(new Value(channelId));
  }
  

  public Value getLockId() {
    
    return getValue(Messages.LockId);
  }
  
  public void setLockId(Value lockId) {
    
    if (!setValue(Messages.LockId,lockId)) {
      add(Messages.LockId,lockId);
    }
  }

  public Value getRemoteId() {
    
    return getValue(Messages.RemoteId);
  }
  
  public void setRemoteId(Value remoteId) {
    
    if (!setValue(Messages.RemoteId,remoteId)) {
      add(Messages.RemoteId,remoteId);
    }
  }
  
  public void setRemoteId(String remoteId) {
    
    setRemoteId(new Value(remoteId));
  }

  public Value getContentType() {
    
    return getValue(Messages.ContentType);
  }
  
  public void setContentType(Value contentType) {
    
    if (!setValue(Messages.ContentType,contentType)) {
      add(Messages.ContentType,contentType);
    }
  }
  
  public void setContentType(String contentType) {
    
    setContentType(new Value(contentType));
  }

  public Value getPatternId() {
    
    return getValue(Messages.PatternId);
  }
  
  public void setPatternId(Value patternId) {
    
    if (!setValue(Messages.PatternId,patternId)) {
      add(Messages.PatternId,patternId);
    }
  }

  public void setPatternId(Object patternId) {
    
    setPatternId(new Value(patternId));
  }
  
  public Value getHeaders() {
    
    return getValue(Messages.Headers);
  }
  
  public void setHeaders(Value headers) {
    
    if (!setValue(Messages.Headers,headers)) {
      add(Messages.Headers,headers);
    }
  }

  public void setHeaders(String headers) {
    
    setHeaders(new Value(headers));
  }
  
  public Value getLocked() {
    
    return getValue(Messages.Locked);
  }
  
  public void setLocked(Value locked) {
    
    if (!setValue(Messages.Locked,locked)) {
      add(Messages.Locked,locked);
    }
  }

  public void setLocked(Timestamp locked) {
   
    setBegin(new Value(locked));
  }
  
  public Value getChannelClass() {
    
    return getValue(Messages.ChannelClass);
  }
  
  private static final ConcurrentHashMap<String,Attachments> attachmentCache =  new ConcurrentHashMap<>();
          
  public Attachments getAttachments(boolean refresh) {
    
    if (refresh) {

      Value messageId = getMessageId();
      if (messageId.isNotNull()) {
        attacments.open(new Filter(Attachments.MessageId,messageId));
      }
      
      if (attacments.isEmpty()) {
        
        Value parentId = getParentId();
        if (parentId.isNotNull()) {
          
          Attachments atts = attachmentCache.get(parentId.asString());
          if (isNotNull(atts) && !atts.isEmpty()) {
            attacments.copyFrom(atts);
          } else {
            if (attacments.open(new Filter(Attachments.MessageId,parentId))) {
              if (!attacments.isEmpty()) {
                atts = new Attachments();
                atts.copyFrom(attacments,true);
                attachmentCache.put(parentId.asString(),atts);
              }
            }
          }
        }
      }
    }
    return attacments;
  }

  public Attachments getAttachments() {
    
    return getAttachments(attacments.isEmpty());
  }
  
}