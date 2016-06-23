package ufsic.scheme;

import ufsic.providers.Provider;

public class Handler extends SchemeObject {
  
  private final Path path;
  private final Provider provider;
  
  public Handler(Path path) {

    super(path.getScheme());
    this.path = path;
    this.provider = path.getProvider();
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
  
  public boolean process(Comm comm) {
    return false;
  }
 
}
