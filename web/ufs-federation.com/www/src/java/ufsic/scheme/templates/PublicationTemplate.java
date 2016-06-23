package ufsic.scheme.templates;

import ufsic.contexts.IVarContext;
import ufsic.providers.Record;
import ufsic.scheme.Publication;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class PublicationTemplate extends Template {

  public PublicationTemplate(SchemeTable table, Record record) {
    super(table, record);
  }
  
  @Override
  public void process(IVarContext context) {
    
    super.process(context);
    
    if (!context.localExists("publication")) {
      Publication publication = getScheme().getPublication();
      if (isNotNull(publication)) {
        context.setLocalVar("publication",publication);
      }
    }
  }

}
