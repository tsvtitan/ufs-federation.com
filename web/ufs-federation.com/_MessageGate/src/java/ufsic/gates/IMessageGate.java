package ufsic.gates;

import javax.enterprise.concurrent.ManagedScheduledExecutorService;
import ufsic.applications.IDatabaseApplication;

public interface IMessageGate extends IDatabaseApplication {
  
  public ManagedScheduledExecutorService getExecutor();
}
