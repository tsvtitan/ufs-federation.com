package ufsic.gates;

import java.util.concurrent.atomic.AtomicInteger;
import javax.enterprise.concurrent.ManagedScheduledExecutorService;
import ufsic.applications.IDatabaseApplication;

public interface IMessageGate extends IDatabaseApplication {
  
  public boolean checkOutgoing();
  public ManagedScheduledExecutorService getExecutor();
  public AtomicInteger getOutgoingCounter();
}
