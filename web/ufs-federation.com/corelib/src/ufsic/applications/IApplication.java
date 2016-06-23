package ufsic.applications;

import ufsic.core.ICoreObject;
import ufsic.out.Echo;
import ufsic.out.Logger;
import ufsic.providers.Value;

public interface IApplication extends ICoreObject  {

  public Value getAccountId();
  public Class getClass(String className);
  public Class getClass(String packageName, String className);
  public Logger getLogger();
  public Echo getEcho();
  public void reloadOptions();
  public Options getOptions();
  public String getOption(String name, String def);
}
