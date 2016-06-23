package ufsic.controllers;

import java.util.Properties;
import javax.naming.InitialContext;

import ufsic.core.CoreObject;
import ufsic.out.Echo;
import ufsic.out.Logger;

public class Options extends CoreObject {

  protected final String jndiName;
  protected final Properties properties = new Properties();

  public Options(Echo echo, Logger logger, String jndiName) {
    
    super(logger,echo);
    this.jndiName = jndiName;
    loadProperties();
  }
  
  public Options(String jndiName) {
    
    this(null,null,jndiName);
  }
    
  private void loadProperties() {
    
    try {
      InitialContext initContext = new InitialContext();
      Properties props = (Properties) initContext.lookup(jndiName);
      if (isNotNull(props)) {
        properties.putAll(props);
      }
    } catch (Exception e) {
      logException(e);
    }
  } 
  
  public String getProperty(String name, String def) {
    
    String ret;
    try {
      ret = properties.getProperty(name,def);
    } catch (Exception e) {
      ret = def;
    }
    return ret.toString();
  } 
  
  public String getProperty(String name) {
    return getProperty(name,null);
  }
  
}