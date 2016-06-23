package ufsic.servlets;

import ufsic.applications.Website;

public class WebsiteServlet extends SchemeServlet {
  
  @Override
  protected Class getServletApplicationClass() {
    return Website.class;
  }
}
