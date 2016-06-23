package ufsic.gates.tasks;

import ufsic.gates.IMessageGate;
import ufsic.gates.channels.MessageGateChannels;
import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Provider;

public class IncomingTask extends MessageGateTask {

  private String channelId = null;
  
  public IncomingTask(IMessageGate gate, String channelId, boolean withWait) {
    super(gate, withWait);
    this.channelId = channelId;
  }

  @Override
  public MessageGateTaskResult call() {

    MessageGateTaskResult result = new MessageGateTaskResult();
    try {
      Provider p = getGate().newProvider();
      if (isNotNull(p)) {

        try {
          MessageGateChannels channels = new MessageGateChannels(getGate(),p);

          Filter fl;
          if (isNotNull(channelId)) {
            fl = new Filter(MessageGateChannels.Locked,null).And(MessageGateChannels.ChannelId,channelId);
          } else {
            fl = new Filter(MessageGateChannels.Locked,null).And(MessageGateChannels.ParentId).IsNull();
          }

          boolean r = channels.open(fl,new Orders(MessageGateChannels.Priority));
          if (r) {

            boolean f = channels.receive(getWithWait());
            result.setSuccess(f);
          }
        } finally {
          p.disconnect();
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return result;
  }

  
}
