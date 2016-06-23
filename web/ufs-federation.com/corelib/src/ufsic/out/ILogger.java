package ufsic.out;

public interface ILogger {

  public void logInfo(Object obj);
  public void logError(Object obj);
  public void logWarn(Object obj);
  public void logException(Exception e);
  
}