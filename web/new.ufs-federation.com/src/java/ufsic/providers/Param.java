package ufsic.providers;

import java.sql.Types;
import ufsic.utils.Utils;

public class Param {

  public enum Direction {
    
    IN, OUT, IN_OUT;
    
    public static String asString(Direction direction) {
      
      String ret = "";
      switch (direction) {
        case IN: { ret = "IN"; break; }
        case OUT: { ret = "OUT"; break; }
        case IN_OUT: { ret = "IN OUT"; break; }
        default: { break; }
      }
      return ret;
    }
  }
  
  private String name;
  private Value value;
  private Direction direction = null;
  private Integer sqlType = null;

  public Param(String name, Value value) {

    this.name = name;
    this.value = getValueIfNull(value);
  }

  public Param(String name, Value value, Direction direction) {

    this.name = name;
    this.value = getValueIfNull(value);
    this.direction = direction;
  }
  
  public Param(String name, Value value, Direction direction, Integer sqlType) {

    this.name = name;
    this.value = getValueIfNull(value);
    this.direction = direction;
    this.sqlType = sqlType;
  }
  
  private Value getValueIfNull(Value value) {
    
    Value ret = value;
    if (Utils.isNull(ret)) {
      ret = new Value(null);
    }
    return ret;
  }
  
  private boolean isClass(Object obj, Class cls) {
    
    return Utils.isClass(obj,cls);
  }
  
  public String getName() {
    return name;
  }

  public void setName(String name) {
    this.name = name;
  }
  
  public Value getValue() {
    return value;
  }

  public void setValue(Value value) {
    this.value = getValueIfNull(value);
  }
  
  public void setValue(Object value) {
    this.value = new Value(value);
  }
  
  public void setDirection(Direction direction) {
    this.direction = direction;
  }
  
  public Direction getDirection() {
    return direction;
  }
  
  public Integer getSqlType() {
    return sqlType;
  }
  
  public void setSqlType(Integer sqlType) {
    this.sqlType = sqlType;
  }
  
}