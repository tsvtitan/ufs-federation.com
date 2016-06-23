package ufsic.applications;

import ufsic.providers.Provider;

public interface IDatabaseApplication extends IApplication {

  public Provider newProvider();
  public Provider getProvider();
}
