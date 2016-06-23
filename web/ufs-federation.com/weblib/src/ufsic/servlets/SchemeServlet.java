package ufsic.servlets;

import java.io.IOException;
import java.lang.reflect.Constructor;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import ufsic.applications.SchemeServletApplication;
import ufsic.applications.ServletApplication;
import ufsic.out.Echo;
import ufsic.utils.Utils;

public class SchemeServlet extends HttpServlet {

  protected Class getServletApplicationClass() {
    return SchemeServletApplication.class;
  }
  
  private ServletApplication newApplication(Echo echo, HttpServletRequest request, HttpServletResponse response) throws Exception {
    
    ServletApplication ret = null;

    Class cls = getServletApplicationClass();
    if (Utils.isNull(cls)) {
      cls = SchemeServletApplication.class;
    }
    if (Utils.isNotNull(cls)) {
      try {
        Constructor con = cls.getConstructor(Echo.class,HttpServletRequest.class,HttpServletResponse.class);
        if (Utils.isNotNull(con)) {

          ret = (ServletApplication)con.newInstance(echo,request,response);
        }
      } catch (Exception e) {
        throw e;
      }
    }
    return ret;
  }
          
  protected void processRequest(HttpServletRequest request, HttpServletResponse response)
          throws ServletException, IOException {
    
    Echo echo = new Echo(response.getOutputStream());
    try {

      ServletApplication application = newApplication(echo,request,response);
      if (Utils.isNotNull(application)) {

        application.startUp();
        try {
          application.run();
        } finally {
          application.shutDown();
        }
      }
      
    } catch (Exception e) {  
      echo.write(e.getMessage());
    } finally {      
      echo.flush();
    }
  }

  // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
  /**
   * Handles the HTTP <code>GET</code> method.
   *
   * @param request servlet request
   * @param response servlet response
   * @throws ServletException if a servlet-specific error occurs
   * @throws IOException if an I/O error occurs
   */
  @Override
  protected void doGet(HttpServletRequest request, HttpServletResponse response)
          throws ServletException, IOException {
    processRequest(request, response);
  }

  /**
   * Handles the HTTP <code>POST</code> method.
   *
   * @param request servlet request
   * @param response servlet response
   * @throws ServletException if a servlet-specific error occurs
   * @throws IOException if an I/O error occurs
   */
  @Override
  protected void doPost(HttpServletRequest request, HttpServletResponse response)
          throws ServletException, IOException {
    processRequest(request, response);
  }

  /**
   * Returns a short description of the servlet.
   *
   * @return a String containing servlet description
   */
  @Override
  public String getServletInfo() {
    return "Short description";
  }// </editor-fold>

}
