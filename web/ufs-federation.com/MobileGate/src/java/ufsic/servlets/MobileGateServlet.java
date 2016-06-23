package ufsic.servlets;

import ufsic.applications.MobileGate;

public class MobileGateServlet extends SchemeServlet {

  @Override
  protected Class getServletApplicationClass() {
    return MobileGate.class;
  }
}
