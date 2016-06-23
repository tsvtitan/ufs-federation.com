package ufsic.scheme.handlers;

import ufsic.scheme.Comm;
import ufsic.scheme.Path;

public class LinkHandler extends Handler {

  public LinkHandler(Path path) {
    super(path);
  }
  
  @Override
  public boolean process(Comm comm) {
    
    return getPath().redirectToLink();
  }
  
  
}
