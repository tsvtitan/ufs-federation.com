package ufsic.scheme.templates;

import ufsic.contexts.IVarContext;
import ufsic.providers.Record;
import ufsic.scheme.Page;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class PromoMenuTemplate extends Template {

  public PromoMenuTemplate(SchemeTable table, Record record) {
    super(table, record);
  }
  
  @Override
  public void process(IVarContext context) {
    
    super.process(context);
    
    Page page = getScheme().getPage();
    if (isNotNull(page)) {
      context.setLocalVar("menus",page.getPromoMenus());
    }
  }
  
}
