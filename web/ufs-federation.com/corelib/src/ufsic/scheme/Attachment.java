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
  
  @Override
  public void copyFrom(Record source, boolean newValues) {
    
    super.copyFrom(source);
    if (isNotNull(source) && (source instanceof Attachment)) {
      
      if (newValues) {
        Attachment att = (Attachment)source;
        
        if (att.getAttachmentId().isNotNull()) setAttachmentId(new Value(att.getAttachmentId().asString()));
        if (att.getMessageId().isNotNull()) setMessageId(new Value(att.getMessageId().asString()));
        if (att.getName().isNotNull()) setName(new Value(att.getName().asString()));
        if (att.getExtension().isNotNull()) setExtension(new Value(att.getExtension().asString()));
        if (att.getData().isNotNull()) setData(new Value(att.getData().asBytes()));
        if (att.getContentType().isNotNull()) setContentType(new Value(att.getContentType().asString()));
        if (att.getContentId().isNotNull()) setContentId(new Value(att.getContentId().asString()));
        
      }
    }
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
  
  public void setData(String data) {
    
    if (isNotNull(data)) {
      setData(data.getBytes());
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