package ufsic.core;

import java.util.Properties;
import javax.naming.InitialContext;
import ufsic.out.Echo;
import ufsic.out.ILogger;
import ufsic.out.Logger;
import ufsic.utils.Utils;

public class CoreObject implements ICoreObject, ILogger {

  private Logger logger;
  private Echo echo;
  
  public enum LogLevel {
    INFO, ERROR, WARN;
  }

  public CoreObject(Logger logger, Echo echo) {
    
    this.logger = logger;
    this.echo = echo;
  }
  
  public CoreObject(Logger logger) {
    
    this(logger,null);
  }

  public CoreObject(Echo echo) {
    
    this(null,echo);
  }
  
  public CoreObject() {
    
    this(null,null);
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
  
  public void setLogger(Logger logger) {
    this.logger = logger;
  }

  public void setEcho(Echo echo) {
    this.echo = echo;
  }
  
  public void out(Object obj, boolean newLine, Object... args) {
    
    if (isNotNull(obj) && isNotNull(echo)) {
      echo.write(obj.toString(),newLine,args);
    }
  }
  
  public void out(Object obj, Object... args) {
    
    out(obj,true,args);
  }
  
  public void out(Object obj, boolean newLine) {
   
    out(obj,newLine,new Object[]{});
  }  
  
  public void out(Object obj) {
   
    out(obj,true);
  }
  
  public void log(Object obj, LogLevel level) {
    
    Logger l = getLogger();
    if (isNotNull(obj) && isNotNull(l)) {
      switch (level) {
        case INFO: { l.writeInfo(obj.toString()); break; }
        case ERROR: { l.writeError(obj.toString()); break; }
        case WARN: { l.writeWarn(obj.toString()); break; }
        default: { l.write(obj.toString()); break; }
      } 
    }
  }
  
  @Override
  public void logInfo(Object obj) {
    
    log(obj,LogLevel.INFO);
  }
  
  @Override
  public void logError(Object obj) {
    
    log(obj,LogLevel.ERROR);
  }
  
  @Override
  public void logWarn(Object obj) {
    
    log(obj,LogLevel.WARN);
  }

  @Override
  public void logException(Exception e) {
    
    if (isNotNull(e) && isNotNull(logger)) {
      logger.writeException(e);
    }
  }
  
  @Override
  public String toString() {
    
    return this.getClass().getName();
  }
  
  public Logger getLogger() {
    return logger;
  }

  public Echo getEcho() {
    return echo;
  }

  public Object contextLookup(String name, Properties props) {
    
    Object ret = null;
    try {
      InitialContext ctx;
      if (isNotNull(props)) {
        ctx = new InitialContext(props);
      } else {
        ctx = new InitialContext();
      }
      ret = ctx.lookup(name);
    } catch (Exception e) {
      //logException(e);
    }
    return ret;
  }
  
  public Object contextLookup(String name) {
    
    return contextLookup(name,(Properties)null);
  }

  public String contextLookup(String name, String def) {
    
    String ret = def;
    Object obj = contextLookup(name);
    if (isNotNull(obj)) {
      ret = obj.toString();
    }
    return ret;
  }
  
  public boolean contextExists(String name) {
    
    Object obj = contextLookup(name);
    return isNotNull(obj);
  }
  
}