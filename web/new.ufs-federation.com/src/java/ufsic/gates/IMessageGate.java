package ufsic.gates;

import javax.enterprise.concurrent.ManagedScheduledExecutorService;
import ufsic.providers.Value;

interface IMessageGate {
  
  public String getJndiName();
  public Value getAccountId();
  public ManagedScheduledExecutorService getExecutor();
  public Class getClass(String className);
  
}
