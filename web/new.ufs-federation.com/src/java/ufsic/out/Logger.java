package ufsic.out;

import java.io.PrintStream;
import ufsic.utils.Utils;

public class Logger {
  
  private final Out out;
  private boolean showStack = false;
  
  public Logger(Out out) {
    
    this.out = out;
  }
  
  public void setShowStack(boolean showStack) {
    this.showStack = showStack;
  }

  public boolean write(String s, Object... args) {
    
    boolean ret = false;
    if (Utils.isNotNull(out)) {
      ret = out.write(s,args);  
    }
    return ret;
  }
  
  public void writeInfo(String info) {
  
    write(info);
  }
  
  public void writeError(String error) {
    
    write(error);
  }
  
  public void writeWarn(String warn) {
    
    write(warn);
  }
  
  public void writeException(Exception e) {
    
   if (Utils.isNotNull(out) && Utils.isNotNull(e)) {
      
      StackTraceElement[] elements = e.getStackTrace();
      if (elements.length>0) {
        
        boolean first = true;
        for (StackTraceElement el: elements) {
          
          String clsName = el.getClassName();
          String pkgName = getClass().getPackage().getName();
          String[] tmp = pkgName.split("\\.");
          if (tmp.length>0) {
            if (clsName.startsWith(tmp[0])) {
              if (first) {
                out.write("%s.%s at line: %d (%s) %s",
                          el.getClassName(),
                          el.getMethodName(),
                          el.getLineNumber(),
                          e.getClass().getName(),
                          e.getMessage());
                first = false;
                break;
              } else {
                /*if (showStack) {
                  out.write("%s.%s at line: %d",
                            el.getClassName(),
                            el.getMethodName(),
                            el.getLineNumber());
                } else {
                  break;
                }*/
              }
            }
          }
        }
      }
      if (showStack)
        e.printStackTrace(new PrintStream(out.getBufStream()));
    }
  }
  
}
