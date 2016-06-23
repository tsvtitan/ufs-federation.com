package ufsic.scheme.thymeleaf;

import javax.servlet.ServletContext;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.thymeleaf.context.WebContext;

public class SchemeWebContext extends WebContext {

  public SchemeWebContext(HttpServletRequest request, HttpServletResponse response, ServletContext servletContext) {
    super(request, response, servletContext);
  }
 
  
}
