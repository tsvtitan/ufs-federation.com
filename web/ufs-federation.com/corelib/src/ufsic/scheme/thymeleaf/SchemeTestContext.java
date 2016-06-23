package ufsic.scheme.thymeleaf;

import org.thymeleaf.context.AbstractContext;
import org.thymeleaf.context.IContextExecutionInfo;
import org.thymeleaf.context.VariablesMap;
import ufsic.scheme.Scheme;

public class SchemeTestContext extends AbstractContext {

  private Scheme scheme = null;
  
  public SchemeTestContext(Scheme scheme) {
 
    super();
    this.scheme = scheme;
  }

  @Override
  protected IContextExecutionInfo buildContextExecutionInfo(String templateName) {
    throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
  }
 
  
}
