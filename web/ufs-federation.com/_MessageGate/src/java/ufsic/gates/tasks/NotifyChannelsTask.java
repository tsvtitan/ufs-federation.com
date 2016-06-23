package ufsic.gates.tasks;

import ufsic.gates.channels.OutgoingMessageChannel;
import ufsic.gates.channels.MessageGateChannel;
import ufsic.gates.channels.MessageGateChannels;
import ufsic.gates.channels.IncomingMessageChannel;
import java.util.concurrent.ScheduledFuture;
import java.util.concurrent.TimeUnit;
import ufsic.gates.IMessageGate;
import ufsic.providers.Filter;
import ufsic.providers.Provider;

public class NotifyChannelsTask extends MessageGateTask {

  private String channelId = null;
  
  public NotifyChannelsTask(IMessageGate gate, String channelId, boolean withWait) {
    super(gate, withWait);
    this.channelId = channelId;
  }
  
  private boolean run(MessageGateTask task, boolean withWait) {
  
    boolean ret = false;
    
    ScheduledFuture<MessageGateTaskResult> f = getGate().getExecutor().schedule(task,0,TimeUnit.SECONDS);
    if (isNotNull(f)) {
      if (withWait) {
        try {
          MessageGateTaskResult result = f.get();
          if (isNotNull(result)) {
            ret = result.getSuccess();
          }
        } catch (Exception e) {
          logException(e);
        }
      } else {
        ret = true;
      }
    }
    return ret;
  }
  
  @Override
  public MessageGateTaskResult call() {

    MessageGateTaskResult result = new MessageGateTaskResult();
    try {
      Provider p = getGate().newProvider();
      if (isNotNull(p) && p.checkConnected()) {

        try {
          MessageGateChannels channels = new MessageGateChannels(getGate(),p);

          boolean r = channels.open(new Filter(MessageGateChannels.Locked,null).And(MessageGateChannels.ChannelId,channelId));
          if (r) {

            MessageGateChannel mgc = (MessageGateChannel)channels.first();
            if (mgc instanceof IncomingMessageChannel) {

              r = run(new IncomingChannelsTask(getGate(),channelId,false),false);  

            } else if (mgc instanceof OutgoingMessageChannel) {

              r = run(new OutgoingChannelsTask(getGate(),channelId,false),false);  
            }
            result.setSuccess(r);
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
