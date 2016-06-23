package ufsic.scheme;

import ufsic.providers.Provider;

public class Channels extends SchemeTable<Channel> {

  public final static String TableName = "CHANNELS";
  
  public final static String ChannelId = "CHANNEL_ID";
  public final static String ParentId = "PARENT_ID";
  public final static String Name = "NAME";
  public final static String Description = "DESCRIPTION";
  public final static String ClassName = "CLASS_NAME";
  public final static String Locked = "LOCKED";
  public final static String Priority = "PRIORITY";
  public final static String Settings = "SETTINGS";
  public final static String QueueLength = "QUEUE_LENGTH";
  
  public final static String Level = "LEVEL";
  

  public Channels(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public Channels(Provider provider, String name) {
    
    super(provider, name);
  }
  
  public Channels(Provider provider) {
    
    this(provider,TableName);
  }
  
  public Channels() {
    
    super();
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {

    return Channel.class;
  }
  
}
