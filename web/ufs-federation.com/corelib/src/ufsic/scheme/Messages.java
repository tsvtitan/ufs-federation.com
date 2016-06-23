package ufsic.scheme;

import ufsic.providers.FieldNames;
import ufsic.providers.Provider;

public class Messages extends SchemeTable<Message> {

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
  public final static String Headers = "HEADERS";
  public final static String Locked = "LOCKED";
  
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
  public final static String PatternType = "PATTERN_TYPE";
  
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
  
  public static FieldNames getTableFieldNames() {
    
    FieldNames ret = new FieldNames();
    ret.add(MessageId);
    ret.add(ParentId);
    ret.add(Created);
    ret.add(CreatorId);
    ret.add(SenderId);
    ret.add(SenderContact);
    ret.add(SenderName);
    ret.add(RecipientId);
    ret.add(RecipientContact);
    ret.add(RecipientName);
    ret.add(Subject);
    ret.add(Body); 
    ret.add(Sent);    
    ret.add(Error);  
    ret.add(Begin); 
    ret.add(End); 
    ret.add(Priority); 
    ret.add(Delivered); 
    ret.add(ChannelId);
    ret.add(LockId);
    ret.add(RemoteId); 
    ret.add(ContentType); 
    ret.add(PatternId); 
    ret.add(Headers);
    ret.add(Locked);
    return ret;
  }
  
  public static FieldNames getViewFieldNames() {
    
    FieldNames ret = getTableFieldNames();
    ret.add(CreatorLogin);
    ret.add(SenderLogin);
    ret.add(SenderEmail);
    ret.add(SenderPhone);
    ret.add(RecipientLogin);
    ret.add(RecipientEmail);
    ret.add(RecipientPhone);
    ret.add(ChannelName);
    ret.add(ChannelClass);
    ret.add(PatternName);
    ret.add(PatternType);
    return ret;
  }
}
