package ufsic.gates.tasks;

import ufsic.gates.IMessageGate;
import ufsic.gates.channels.MessageGateChannel;
import ufsic.gates.channels.MessageGateChannels;

import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Params;
import ufsic.providers.Provider;

import ufsic.scheme.Message;
import ufsic.scheme.Messages;
import ufsic.utils.Utils;

public class OutgoingTask extends MessageGateTask {

  private String channelId = null;
  
  public OutgoingTask(IMessageGate gate, String channelId, boolean withWait) {
    super(gate,withWait);
    this.channelId = channelId;
  }
  
  private boolean processMessages(Provider p, Params ps, Orders o, MessageGateChannels chls) {
    
    boolean ret = false;
    
    boolean r = p.execute("LOCK_OUTGOING_MESSAGES",ps);
    if (r) {
      
      int lockCount = ps.getValue("LOCK_COUNT").asInteger();
      if (lockCount>0) {
        
        MessageGateChannels channels = new MessageGateChannels(chls,true);
        
        Messages ms = new Messages();
        ms.setProvider(p);
        
        if (ms.open(new Filter(Messages.LockId,ps.getValue(Messages.LockId)),o)) {

          ms.setProvider(null);
          
          for (Message mm: ms) {
            
            Message m = new Message(ms);
            m.copyFrom(mm,true);

            MessageGateChannel channel = (MessageGateChannel)channels.findFirst(MessageGateChannels.ChannelId,m.getChannelId());
            if (isNotNull(channel)) {
              channel.addMessage(m);
            }
          }
          
          ret = channels.send(getWithWait()); 
        }
      }
    }
    return ret;
  }
  
  private Integer getChannelsCount() {
  
    String count = getGate().getOption("OutgoingChannels.Count",null);
    return Utils.toInteger(count,null);
  }
  
  @Override
  public MessageGateTaskResult call() {

    MessageGateTaskResult result = new MessageGateTaskResult();
    try {

      IMessageGate gate = getGate();
      if (isNotNull(gate)) {        
        
        Integer channelsCount = getChannelsCount();
        
        if (isNull(channelsCount) || (isNotNull(channelsCount) && channelsCount>gate.getOutgoingCounter().get())) {
          
          Provider p = gate.newProvider();
          if (isNotNull(p)) {

            try {
              MessageGateChannels channels = new MessageGateChannels(gate,p);

              Filter fl;
              if (isNotNull(channelId)) {
                fl = new Filter(MessageGateChannels.Locked,null).And(MessageGateChannels.ChannelId,channelId);
              } else {
                fl = new Filter(MessageGateChannels.Locked,null);
              }

              boolean r = channels.open(fl,new Orders(MessageGateChannels.Priority));
              if (r) {

                Orders o = new Orders(Messages.Created).Add(Messages.Priority);
                Params ps = new Params(Messages.ChannelId,channelId).AddOut(Messages.LockId).AddOut("LOCK_COUNT").AddOut("ALL_COUNT");

                boolean f = true;
                int lockCount = 0;
                int allCount;

                do {

                  boolean processed = processMessages(p,ps,o,channels);
                  if (processed) {
                    lockCount = ps.getValue("LOCK_COUNT").asInteger();
                    allCount = ps.getValue("ALL_COUNT").asInteger();
                  } else {
                    allCount = 0;
                  }

                  f = f & processed;

                  if (isNotNull(channelsCount) && channelsCount<gate.getOutgoingCounter().get()) {
                    break;
                  }

                } while ((allCount - lockCount)>0);
                
                result.setSuccess(f);
              }
             
            } finally {
              p.disconnect();
            }
          }
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return result;
  }
  
}
