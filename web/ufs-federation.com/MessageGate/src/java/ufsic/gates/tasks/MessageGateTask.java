package ufsic.gates.tasks;

import java.util.concurrent.Callable;
import ufsic.core.CoreObject;
import ufsic.gates.IMessageGate;

public class MessageGateTask extends CoreObject implements Callable<MessageGateTaskResult> {

  private final IMessageGate gate;
  private final boolean withWait;
  
  public MessageGateTask(IMessageGate gate, boolean withWait) {
    
    super(gate.getLogger());
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
  public MessageGateTaskResult call() {
    
    return null;
  }
  
}