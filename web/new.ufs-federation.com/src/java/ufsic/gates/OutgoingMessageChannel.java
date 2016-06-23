package ufsic.gates;

import ufsic.providers.DataSet;
import ufsic.providers.Record;
import ufsic.scheme.Message;
import ufsic.scheme.SchemeTable;

public class OutgoingMessageChannel extends MessageGateChannel {

  public OutgoingMessageChannel() {
    super();
  }
  
  public OutgoingMessageChannel(SchemeTable table, Record record) {
    super(table, record);
  }

  public OutgoingMessageChannel(SchemeTable table) {
    super(table, null);
  }
  
  
  @Override
  public boolean isConnected() {
    
    return true;
  }

  @Override
  public boolean sendMessage(Message message) {
    
    boolean ret = false;
    
    if (isNotNull(message)) {
      
      MessageGateChannels channels = getChannels();
      if (isNotNull(channels)) {
        
        DataSet ds = new DataSet();
        boolean exists = channels.getList(MessageGateChannels.ParentId,getChannelId(),ds,false);
        if (exists) {
          
          Record r = ds.randomRecord();
          if (isNotNull(r)) {
            
            r = channels.findFirst(MessageGateChannels.ChannelId,r.getValue(MessageGateChannels.ChannelId));
            if (isNotNull(r)) {
              
              MessageGateChannel channel = (MessageGateChannel)r;
              if (!channel.isConnected()) {
                channel.connect();
              }
              if (channel.isConnected()) {
                ret = channel.sendMessage(message);
                if (ret) {
                  message.setChannelId(channel.getChannelId());
                }
              }
            }
          }
        }
      }
    }
    return ret;
  }
  
}
