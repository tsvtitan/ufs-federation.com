package ufsic.servlets;

import java.io.IOException;
import javax.ejb.EJB;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import ufsic.controllers.*;
import ufsic.gates.MessageGate;
import ufsic.out.*;
import ufsic.providers.*;

public class DefaultServlet extends HttpServlet {

  private @EJB MessageGate messageGate;
  
  protected void processRequest(HttpServletRequest request, HttpServletResponse response)
          throws ServletException, IOException {
    
    Echo echo = new Echo(response.getOutputStream());
    try {
      Logger logger = new Logger(echo);

      Options options = new Options(echo,logger,String.format("%s.Options",DefaultServlet.class.getCanonicalName()));
      
      response.setHeader("Server",options.getProperty("Server.Name","UFS IC Application Server"));
      
      Provider provider = new OracleProvider(echo,logger,options.getProperty("OracleProvider.JNDI","jdbc/work"));
      
      Controller.run(options.getProperty("Controller.Name",DefaultController.class.getCanonicalName()),
                     logger,echo,provider,this,request,response,options,messageGate);
    } finally {      
      echo.flush();
    } 
  }

  // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
  /**
   * Handles the HTTP
   * <code>GET</code> method.
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
   * Handles the HTTP
   * <code>POST</code> method.
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
