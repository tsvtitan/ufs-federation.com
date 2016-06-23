package ufsic.applications;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public interface IServletApplication extends IDatabaseApplication {
  
  public HttpServletRequest getRequest();
  public HttpServletResponse getResponse();
  
}
