package ufsic.scheme.handlers;

import ufsic.scheme.Comm;
import ufsic.scheme.Handler;
import ufsic.scheme.Path;
import ufsic.utils.Utils;

public class HtmlHandler extends Handler {

  public HtmlHandler(Path path) {
    super(path);
  }
  
  public static String getContentType() {
    
    return "text/html";
  }
  
  protected void setHeaders() {
    
    Path path = getPath();
    
    path.getResponse().setCharacterEncoding(Utils.getCharset().name());
    path.setContentHeaders(getContentType(),getEcho().getBufStream().size());
  }
  
  protected String getHtml() {
   
    return null;
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = false;
    String s = getHtml();
    if (!isEmpty(s)) {
      ret = getEcho().write(s);
      if (ret) {
        setHeaders();
      }
    }
    return ret;
  }
  
}
