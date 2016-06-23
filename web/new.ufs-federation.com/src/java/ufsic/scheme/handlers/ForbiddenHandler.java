package ufsic.scheme.handlers;

import java.io.IOException;
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
    if (ret) {
      try {
        getPath().getResponse().setStatus(403);
        //getPath().getResponse().sendError(HttpServletResponse.SC_FORBIDDEN);
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
}
