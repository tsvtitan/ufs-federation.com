package ufsic.scheme;

import ufsic.providers.Provider;

public class Attachments extends SchemeTable {
  
  public final static String TableName = "ATTACHMENTS";
  
  public final static String AttachmentId = "ATTACHMENT_ID";
  public final static String MessageId = "MESSAGE_ID";
  public final static String Name = "NAME";
  public final static String Extension = "EXTENSION";
  public final static String Data = "DATA";
  public final static String ContentType = "CONTENT_TYPE";
  public final static String ContentId = "CONTENT_ID";
  
  public Attachments(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public Attachments(Provider provider, String name) {
    
    super(provider, name);
  }

  public Attachments() {
    
    super();
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {

    return Attachment.class;
  }

  
}
