package ufsic.gates;

import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.scheme.Channels;

public class MessageGateChannels extends Channels {
  
  private final IMessageGate gate;
  
  public MessageGateChannels(IMessageGate gate, Provider provider) {
    
    super(provider,MessageGateChannels.TableName);
    this.gate = gate;
  }
  
  public MessageGateChannels(MessageGateChannels source, boolean withCopy) {
    
    this(source.gate,source.provider);
    if (withCopy) {
      copyFrom(source);
    }
  }
  
  
  @Override
  public Class getRecordClass() {

    return null;
  }
  
  @Override
  public Class getRecordClassByRecord(Record record) {

    Class ret = MessageGateChannel.class;
    if (isNotNull(record) && isNotNull(gate)) {
      String className = record.getValue(ClassName).asString();
      ret = gate.getClass(className);
    }
    return ret;
  }
  
  public IMessageGate getGate() {
    
    return this.gate;
  }
  
  public final boolean sendAll(boolean withWait) {
    
    boolean ret = false;
    if (!isEmpty()) {
      boolean f = true;
      for (Record r: this) {
        MessageGateChannel mgc = (MessageGateChannel)r;
        if (isNotNull(mgc) && (mgc instanceof OutgoingMessageChannel)) {
          f = f & mgc.sendAll(withWait);
        }
      }
      ret = f;
    }
    return ret;
  }
  
  public final boolean receiveAll(boolean withWait) {
    
    boolean ret = false;
    if (!isEmpty()) {
      boolean f = true;
      for (Record r: this) {
        MessageGateChannel mgc = (MessageGateChannel)r;
        if (isNotNull(mgc) && (mgc instanceof IncomingMessageChannel)) {
          f = f & mgc.receiveAll(withWait);
        }
      }
      ret = f;
    }
    return ret;
  }
  
}
