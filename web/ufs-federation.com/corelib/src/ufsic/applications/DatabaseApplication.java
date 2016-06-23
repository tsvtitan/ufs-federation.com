package ufsic.applications;

import java.lang.reflect.Constructor;
import ufsic.out.Echo;
import ufsic.out.Logger;
import ufsic.providers.Provider;

public class DatabaseApplication extends Application implements IDatabaseApplication {

  private Provider provider = null;
  private String providerClass = null;
  private String providerJndi = null;
  
  public DatabaseApplication(Logger logger, Echo echo) {

    super(logger,echo);
  }
  
  public DatabaseApplication() {
    this(null,null);
  }
  
  private synchronized Provider newProvider(Logger logger, String className, String jndiName) {
    
    Provider ret = null;
    Class cls = getClass(className);
    if (isNotNull(cls)) {
      try {
        Constructor con = cls.getConstructor(String.class);
        if (isNotNull(con)) {
          ret = (Provider)con.newInstance(jndiName);
          if (isNotNull(ret)) {
            ret.setLogger(logger);
          }
        }
      } catch(Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  @Override
  public synchronized Provider newProvider() {
   
    return newProvider(getLogger(),providerClass,providerJndi);
  }
  
  private void providerCleanUp() {
    
    if (isNotNull(provider)) {
      provider.disconnect();
      provider = null;
    }
  }
  
  @Override
  public void reloadOptions() {  
    
    super.reloadOptions();
    providerCleanUp();
    
    if (isNull(provider)) {
      providerClass = getOption("Provider.Class",ufsic.providers.OracleProvider.class.getName());
      providerJndi = getOption("Provider.Jndi","jdbc/work");

      provider = newProvider(getLogger(),providerClass,providerJndi);
    }
  }
  
  @Override
  public void startUp() {
    
    super.startUp();
  }
  
  @Override
  public void shutDown() {
    
    providerCleanUp();
    super.shutDown();
  }

  @Override
  public Provider getProvider() {
    return provider;
  }
  
}