package ufsic.controllers;

import java.lang.reflect.Constructor;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import ufsic.core.CoreObject;
import ufsic.gates.MessageGate;
import ufsic.out.Logger;
import ufsic.out.Echo;
import ufsic.providers.Provider;
import ufsic.utils.Utils;

public class Controller extends CoreObject {

  protected final Provider provider;
  protected final HttpServlet servlet;
  protected final HttpServletRequest request;
  protected final HttpServletResponse response;
  protected final Options options;
  protected final MessageGate messageGate;
  
  public Controller(Logger logger, Echo echo, Provider provider, HttpServlet servlet, 
                     HttpServletRequest request, HttpServletResponse response, Options options,
                     MessageGate messageGate) {
    
    super(logger,echo);    
    this.provider = provider;
    this.servlet = servlet;
    this.request = request;
    this.response = response;
    this.options = options;
    this.messageGate = messageGate;
  }
  
  public static Controller run(String className, Logger logger, Echo echo, Provider provider, HttpServlet servlet, 
                               HttpServletRequest request, HttpServletResponse response, Options options,
                               MessageGate messageGate) {
    
    Controller ret = null;
    
    String clsName = Controller.class.getCanonicalName();
    if (className!=null) {
      clsName = className;
    }
    
    try {
      Class cls = Controller.class.getClassLoader().loadClass(clsName);
      if (Utils.isNotNull(cls)) {

        Constructor con = cls.getConstructor(Logger.class,Echo.class,Provider.class,HttpServlet.class,
                                             HttpServletRequest.class,HttpServletResponse.class,Options.class,
                                             MessageGate.class);
        if (Utils.isNotNull(con)) {

          ret = (Controller)con.newInstance(logger,echo,provider,servlet,request,response,options,messageGate);
          if (Utils.isNotNull(ret)) {

            ret.execute();
          }
        }
      }
    } catch (Exception e) {
      if (Utils.isNotNull(logger)) { logger.writeException(e); }
    }
    
    return ret;
  }
 
  public Provider getProvider() {
    return provider;
  }

  public HttpServletRequest getRequest() {
    return request;
  }

  public HttpServletResponse getResponse() {
    return response;
  }

  public HttpServlet getServlet() {
    return servlet;
  }
  
  public Options getOptions() {
    return options;
  }
  
  public MessageGate getMessageGate() {
    return messageGate;
  }
  
  protected void beforeExecute() {
  }
  
  protected void onExecute() {
  }
  
  protected void afterExecute() {
  }
  
  protected void execute() {

    beforeExecute();  
    try {
      onExecute();
    } finally {
      afterExecute();
    }
    
  }
  
}