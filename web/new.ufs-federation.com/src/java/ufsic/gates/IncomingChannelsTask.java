package ufsic.gates;

import ufsic.providers.Filter;
import ufsic.providers.OracleProvider;
import ufsic.providers.Orders;
import ufsic.providers.Provider;

public class IncomingChannelsTask extends MessageGateTask {

  private String channelId = null;
  
  public IncomingChannelsTask(IMessageGate gate, String channelId, boolean withWait) {
    super(gate, withWait);
    this.channelId = channelId;
  }

  @Override
  public MessageGateTaskResult call() {

    MessageGateTaskResult result = new MessageGateTaskResult();
    try {
      Provider p = new OracleProvider(getGate().getJndiName());
      if (p.checkConnected()) {

        MessageGateChannels channels = new MessageGateChannels(getGate(),p);
        
        Filter fl;
        if (isNotNull(channelId)) {
          fl = new Filter(MessageGateChannels.Locked,null).And(MessageGateChannels.ChannelId,channelId);
        } else {
          fl = new Filter(MessageGateChannels.Locked,null).And(MessageGateChannels.ParentId,null);
        }
    
        boolean r = channels.open(fl,new Orders(MessageGateChannels.Priority));
        if (r) {
        
          boolean f = channels.receiveAll(getWithWait());
          result.setSuccess(f);
        }
      }
    } catch (Exception e) {
      //
    }
    return result;
  }

  
}
