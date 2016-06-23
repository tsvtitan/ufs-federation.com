package ufsic.gates.channels;

import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.scheme.SchemeTable;

public class IncomingMessageChannel extends MessageGateChannel {

  public IncomingMessageChannel() {
    super();
  }
  
  public IncomingMessageChannel(SchemeTable table, Record record) {
    super(table, record);
  }

  public IncomingMessageChannel(SchemeTable table) {
    super(table, null);
  }

  @Override
  protected boolean isConnected() {
    
    return true;
  }
       
  @Override
  public boolean canSend() {
    
    return false;
  }
  
  @Override
  public boolean canReceive() {
    
    return true;
  }
  
  @Override
  public boolean receiveMessages(boolean withWait) {
    
    boolean ret = false;
    
    MessageGateChannels channels = new MessageGateChannels(getGate(),getProvider());
    
    Filter fl = new Filter(MessageGateChannels.ParentId,getChannelId());
    fl.And(MessageGateChannels.Locked).IsNull();
    
    boolean exists = channels.open(fl,new Orders(MessageGateChannels.Priority));
    if (exists) {

      for (Record r: channels) {
          
        MessageGateChannel channel = (MessageGateChannel)r;
        if (channel.canReceive()) {

          ret = channel.receiveAll(withWait);
        }
      }
    }
    return ret;
  }
  
}
