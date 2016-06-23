package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Channel extends SchemeRecord {

  public Channel() {
    super();
  }
  
  public Channel(SchemeTable table, Record record) {
    super(table, record);
  }

  public Channel(SchemeTable table) {
    super(table, null);
  }
  
  public Value getChannelId() {
    
    return getValue(Channels.ChannelId);
  }

  public Value getParentId() {
    
    return getValue(Channels.ParentId);
  }

  public Value getName() {
    
    return getValue(Channels.Name);
  }

  public Value getDescription() {
    
    return getValue(Channels.Description);
  }

  public Value getClassName() {
    
    return getValue(Channels.ClassName);
  }

  public Value getLocked() {
    
    return getValue(Channels.Locked);
  }

  public Value getPriority() {
    
    return getValue(Channels.Priority);
  }

  public Value getSettings() {
    
    return getValue(Channels.Settings);
  }

  public Value getQueueLength() {
    
    return getValue(Channels.QueueLength);
  }
  
}
