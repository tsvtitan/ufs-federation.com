package ufsic.utils;

import java.util.Collections;
import java.util.Enumeration;

import java.util.LinkedHashSet;
import java.util.Properties;
import java.util.Set;
 
public class LinkedProperties extends Properties {

  private final LinkedHashSet<String> keys = new LinkedHashSet<>();

  @Override
  public Enumeration<?> propertyNames() {
    return Collections.enumeration(keys);
  }

  @Override
  public Set<String> stringPropertyNames() {
    return keys;
  }
  
  @Override
  public Object put(Object key, Object value) {
    if (Utils.isNotNull(key)) {
      keys.add(key.toString());
    } else {
      keys.add(null);
    }
    return super.put(key, value);
  }

  @Override
  public synchronized Object remove(Object key) {
    if (Utils.isNotNull(key)) {
      keys.remove(key.toString());
    } else {
      keys.remove(null);
    }
    return super.remove(key);
  }

  @Override
  public synchronized void clear() {
    keys.clear();
    super.clear();
  }
}
