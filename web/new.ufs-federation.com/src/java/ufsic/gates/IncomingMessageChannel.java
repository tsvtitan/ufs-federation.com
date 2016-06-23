package ufsic.gates;

import ufsic.providers.DataSet;
import ufsic.providers.Record;
import ufsic.scheme.Message;
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
  public boolean receiveMessages() {
    
    boolean ret = false;
    
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
              ret = channel.receiveMessages();
              if (isNotNull(ret)) {
                for (Record rec: channel.getMessages()) {
                  Message m = (Message)rec;
                  m.setChannelId(channel.getChannelId());
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
