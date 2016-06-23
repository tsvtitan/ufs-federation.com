package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Attachment extends SchemeRecord {
  
  public Attachment() {
    super();  
  }

  public Attachment(Scheme scheme) {
    super(scheme);  
  }
  
  public Attachment(SchemeTable table, Record record) {
    super(table, record);
  }

  public Attachment(SchemeTable table) {
    super(table, null);
  }
  
  public Value getAttachmentId() {
    
    return getValue(Attachments.AttachmentId);
  }
  
  public void setAttachmentId(Value attachmentId) {
    
    if (!setValue(Attachments.AttachmentId,attachmentId)) {
      add(Attachments.AttachmentId,attachmentId);
    }
  }
  
  public Value getMessageId() {
    
    return getValue(Messages.MessageId);
  }
  
  public void setMessageId(Value messageId) {
    
    if (!setValue(Attachments.MessageId,messageId)) {
      add(Attachments.MessageId,messageId);
    }
  }
  
  public Value getName() {
    
    return getValue(Attachments.Name);
  }

  public void setName(Value name) {
    
    if (!setValue(Attachments.Name,name)) {
      add(Attachments.Name,name);
    }
  }

  public void setName(String name) {

    setName(new Value(name));
  }
  
  public Value getExtension() {
    
    return getValue(Attachments.Extension);
  }

  public void setExtension(Value extension) {
    
    if (!setValue(Attachments.Extension,extension)) {
      add(Attachments.Extension,extension);
    }
  }

  public void setExtension(String extension) {

    setExtension(new Value(extension));
  }
  
  public Value getData() {
    
    return getValue(Attachments.Data);
  }

  public void setData(Value data) {
    
    if (!setValue(Attachments.Data,data)) {
      add(Attachments.Data,data);
    }
  }
  
  public void setData(Object data) {
    
    if (!setValue(Attachments.Data,data)) {
      add(Attachments.Data,data);
    }
  }

  public Value getContentType() {
    
    return getValue(Attachments.ContentType);
  }

  public void setContentType(Value contentType) {
    
    if (!setValue(Attachments.ContentType,contentType)) {
      add(Attachments.ContentType,contentType);
    }
  }

  public void setContentType(String contentType) {
    
    setContentType(new Value(contentType));
  }

  public Value getContentId() {
    
    return getValue(Attachments.ContentId);
  }

  public void setContentId(Value contentId) {
    
    if (!setValue(Attachments.ContentId,contentId)) {
      add(Attachments.ContentId,contentId);
    }
  }

  public void setContentId(String contentId) {
    
    setContentId(new Value(contentId));
  }
  
}