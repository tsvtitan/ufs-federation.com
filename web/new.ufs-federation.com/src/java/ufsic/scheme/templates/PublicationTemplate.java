package ufsic.scheme.templates;

import java.util.Map;
import ufsic.providers.Record;
import ufsic.scheme.Publication;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class PublicationTemplate extends Template {

  public PublicationTemplate(SchemeTable table, Record record) {
    super(table, record);
  }
  
  @Override
  public void process(Map<String, Object> vars) {
    
    super.process(vars);
    
    Publication publication = getScheme().getPublication();
    if (isNotNull(publication)) {
      vars.put("publication",publication);
    }
  }
  
}
