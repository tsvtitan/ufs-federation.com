package ufsic.scheme.handlers;

import javax.servlet.http.HttpServletResponse;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;

public class ForbiddenHandler extends PageHandler {

  public ForbiddenHandler(Path path) {
    super(path);
  }
  
  @Override
  protected boolean needSetLastPageId() {
    return false;
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = super.process(comm);
    try {
      if (ret) {
        getPath().getResponse().setStatus(403);
      } else {
        getPath().getResponse().sendError(HttpServletResponse.SC_FORBIDDEN);
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
}
