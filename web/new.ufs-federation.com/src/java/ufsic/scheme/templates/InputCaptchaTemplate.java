package ufsic.scheme.templates;

import ufsic.providers.Record;
import ufsic.contexts.IVarContext;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class InputCaptchaTemplate extends Template {

  public InputCaptchaTemplate(SchemeTable table, Record record) {
    super(table, record);
  }
  
  @Override
  public void process(IVarContext context) {
    
    getScheme().getLogger().setShowStack(true);
    
    
    //context.setGlobalVar("testGlobal",getScheme().getPath().getPATH()); 
    context.setGlobalVar("testLocal","HELLO");
    
/*    Map<String,Object> map = context.getLocalVars();
    if (map.size()>0) {
      
    } */
      
  }
  
}
