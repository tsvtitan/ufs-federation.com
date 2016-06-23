package ufsic.applications;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import ufsic.out.Echo;

public class MobileGate extends SchemeServletApplication {

  public MobileGate(Echo echo, HttpServletRequest request, HttpServletResponse response) {
    super(echo, request, response);
  }
  
}
