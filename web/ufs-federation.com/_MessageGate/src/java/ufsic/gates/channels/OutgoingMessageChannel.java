package ufsic.gates.channels;

import ufsic.providers.Dataset;
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
  public boolean canSend() {
    
    return true;
  }
  
  @Override
  public boolean canReceive() {
    
    return false;
  }

  @Override
  public boolean sendMessage(Message message) {
    
    boolean ret = false;
    
    if (isNotNull(message)) {
      
      MessageGateChannels channels = getChannels();
      if (isNotNull(channels)) {
        
        Dataset ds = new Dataset();
        boolean exists = channels.getList(MessageGateChannels.ParentId,getChannelId(),ds,false);
        if (exists) {
          
          Record r = ds.randomRecord();
          if (isNotNull(r)) {
            
            MessageGateChannel channel = (MessageGateChannel)r;
            if (channel.canSend()) {
              
              if (channel.connect()) {
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
