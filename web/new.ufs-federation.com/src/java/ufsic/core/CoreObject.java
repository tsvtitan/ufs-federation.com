package ufsic.core;

import ufsic.out.Echo;
import ufsic.out.ILogger;
import ufsic.out.Logger;
import ufsic.utils.Utils;

public class CoreObject implements ICoreObject, ILogger {

  protected Logger logger;
  protected Echo echo;
  
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
   
    out(obj,newLine);
  }  
  
  public void out(Object obj) {
   
    out(obj,true);
  }
  
  public void log(Object obj, LogLevel level) {
    
    if (isNotNull(obj) && isNotNull(logger)) {
      switch (level) {
        case INFO: { logger.writeInfo(obj.toString()); break; }
        case ERROR: { logger.writeError(obj.toString()); break; }
        case WARN: { logger.writeWarn(obj.toString()); break; }
        default: { logger.write(obj.toString()); break; }
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
   
}