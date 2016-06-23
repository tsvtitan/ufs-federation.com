package ufsic.gates;

import java.util.Properties;
import ufsic.core.ICoreObject;
import ufsic.utils.Utils;

public class Connection implements ICoreObject {
  
  private Properties properties = new Properties();
  
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
  
  @Override
  public boolean isNull(Object obj) {
    return Utils.isNull(obj);
  }
  
  @Override
  public boolean isNotNull(Object obj) {
    return Utils.isNotNull(obj);
  }
  
}
