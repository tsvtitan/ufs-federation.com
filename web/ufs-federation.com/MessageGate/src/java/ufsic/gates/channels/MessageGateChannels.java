package ufsic.gates.channels;

import ufsic.gates.IMessageGate;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.scheme.Channels;

public class MessageGateChannels extends Channels {
  
  private final IMessageGate gate;
  
  public MessageGateChannels(IMessageGate gate, Provider provider) {
    
    super(provider);
    this.gate = gate;
  }
  
  public MessageGateChannels(MessageGateChannels source, boolean withCopy) {
    
    this(source.gate,source.getProvider());
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
      Package pkg = getClass().getPackage();
      ret = gate.getClass(pkg.getName(),record.getValue(ClassName).asString());
    }
    return ret;
  }
  
  public IMessageGate getGate() {
    
    return this.gate;
  }
  
  public final boolean send(boolean withWait) {
    
    boolean ret = false;
    if (!isEmpty()) {
      boolean f = true;
      for (Record r: this) {
        MessageGateChannel mgc = (MessageGateChannel)r;
        if (isNotNull(mgc) && mgc.canSend()) {
          f = f & mgc.sendAll(withWait);
        }
      }
      ret = f;
    }
    return ret;
  }
  
  public final boolean receive(boolean withWait) {
    
    boolean ret = false;
    if (!isEmpty()) {
      boolean f = true;
      for (Record r: this) {
        MessageGateChannel mgc = (MessageGateChannel)r;
        if (isNotNull(mgc) && mgc.canReceive()) {
          f = f & mgc.receiveAll(withWait);
        }
      }
      ret = f;
    }
    return ret;
  }
  
  public final boolean getStatuses(boolean withWait) {
    
    boolean ret = false;
    if (!isEmpty()) {
      boolean f = true;
      for (Record r: this) {
        MessageGateChannel mgc = (MessageGateChannel)r;
        if (isNotNull(mgc) && mgc.canGetStatuses()) {
          f = f & mgc.getStatuses(withWait);
        }
      }
      ret = f;
    }
    return ret;
  }
  
}
