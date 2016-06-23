package ufsic.scheme;

import java.lang.reflect.Constructor;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import ufsic.out.Echo;
import ufsic.out.ILogger;

import ufsic.providers.DataSet;
import ufsic.providers.FieldNames;
import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Provider;
import ufsic.providers.Record;

public class SchemeTable extends DataSet implements ILogger {
  
  protected Scheme scheme;
  protected Provider provider;
  protected Echo echo;
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
  public void copyFrom(DataSet source) {
    
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
  
  public boolean open(FieldNames fieldNames, Filter filter, Orders orders) {
    
    boolean ret = false;
    
    this.clear();

    if (isNotNull(provider)) {
      DataSet ds = provider.select(getViewName(),fieldNames,filter,orders);
      if (isNotNull(ds)) {

        Class cls = getRecordClass();
        if (isNotNull(cls)) {

          try {

            Constructor con = cls.getConstructor(SchemeTable.class,Record.class);
            if (isNotNull(con)) {

              for (Record r: ds) {
                add((SchemeRecord)con.newInstance(this,r));
              }
            } 
          } catch (Exception e) {
            logException(e);
          }
        } else {

          for (Record r: ds) {

            cls = getRecordClassByRecord(r);
            if (isNotNull(cls)) {
              try {
                
                Constructor con = cls.getConstructor(SchemeTable.class,Record.class);
                if (isNotNull(con)) {
                  add((SchemeRecord)con.newInstance(this,r));
                }

              } catch (Exception e) {
                logException(e);
              }
            }
          }
        }
        ret = !isEmpty();
      }
    }
    return ret;
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
    if (isNotNull(provider) && isNotNull(record)) {
      
      Record r = provider.first(name,filter);
      if (isNotNull(r)) {
        record.copyFrom(r);
        ret = true;
      }
    }
    return ret;
  }
  
  public boolean insert(Record record) {
    
    boolean ret = false;
    if (isNotNull(provider)) {
      ret = provider.insert(name,record);
    }
    return ret;
  }
  
  public boolean update(Record record, Filter filter) {
    
    boolean ret = false;
    if (isNotNull(provider)) {
      ret = provider.update(name,record,filter);
    }
    return ret;
  }

  public Scheme getScheme() {
    return scheme;
  }

  public Provider getProvider() {
    
    return this.provider;
  }
  
  public void setProvider(Provider provider) {
    
    this.provider = provider;
  }
  

  public Echo getEcho() {
    return echo;
  }

  public HttpServletRequest getRequest() {
    
    HttpServletRequest ret = null;
    if (isNotNull(scheme)) {
      ret = scheme.getController().getRequest(); 
    }
    return ret;
  }

  public HttpServletResponse getResponse() {

    HttpServletResponse ret = null;
    if (isNotNull(scheme)) {
      ret = scheme.getController().getResponse();
    }
    return ret;
  }
  
  @Override
  public void logInfo(Object obj) {
    
    if (isNotNull(scheme)) {
      scheme.getLogger().writeInfo(obj.toString());
    }
  }
  
  @Override
  public void logError(Object obj) {
    
    if (isNotNull(scheme)) {
      scheme.getLogger().writeError(obj.toString());
    }
  }
  
  @Override
  public void logWarn(Object obj) {
    
    if (isNotNull(scheme)) {
      scheme.getLogger().writeError(obj.toString());
    }
  }
  
  @Override
  public void logException(Exception e) {
  
    if (isNotNull(scheme)) {
      scheme.getLogger().writeException(e);
    }
  }

}
