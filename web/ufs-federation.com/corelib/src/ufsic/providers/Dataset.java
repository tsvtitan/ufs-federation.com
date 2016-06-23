package ufsic.providers;

import java.lang.reflect.Constructor;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.Random;

import ufsic.core.ICoreObject;
import ufsic.utils.Utils;

public class Dataset<T extends Record> extends ArrayList<T> implements ICoreObject {
  
  
  public T first() {
  
    T ret = null;
    if (size()>0) {
      ret = get(0);
    }
    return ret;
  }
  
  public T last() {
  
    T ret = null;
    if (size()>0) {
      ret = get(size()-1);
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
  
  @Override
  public boolean isEmpty(Object obj) {
    return Utils.isEmpty(obj);
  }

  public boolean getList(String name, Object value, Dataset dataset, boolean first) {
    
    boolean ret = false;
    
    Iterator<T> rit = iterator();
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
  
  public boolean getList(String name, Value value, Dataset dataset, boolean first) {
    
    return getList(name,value.getObject(),dataset,first);
  }
  
  public T findFirst(String name, Object value) {
    
    T ret = null;
    Dataset<T> ds = new Dataset();
    boolean exists = getList(name,value,ds,true);
    if (exists) {
      ret = ds.get(0);
    }
    return ret;
  }

  public T findFirst(String name, Value value) {
    
    return findFirst(name,value.getObject());
  }
  
  public T randomRecord() {
    
    Random rn = new Random();
    int range = size();
    int index = rn.nextInt(range);
    
    return get(index);
  }
  
  public Class getRecordClass() {
    
    return Record.class;
  }
  
  public void copyFrom(Dataset<T> source, boolean newValues) {
    
    if (isNotNull(source)) {
      
      for (T t: source) {
        
        try {

          Constructor con = t.getClass().getConstructor();
          if (isNotNull(con)) {
            T nt = (T)con.newInstance();
            nt.copyFrom(t,newValues);
            add(nt);
          } 
        } catch (Exception e) {
         // e.printStackTrace();
        }
      }
    }
  }
  
  public void copyFrom(Dataset<T> source) {
    copyFrom(source,false);
  }
  
}
