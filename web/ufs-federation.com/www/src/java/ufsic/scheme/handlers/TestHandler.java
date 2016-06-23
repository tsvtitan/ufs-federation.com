package ufsic.scheme.handlers;

import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.*;
import ufsic.scheme.messages.*;
import ufsic.scheme.patterns.*;

public class TestHandler extends Handler {

  public TestHandler(Path path) {
    super(path);
  }

  @Override
  public boolean process(Comm comm) {
    
    getPath().getResponse().setCharacterEncoding("UTF-8");
    getPath().getResponse().setContentType("text/html");
    
    Record r = getProvider().first("V_PAGES",new Filter("PAGE_ID","C31A3A605172A7AE1C1794B95EEF97C1"));
    if (isNotNull(r)) {
      getEcho().write("<h2>%s</h2>",r.getValue("HTML").asString());    
    } else {
      getEcho().write("null");
    }
    
    return true;
  }
 
  
  
}