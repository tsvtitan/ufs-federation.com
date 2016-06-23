package ufsic.providers;

import java.lang.reflect.Constructor;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.Random;

import ufsic.core.ICoreObject;
import ufsic.utils.Utils;

public class DataSet extends ArrayList<Record> implements ICoreObject {
  
  
  public Record first() {
  
    Record ret = null;
    if (size()>0) {
      ret = get(0);
    }
    return ret;
  }
  
  @Override
  public boolean isEmpty() {
    
    return size()==0;
  }
  
  @Override
  public boolean isNull(Object obj) {
    return Utils.isNull(obj);
  }
  
  @Override
  public boolean isNotNull(Object obj) {
    return Utils.isNotNull(obj);
  }

  public boolean getList(String name, Object value, DataSet dataset, boolean first) {
    
    boolean ret = false;
    
    Iterator<Record> rit = iterator();
    while (rit.hasNext()) {
     
      Record r = (Record)rit.next();
      
      Iterator<Field> fit = r.iterator();
      while (fit.hasNext()) {

        Field f = (Field)fit.next();
        if (f.getName().equals(name) && f.getValue().same(value)) {
          
          dataset.add(r);
          ret = true;
          if (first) {
            break;
          }
        }
      }
    }
    return ret;
  }
  
  public boolean getList(String name, Value value, DataSet dataset, boolean first) {
    
    return getList(name,value.getObject(),dataset,first);
  }
  
  public Record findFirst(String name, Object value) {
    
    Record ret = null;
    DataSet ds = new DataSet();
    boolean exists = getList(name,value,ds,true);
    if (exists) {
      ret = ds.get(0);
    }
    return ret;
  }

  public Record findFirst(String name, Value value) {
    
    return findFirst(name,value.getObject());
  }
  
  public Record randomRecord() {
    
    Random rn = new Random();
    int range = size();
    int index = rn.nextInt(range);
    
    return get(index);
  }
  
  public Class getRecordClass() {
    
    return Record.class;
  }
  
  public void copyFrom(DataSet source) {
    
    if (isNotNull(source)) {
      
      for (Record record: source) {
        
        try {

          Constructor con = record.getClass().getConstructor();
          if (isNotNull(con)) {
            Record nr = (Record)con.newInstance();
            nr.copyFrom(record);
            add(nr);
          } 
        } catch (Exception e) {
         // e.printStackTrace();
        }
      }
    }
  }
  
  
}
