package ufsic.applications;

import java.io.ByteArrayInputStream;
import java.util.Properties;
import ufsic.core.CoreObject;

import ufsic.out.Echo;
import ufsic.out.Logger;
import ufsic.utils.Location;

public class Options extends CoreObject {

  protected String jndiName;
  protected final Properties properties = new Properties();

  public Options(Echo echo, Logger logger, String jndiName) {
    
    super(logger,echo);
    this.jndiName = jndiName;
  }
  
  public Options(String jndiName) {
    
    this(null,null,jndiName);
  }

  public Options() {
    
    this(null,null,null);
  }
  
  private void load(Properties props) {
    
    if (isNotNull(props) && !props.isEmpty()) {
      
      String ident = this.getClass().getSimpleName();
        
      for (String name: props.stringPropertyNames()) {

        String n = name.substring(0,ident.length());
        if (ident.equalsIgnoreCase(n)) {

          String v = props.getProperty(name,null);
          if (isNotNull(v)) {
            
            Properties temp = new Properties();
            
            Object obj = contextLookup(v);
            if (isNotNull(obj) && (obj instanceof Properties)) {
              
              temp.putAll((Properties)obj);
              
            } else {
              
              Location loc = new Location(v);
              if (loc.exists()) {
                try {
                  temp.load(new ByteArrayInputStream(loc.getBytes()));
                } catch (Exception e) {
                  logException(e);
                }
              }
              
            }
            properties.putAll(temp);
          }

        } else {
          properties.put(name,props.getProperty(name));
        }
      }
    }
  }
  
  public void load(String jndiName) {

    Object obj = contextLookup(jndiName);
    if (isNotNull(obj) && (obj instanceof Properties)) {
      properties.clear();
      load((Properties)obj);
      //properties.putAll((Properties)obj);
      this.jndiName = jndiName;
    }
  } 
  
  public void reload() {
    load(jndiName);
  }
  
  public String getProperty(String name, String def) {
    
    String ret = def;
    try {
      if (properties.containsKey(name)) {
        ret = properties.getProperty(name,def);
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  } 
  
  public String getProperty(String name) {
    return getProperty(name,null);
  }
  
}