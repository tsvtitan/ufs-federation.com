package ufsic.scheme.handlers;

import ufsic.out.Echo;
import ufsic.providers.Provider;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.SchemeObject;

public class Handler extends SchemeObject {
  
  private final Path path;
  private final Provider provider;
  private final Echo echo;
  
  public Handler(Path path) {

    super(path.getScheme());
    this.path = path;
    this.provider = path.getProvider();
    this.echo = path.getEcho();
  }
  
  public static String getContentType() {
    
    return "text/plain";
  }
  
  public static boolean isPathRestricted() {
    return true;
  }
  
  public static boolean needSession() {
    return true;
  }

  
  public Path getPath() {
    
    return path;
  } 

  public Provider getProvider() {
    
    return provider;
  } 
  
  public Echo getEcho() {
    
    return echo;
  }
  
  public boolean process(Comm comm) {
    
    return false;
  }
  
}
