package ufsic.scheme;

import ufsic.out.Echo;
import ufsic.out.ILogger;
import ufsic.out.Logger;
import ufsic.providers.Filter;
import ufsic.providers.Provider;
import ufsic.providers.Record;

public class SchemeRecord extends Record implements ILogger {

  private SchemeTable table;
  private Scheme scheme;
  private Provider provider;
  private Echo echo;

  public SchemeRecord() {
    super();
  }

  public SchemeRecord(Scheme scheme) {
    super();
    this.table = null;
    this.scheme = scheme;
    this.provider = scheme.getProvider();
    this.echo = scheme.getEcho();
  }
  
  public SchemeRecord(SchemeTable table) {
    
    this();
    this.table = table;
    if (isNotNull(table)) {
      this.scheme = table.getScheme();
      this.provider = table.getProvider();
      this.echo = table.getEcho();
    } else {
      this.scheme = null;
      this.provider = null;
      this.echo = null;
    }
  }
  
  public SchemeRecord(SchemeTable table, Record record) {

    this(table);
    if (isNotNull(record))  {
      this.addAll(record);
    }
  }
  
  @Override
  public void copyFrom(Record source) {
    
    super.copyFrom(source);
    if (isNotNull(source) && (source instanceof SchemeRecord)) {
      
      SchemeRecord record = (SchemeRecord)source;
      this.table = record.table;
      this.scheme = record.scheme;
      this.provider = record.provider;
      this.echo = record.echo;
      /*this.request = record.request;
      this.response = record.response;*/
    }
  }

  public Scheme getScheme() {

    return scheme;
  }

  public SchemeTable getTable() {

    return table;
  }
  
  public Provider getProvider() {

    return provider;
  }
  
  public void setProvider(Provider provider) {
    
    this.provider = provider;
  }

  public Echo getEcho() {

    return echo;
  }

  public Logger getLogger() {
  
    Logger ret = null;
    if (isNotNull(table)) {
      ret = table.getLogger();
    }
    if (isNull(ret) && isNotNull(scheme)) {
      ret = scheme.getLogger();
    }
    if (isNull(ret) && isNotNull(provider)) {
      ret = provider.getLogger();
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
 
  public Integer getRecNum() {
    
    Integer ret = null;
    if (isNotNull(table)) {
      ret = table.indexOf(this);
    }
    return ret;
  }
  
  public String getRecNum(Integer padCount) {
    
    String ret = "";
    Integer recNum = getRecNum();
    if (isNotNull(recNum)) {
      if (isNotNull(padCount)) {
        String fmt = "%0" + padCount.toString() + "d";
        ret = String.format(fmt,recNum);
      } else {
        ret = recNum.toString();
      }
    }
    return ret;
  }
 
  public boolean select(Filter filter) {

    boolean ret = false;
    if (isNotNull(table)) {
      ret = table.select(this,filter);
    }
    return ret;
  }
  
  public boolean insert() {
    
    boolean ret = false;
    if (isNotNull(table)) {
      ret = table.insert(this);
    }
    return ret;
  }
  
  public boolean update(Filter filter) {
    
    boolean ret = false;
    if (isNotNull(table)) {
      ret = table.update(this,filter);
    }
    return ret;
  }
  
  protected boolean save() {
    return false;
  }
  
}