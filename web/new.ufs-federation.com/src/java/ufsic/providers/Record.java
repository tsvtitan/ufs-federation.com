package ufsic.providers;

import java.lang.reflect.Constructor;
import java.util.ArrayList;
import java.util.Iterator;

import ufsic.core.ICoreObject;
import ufsic.utils.Utils;

public class Record extends ArrayList<Field> implements ICoreObject {

  public Record() {
    super();  
  }
  
  public Class getFieldClass() {
    return Field.class;
  }
  
  protected Field newField(String name, Value value, Class cls) {
    
    Field ret = null;
    if (isNotNull(cls)) {
      try {
        Constructor con = cls.getConstructor(String.class,Value.class);
        if (isNotNull(con)) {

          ret = (Field)con.newInstance(name,value);
        }
      } catch (Exception e) {
        //
      }
    } else {
      ret = newField(name,value,getFieldClass());
    }
    return ret;
  }
  
  public Field add(String name, Value value) {
    
    Field f = newField(name,value,null);
    super.add(f);
    return f;
  }

  public Field add(String name, Value value, Class cls) {
    
    Field f = newField(name,value,cls);
    super.add(f);
    return f;
  }
  
  public Field add(String name, Object value) {
    
    Field f = newField(name,new Value(value),null);
    super.add(f);
    return f;
  }
  
  @Override
  public boolean isNull(Object obj) {
    return Utils.isNull(obj);
  }
  
  @Override
  public boolean isNotNull(Object obj) {
    return Utils.isNotNull(obj);
  }
  
  
  public boolean getList(String name, Record record, boolean first) {
    
    boolean ret = false;
    Iterator<Field> it = iterator();
    while (it.hasNext()) {
     
      Field f = (Field)it.next();
      if (f.getName().equals(name)) {
        record.add(f);
        ret = true;
        if (first) {
          break;
        }
      }
    }
    return ret;
  }
  
  public Field findFirst(String name) {
    
    Field ret = null;
    Record r = new Record();
    boolean exists = getList(name,r,true);
    if (exists) {
      ret = r.get(0);
    }
    return ret;
  }
  
  public Field find(String name) {
    
    return findFirst(name);
  }
  

  public boolean setValue(String name, Value value) {
    
    boolean ret = false;
    Record r = new Record();
    if (getList(name,r,false)) {
      
      Iterator<Field> it = r.iterator();
      while (it.hasNext()) {

        Field fv = (Field)it.next();
        if (isNotNull(fv)) {
          fv.setValue(value);
          ret = true;
        }
      }
    }
    return ret;
  }
  
  public boolean setValue(String name, Object value) {
    return setValue(name,new Value(value));
  }
  
  public Value getValue(String name) {

    Value ret;
    Field f = findFirst(name);
    if (isNotNull(f)) {
      ret = f.getValue();
    } else {
      ret = new Value(null);
    }
    return ret;
  }
  
  public boolean exists(String name) {
    
    return getList(name,new Record(),true);
  }

  public void copyFrom(Record source) {
    
    if (isNotNull(source)) {
      
      for (Field field: source) {
        
        Field nf = newField(field.getName(),field.getValue(),field.getClass());
        nf.setNeedInsert(field.isNeedInsert());
        nf.setNeedUpdate(field.isNeedUpdate());
        add(nf);
      }
    }
  }
  
}
