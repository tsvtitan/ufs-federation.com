package ufsic.connections;

import java.util.Properties;
import ufsic.core.CoreObject;

public class Connection extends CoreObject {
  
  private final Properties properties = new Properties();
  
  public Connection() {
    super();  
  }
  
  public Connection(Properties properties) {
    this();
    this.properties.putAll(properties);
  }
  
  public Properties getProperties() {
    
    return this.properties;
  }

  public boolean isConnected() {
    
    return false;
  }
          
  public boolean connect() {
    
    return isConnected();
  }
  
  public void disconnect() {
    
  }
  
}
