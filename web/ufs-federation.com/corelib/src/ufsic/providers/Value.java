package ufsic.providers;

import java.io.StringReader;
import java.sql.Date;
import java.sql.Timestamp;
import java.util.Properties;
import ufsic.utils.Utils;

public class Value {
  
  private Object object;

  public Value(Object object) {
    super();
    this.object = object;
  }
  
  public static Value getValueBy(Object value) {
    
    return Utils.isNotNull(value) && (value instanceof Value)?(Value)value:new Value(value);
  }

  public Object getObject() {
    return object;
  }

  public void setObject(Object object) {
    this.object = object;
  }

  public void clear() {
    setObject(null);
  }
  
  public boolean isNull() {
    return Utils.isNull(object);
  }
  
  public boolean isNotNull() {
    return Utils.isNotNull(object);
  }
  
  public boolean isInteger() {
    return Utils.isInteger(object);
  }
  
  public boolean isDouble() {
    return Utils.isDouble(object);
  }
  
  public boolean isEmpty() {
    return Utils.isEmpty(object);
  }
  
  public String asString() {
    
    String ret = "";
    try {
      if (isNotNull()) {
        ret = object.toString();
      }
    } catch (Exception e) {
      //
    }
    return ret;
  }
  
  public int asInteger() {
    return (isNull())?0:Integer.valueOf(asString());
  }
  
  public boolean asBoolean() {
    return (isNull())?false:(asInteger()>0);
  }

  public float asFloat() {
    return (isNull())?0:Float.valueOf(asString());
  }

  public double asDouble() {
    return (isNull())?0:Double.valueOf(asString());
  }
  
  public byte[] asBytes() {
    
    byte[] ret = new byte[] {};
    if (isNotNull()) {
      if (object instanceof byte[]) {
        ret = (byte[])object;
      } else {
        ret = asString().getBytes();
      }
    }
    return ret;
  }

  public Date asDate() {
    
    return Date.valueOf(asString());
  }
  
  public Timestamp asTimestamp() {
    
    return Timestamp.valueOf(asString());
  }
  
  public Timestamp addMilliSeconds(int milliSeconds) {
    
    Timestamp ret = null;
    if (isNotNull()) {
      ret = Utils.addMilliSeconds(asTimestamp(),milliSeconds);
    }
    return ret;
  }

  public Timestamp addSeconds(int seconds) {
    
    Timestamp ret = null;
    if (isNotNull()) {
      ret = Utils.addSeconds(asTimestamp(),seconds);
    }
    return ret;
  }
  
  public Timestamp addMinutes(int minutes) {
    
    Timestamp ret = null;
    if (isNotNull()) {
      ret = Utils.addMinutes(asTimestamp(),minutes);
    }
    return ret;
  }
  
  public Timestamp addHours(int hours) {
    
    Timestamp ret = null;
    if (isNotNull()) {
      ret = Utils.addHours(asTimestamp(),hours);
    }
    return ret;
  }
  
  public Timestamp addDays(int days) {
    
    Timestamp ret = null;
    if (isNotNull()) {
      ret = Utils.addDays(asTimestamp(),days);
    }
    return ret;
  }
  
  public Timestamp addMonths(int months) {
    
    Timestamp ret = null;
    if (isNotNull()) {
      ret = Utils.addMonths(asTimestamp(),months);
    }
    return ret;
  }

  public Timestamp addYears(int years) {
    
    Timestamp ret = null;
    if (isNotNull()) {
      ret = Utils.addYears(asTimestamp(),years);
    }
    return ret;
  }
  
  public Object asObject() {
    
    return getObject();
  }
  
  public Properties asProperties() {

    Properties ret = new Properties();
    if (isNotNull()) {
      try {
        ret.load(new StringReader(asString()));
      } catch (Exception e) {
        //
      }
    }
    return ret;
  }

  
  public boolean same(Value value) {
    
    Object o2 = (Utils.isNull(value))?null:value.asObject();
    return Utils.equals(object,o2);
  }

  public boolean same(Object value) {
    return same(new Value(value));
  }

  public boolean sameClass(Object obj, Class cls) {
    return Utils.isClass(obj,cls);
  }

  public boolean sameClass(Class cls) {
    return sameClass(object,cls);
  }

  public int length() {
    
    return asString().length();
  }
  
}
