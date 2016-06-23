package ufsic.scheme.templates;

import ufsic.providers.Record;
import ufsic.contexts.IVarContext;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class TestTemplate extends Template {

  public TestTemplate(SchemeTable table, Record record) {
    super(table, record);
  }
  
  @Override
  public void process(IVarContext context) {
    
    //Object obj = context.getVar("testLocal");
    //if (isNull(obj)) {
    context.setLocalVar("testLocal","HELLO2");
    //}
  }
  
}
