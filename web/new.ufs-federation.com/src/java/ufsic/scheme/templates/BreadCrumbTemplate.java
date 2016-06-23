package ufsic.scheme.templates;

import java.util.Map;
import ufsic.providers.Record;
import ufsic.scheme.Path;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class BreadCrumbTemplate extends Template {

  public BreadCrumbTemplate(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  public void process(Map<String, Object> vars) {
    
    super.process(vars);
    
    Path path = getScheme().getPath();
    if (isNotNull(path)) {
      vars.put("paths",path.getParentPaths());
    }
  }
  
}
