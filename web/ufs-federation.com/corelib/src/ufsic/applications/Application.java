package ufsic.applications;

import ufsic.core.CoreObject;
import ufsic.out.Echo;
import ufsic.out.Logger;
import ufsic.providers.Value;

public class Application extends CoreObject implements IApplication {
  
  private final Options options = new Options();
  private final Value accountId = new Value(null);

  public Application(Logger logger, Echo echo) {

    super(logger,echo);
  }
  
  protected String getOptionsName() {
    
    String ret = null;
    
    String def = getClass().getSimpleName();
    String appName = contextLookup("java:app/AppName",(String)null);
    String moduleName = contextLookup("java:module/ModuleName",(String)null);
    
    if (isNotNull(appName) && isNotNull(moduleName)) {
      
      if (!appName.equals(moduleName)) {
        String s = String.format("%s.%s",appName,moduleName);
        if (contextExists(s)) {
          ret = s;
        }
      }
      if (isNull(ret)) {
        String s = appName;
        if (contextExists(s)) {
          ret = s;
        }
      }
    }
    return isNull(ret)?def:ret;
  }
  
  @Override
  public void setLogger(Logger logger) {
    
    super.setLogger(logger);
    options.setLogger(logger);
  }
  
  private Logger getDefaultLogger() {
    
    Logger ret = getLogger();
    if (isNull(ret)) {
      ret = new Logger(new Echo(System.out,true));
    }
    return ret;
  }
  
  @Override
  public void reloadOptions() {
    
    String optionsName = getOptionsName();
    options.load(optionsName);
    accountId.setObject(getOption("Application.Id",optionsName));
  }
  
  protected void startUp() {
    
    setLogger(getDefaultLogger());
    reloadOptions();
  }
  
  protected void shutDown() {
    //
  }
  
  @Override
  public Options getOptions() {
    return options;
  }
  
  @Override
  public Class getClass(String packageName, String className) {
  
    Class ret = null;
    if (isNotNull(className)) {
      try {
        String name; 
        String pkg = packageName;
        if (isNull(pkg)) {
          name = className;
        } else {
          name = String.format("%s.%s",pkg,className);
        }
        ret = this.getClass().getClassLoader().loadClass(name);
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }

  @Override
  public Class getClass(String className) {
  
    return getClass(null,className);
  }

  @Override
  public Value getAccountId() {
    
    return accountId;
  }
  
  @Override
  public String getOption(String name, String def) {
    
    String ret = def;
    if (isNotNull(options)) {
      ret = options.getProperty(name,def);
    }
    return ret;
  }
  
}
