package ufsic.scheme;

import java.lang.reflect.Constructor;
import java.util.ArrayList;
import ufsic.out.Echo;
import ufsic.out.ILogger;
import ufsic.out.Logger;
import ufsic.providers.Dataset;
import ufsic.providers.FieldNames;
import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Provider;
import ufsic.providers.Record;

public class SchemeTable<T extends SchemeRecord> extends Dataset<T> implements ILogger {
  
  private Scheme scheme;
  private Provider provider;
  private Echo echo;
  protected String name;
  public final static String ViewPrefix = "V_";

  public SchemeTable(Scheme scheme, String name) {
    super();
    this.scheme = scheme;
    if (isNotNull(scheme)) {
      this.provider = scheme.getProvider();
      this.echo = scheme.getEcho();
    } else {
      this.provider = null;
      this.echo = null;
    }
    this.name = name;
  }

  public SchemeTable(Scheme scheme) {
    super();
    this.scheme = scheme;
    if (isNotNull(scheme)) {
      this.provider = scheme.getProvider();
      this.echo = scheme.getEcho();
    } else {
      this.provider = null;
      this.echo = null;
    }
    this.name = "";
  }
  
  public SchemeTable(Provider provider, String name) {
    super();
    this.scheme = null;
    this.provider = provider;
    this.echo = null;
    this.name = name;
  }
  
  public SchemeTable(String name) {
    super();
    this.scheme = null;
    this.provider = null;
    this.echo = null;
    this.name = name;
  }
  
  public SchemeTable() {
    super();
    this.scheme = null;
    this.provider = null;
    this.echo = null;
    this.name = "";
  }

  @Override
  public void copyFrom(Dataset source) {
    
    super.copyFrom(source);
    if (isNotNull(source) && (source instanceof SchemeTable)) {
      
      SchemeTable table = (SchemeTable)source;
      this.scheme = table.scheme;
      this.provider = table.provider;
      this.echo = table.echo;
      this.name = table.name;
    }
  }

  public String getName() {
    
    return this.name;
  }
  
  public void setName(String name) {
    
    this.name = name;
  }
  
  public String getViewName() {
    
    return String.format("%s%s",ViewPrefix,name);
  }

  @Override
  public Class getRecordClass() {
    
    return SchemeRecord.class;
  }
  
  public Class getRecordClassByRecord(Record record) {
    
    return null;
  }
  
  private T newRecord(Constructor con, Class cls, Record r) {
    
    T ret = null;
    if (isNotNull(cls)) {
      try {
        if (isNull(con)) {
          con = cls.getConstructor(SchemeTable.class,Record.class);
        }
        ret = (T)con.newInstance(this,r);
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  public boolean open(FieldNames fieldNames, Filter filter, Orders orders) {
    
    boolean ret = false;
    
    this.clear();

    Provider p = getProvider();
    
    if (isNotNull(p)) {
      
      Dataset<Record> ds = p.select(getViewName(),fieldNames,filter,orders);
      if (isNotNull(ds)) {

        Class cls = getRecordClass();
        if (isNotNull(cls)) {

          try {

            Constructor con = cls.getConstructor(SchemeTable.class,Record.class);
            if (isNotNull(con)) {

              for (Record r: ds) {
                add(newRecord(con,cls,r));
              }
            } 
          } catch (Exception e) {
            logException(e);
          }
          
        } else {

          for (Record r: ds) {

            cls = getRecordClassByRecord(r);
            if (isNotNull(cls)) {
              
              add(newRecord(null,cls,r));
            }
          }
        }
        ret = !isEmpty();
      }
    }
    return ret;
  }

  public boolean open(FieldNames fieldNames, Filter filter) {
    
    return open(fieldNames,filter,null);
  }
  
  public boolean open(FieldNames fieldNames, Orders orders) {
    
    return open(fieldNames,null,orders);
  }
  
  public boolean open(Filter filter, Orders orders) {
    
    return open(null,filter,orders);
  }

  public boolean open(Filter filter) {
    
    return open(null,filter,null);
  }

  public boolean open(Orders orders) {
    
    return open(null,null,orders);
  }
  
  public boolean select(Record record, Filter filter) {
    
    boolean ret = false;
    
    Provider p = getProvider();
    
    if (isNotNull(p) && isNotNull(record)) {
      
      Record r = p.first(getViewName(),filter);
      if (isNotNull(r)) {
        record.copyFrom(r);
        ret = true;
      }
    }
    return ret;
  }
  
  public T first(FieldNames fieldNames, Filter filter, Orders orders) {
    
    T ret = null;
    
    Provider p = getProvider();
    
    if (isNotNull(p)) {
      Record r = p.first(getViewName(),fieldNames,filter,orders);
      if (isNotNull(r)) {
        Class cls = getRecordClassByRecord(r);
        if (isNull(cls)) {
          cls = getRecordClass();
        }
        if (isNotNull(cls)) {
          ret = newRecord(null,cls,r);
        }
      }
    }
    return ret;
  }
  
  public T first(FieldNames fieldNames, Filter filter) {
    
    return first(fieldNames,filter,null);
  }
  
  public T first(Filter filter, Orders orders) {
    
    return first(null,filter,orders);
  }
  
  public T first(Filter filter) {
    
    return first(null,filter,null);
  }
  
  public boolean insert(Record record) {
    
    boolean ret = false;
    Provider p = getProvider();
    if (isNotNull(p)) {
      ret = p.insert(name,record);
    }
    return ret;
  }
  
  public boolean insert() {
    
    boolean ret = false;
    Provider p = getProvider();
    if (isNotNull(p) && size()>0) {
      ret = true;
      for (Record r: this) {
        ret = ret && p.insert(name,r);
      }
    }
    return ret;
  }
  
  public boolean update(Record record, Filter filter) {
    
    boolean ret = false;
    Provider p = getProvider();
    if (isNotNull(p)) {
      ret = p.update(name,record,filter);
    }
    return ret;
  }

  public Scheme getScheme() {
    return scheme;
  }
  
  public void setScheme(Scheme scheme) {
    this.scheme = scheme;
    if (isNotNull(scheme)) {
      this.provider = scheme.getProvider();
      this.echo = scheme.getEcho();
    }
  }
  
  public Path getPath() {
    Path ret = null;
    if (isNotNull(scheme)) {
      ret = scheme.getPath();
    }
    return ret;
  }

  public Provider getProvider() {
    
    Provider ret = null;
    if (isNotNull(provider)) {
      ret = provider;
    } else if (isNotNull(scheme)) {
      ret = scheme.getProvider();
    }
    return ret;
  }
  
  public void setProvider(Provider provider) {
    
    this.provider = provider;
  }
  

  public Echo getEcho() {
    return echo;
  }
  
  public Logger getLogger() {
    
    Logger ret = null;
    if (isNotNull(scheme)) {
      ret = scheme.getLogger();
    }
    Provider p = getProvider();
    if (isNull(ret) && isNotNull(p)) {
      ret = p.getLogger();
    }
    return ret;
  }

  @Override
  public void logInfo(Object obj) {
    
    Logger logger = getLogger();
    if (isNotNull(logger)) {
      logger.writeInfo(obj.toString());
    }
  }
  
  @Override
  public void logError(Object obj) {
    
    Logger logger = getLogger();
    if (isNotNull(logger)) {
      logger.writeError(obj.toString());
    }
  }
  
  @Override
  public void logWarn(Object obj) {
    
    Logger logger = getLogger();
    if (isNotNull(logger)) {
      logger.writeError(obj.toString());
    }
  }
  
  @Override
  public void logException(Exception e) {
  
    Logger logger = getLogger();
    if (isNotNull(logger)) {
      logger.writeException(e);
    }
  }

}
