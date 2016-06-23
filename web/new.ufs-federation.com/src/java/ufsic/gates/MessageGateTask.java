package ufsic.gates;

import java.util.concurrent.Callable;
import ufsic.core.ICoreObject;
import ufsic.utils.Utils;

public class MessageGateTask implements Callable<MessageGateTaskResult>, ICoreObject {

  private final IMessageGate gate;
  private final boolean withWait;
  
  public MessageGateTask(IMessageGate gate, boolean withWait) {
    
    this.gate = gate;
    this.withWait = withWait;
  }
  
  public IMessageGate getGate() {
    
    return this.gate;
  }
  
  public boolean getWithWait() {
    
    return this.withWait;
  }
 
  @Override
  public boolean isNull(Object obj) {
    return Utils.isNull(obj);
  }
  
  @Override
  public boolean isNotNull(Object obj) {
    return Utils.isNotNull(obj);
  }
  
  @Override
  public MessageGateTaskResult call() {
    
    return null;
  }
  
}