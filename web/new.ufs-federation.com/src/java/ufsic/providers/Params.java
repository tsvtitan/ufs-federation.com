package ufsic.providers;

import java.util.ArrayList;
import ufsic.providers.Param.Direction;
import ufsic.utils.Utils;

public class Params extends ArrayList<Param> {

  public Params() {
    super();
  }

  public Params(String name) {
    super();
    Param p = new Param(name,null);
    this.add(p);
  }
  
  public Params(String name, Value value) {
    super();
    Param p = new Param(name,value);
    this.add(p);
  }

  public Params(String name, Object value) {
    super();
    Param p = new Param(name,new Value(value));
    this.add(p);
  }

  public Params Add(String name, Value value, Direction direction) {
    
    Param p = new Param(name,value,direction);
    this.add(p);
    return this;
  }
  
  
  public Params Add(String name, Value value, Direction direction, Integer sqlType) {
    
    Param p = new Param(name,value,direction);
    p.setSqlType(sqlType);
    this.add(p);
    return this;
  }
  
  public Params AddIn(String name, Value value) {
    
    return Add(name,value,Direction.IN);
  }

  public Params AddIn(String name, Object value) {
    
    return Add(name,new Value(value),Direction.IN);
  }

  public Params AddInNull(String name) {
    
    return AddIn(name,null);
  }
  
  public Params Add(String name) {
    
    return Add(name,new Value(null),null,null);
  }
  
  public Params Add(String name, Value value) {
    
    return Add(name,value,null,null);
  }

  public Params AddNull(String name) {
    
    return Add(name,new Value(null),null,null);
  }
  
  public Params Add(String name, Object value) {
    
    return Add(name,new Value(value));
  }

  public Params AddOut(String name, Integer sqlType) {
    
    return Add(name,null,Direction.OUT,sqlType);
  }

  public Params AddOut(String name) {
    
    return Add(name,null,Direction.OUT);
  }
  
  public Params AddInOut(String name, Value value, Integer sqlType) {
    
    return Add(name,value,Direction.IN_OUT,sqlType);
  }

  public Params AddInOut(String name, Value value) {
    
    return Add(name,value,Direction.IN_OUT);
  }
  
  public Params AddInOut(String name, Object value, Integer sqlType) {
    
    return Add(name,new Value(value),Direction.IN_OUT,sqlType);
  }

  public Params AddInOut(String name, Object value) {
    
    return Add(name,new Value(value),Direction.IN_OUT);
  }
  
  public void Add(Param p) {
  
    super.add(p);
  }
  
  public Param find(String name) {
    
    Param ret = null;
    for (Param p: this) {
      if (p.getName().equals(name)) {
        ret = p;
      }
    }
    return ret;
  }
  
  public Value getValue(String name) {
    
    Value ret;
    Param p = find(name);
    if (Utils.isNotNull(p)) {
      ret = p.getValue();
    } else {
      ret = new Value(null);
    }
    return ret;
  }
  
  public void copyFrom(Record record) {
    
    if (Utils.isNotNull(record)) {
      
      for (Field f: record) {
        
        String n = f.getName();
        Param p = find(n);
        if (Utils.isNull(p)) {
          p = new Param(n,f.getValue(),Param.Direction.IN);
          this.add(p);
        }
      }
    }
  }
  
}