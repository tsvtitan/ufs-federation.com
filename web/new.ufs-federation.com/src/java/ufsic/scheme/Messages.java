package ufsic.scheme;

import ufsic.providers.Provider;

public class Messages extends SchemeTable {

  public final static String TableName = "MESSAGES";
  
  public final static String MessageId = "MESSAGE_ID";
  public final static String ParentId = "PARENT_ID";
  public final static String Created = "CREATED";
  public final static String CreatorId = "CREATOR_ID";
  public final static String SenderId = "SENDER_ID";
  public final static String SenderContact = "SENDER_CONTACT";
  public final static String SenderName = "SENDER_NAME";
  public final static String RecipientId = "RECIPIENT_ID";
  public final static String RecipientContact = "RECIPIENT_CONTACT";
  public final static String RecipientName = "RECIPIENT_NAME";
  public final static String Subject = "SUBJECT";
  public final static String Body = "BODY";
  public final static String Sent = "SENT";
  public final static String Error = "ERROR";
  public final static String Begin = "BEGIN";
  public final static String End = "END";
  public final static String Priority = "PRIORITY";
  public final static String Delivered = "DELIVERED";
  public final static String ChannelId = "CHANNEL_ID";
  public final static String LockId = "LOCK_ID";
  public final static String RemoteId = "REMOTE_ID";
  public final static String ContentType = "CONTENT_TYPE";
  public final static String PatternId = "PATTERN_ID";
  
  public final static String CreatorLogin = "CREATOR_LOGIN";
  public final static String SenderLogin = "SENDER_LOGIN";
  public final static String SenderEmail = "SENDER_EMAIL";
  public final static String SenderPhone = "SENDER_PHONE";
  public final static String RecipientLogin = "RECIPIENT_LOGIN";
  public final static String RecipientEmail = "RECIPIENT_EMAIL";
  public final static String RecipientPhone = "RECIPIENT_PHONE";
  public final static String ChannelName = "CHANNEL_NAME";
  public final static String ChannelClass = "CHANNEL_CLASS";
  public final static String PatternName = "PATTERN_NAME";
  public final static String PatternClass = "PATTERN_CLASS";
  
  public Messages(Scheme scheme, String name) {
    
    super(scheme, name);
  }
  
  public Messages(Provider provider, String name) {
    
    super(provider, name);
  }

  public Messages(Scheme scheme) {
    super(scheme);
    this.name = TableName;
  }

  public Messages() {
    
    super();
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {

    return Message.class;
  }
  
}
