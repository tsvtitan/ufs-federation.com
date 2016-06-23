package ufsic.applications;

import ufsic.gates.IMessageGateRemote;

public interface ISchemeServletApplication extends IServletApplication {
  
  public IMessageGateRemote getMessageGate();
}
