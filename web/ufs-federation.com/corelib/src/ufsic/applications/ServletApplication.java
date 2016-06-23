package ufsic.applications;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import ufsic.out.Echo;
import ufsic.out.Logger;

public class ServletApplication extends DatabaseApplication implements IServletApplication {
 
  private HttpServletRequest request = null; 
  private HttpServletResponse response = null;
  private final String dummy;
  
  public ServletApplication(Echo echo, HttpServletRequest request, HttpServletResponse response) {
    
    super(null,echo);
    this.request = request;
    this.response = response;
    this.dummy = request.getParameter(""); // ??? There aren't parameters without this call
  }

  @Override
  protected String getOptionsName() {

    String ret = super.getOptionsName();
    if (isNotNull(ret) && isNotNull(request)) {
      String servletName = request.getServletContext().getServletContextName();
      if (isNotNull(servletName) && !servletName.trim().equals("")) {
        String name = String.format("%s.%s",ret,servletName);
        String s = contextLookup(name,ret);
        if (isNotNull(s)) {
          ret = s;
        }
      }
    }
    return ret;
  }

  @Override
  public HttpServletRequest getRequest() {
    return request;
  }

  @Override
  public HttpServletResponse getResponse() {
    return response;
  }
  
  @Override
  public void reloadOptions() {  
    
    super.reloadOptions();
    
    Logger logger = getLogger();
    if (isNotNull(logger)) {
      
      String output = getOption("Logger.Output","system").toLowerCase();
      switch (output) {
        case "browser": {
          logger.setOut(getEcho());
          break;
        }
        default:
        case "system": {
          logger.setOut(logger.getOut());
          break;
        }
      }
    }
  }
  
  public void run() {

    response.setHeader("Server",getOption("Server.Name",this.getClass().getSimpleName()));
  }
  
  

}
