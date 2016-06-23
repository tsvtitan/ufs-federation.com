package ufsic.scheme.templates;


import ufsic.contexts.IVarContext;
import ufsic.providers.Record;
import ufsic.scheme.Path;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class BreadCrumbTemplate extends Template {

  public BreadCrumbTemplate(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  public void process(IVarContext context) {
    
    super.process(context);
    
    Path path = getScheme().getPath();
    if (isNotNull(path)) {
      context.setLocalVar("paths",path.getParentPaths());
    }
  }
  
}
