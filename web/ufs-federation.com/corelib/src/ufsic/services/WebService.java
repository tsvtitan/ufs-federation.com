package ufsic.services;

import java.net.URI;
import java.net.URISyntaxException;

import javax.annotation.Resource;
import javax.servlet.http.HttpServletRequest;
import javax.xml.ws.WebServiceContext;
import javax.xml.ws.handler.MessageContext;

public class WebService extends Service {
  
  @Resource    
  private WebServiceContext serviceContext;
  
  /*@Override
  @PostConstruct
  public void startUp() {
    
    super.startUp();
  }

  @Override
  @PreDestroy
  public void shutDown() {
    
    super.shutDown();
  }*/
  
  protected MessageContext getMessageContext() {
    return serviceContext.getMessageContext();
  }
  
  protected HttpServletRequest getRequest() {
    return (HttpServletRequest)getMessageContext().get(MessageContext.SERVLET_REQUEST);
  }
  
  protected URI getURI() {
    
    URI ret = null;
    HttpServletRequest request = getRequest();
    if (isNotNull(request)) {
      
      String url = request.getRequestURL().toString();
      try {
        ret = new URI(url);
      } catch (URISyntaxException ex) {
        logException(ex);
      }
    }
    return ret;
  }
}
