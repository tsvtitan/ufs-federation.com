package ufsic.scheme;

import java.sql.Timestamp;
import ufsic.providers.Filter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Message extends SchemeRecord {

  private Attachments attacments = new Attachments();
          
  public Message() {
    super();  
  }

  public Message(Scheme scheme) {
    super(scheme);  
  }
  
  public Message(SchemeTable table, Record record) {
    super(table, record);
  }

  public Message(SchemeTable table) {
    super(table, null);
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
  
  public Value getChannelClass() {
    
    return getValue(Messages.ChannelClass);
  }
  
  public Attachments getAttachments(boolean refresh) {
    
    if (refresh) {

      attacments.open(new Filter(Attachments.MessageId,getMessageId()));
    }
    return attacments;
  }

  public Attachments getAttachments() {
    
    return getAttachments(attacments.isEmpty());
  }
  
}