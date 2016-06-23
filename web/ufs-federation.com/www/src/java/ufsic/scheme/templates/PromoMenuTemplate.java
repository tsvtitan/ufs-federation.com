package ufsic.scheme.templates;

import java.util.Map;
import ufsic.providers.Record;
import ufsic.scheme.Page;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class PromoMenuTemplate extends Template {

  public PromoMenuTemplate(SchemeTable table, Record record) {
    super(table, record);
  }
  
  @Override
  public void process(Map<String, Object> vars) {
    
    super.process(vars);
    
    Page page = getScheme().getPage();
    if (isNotNull(page)) {
      vars.put("menus",page.getPromoMenus());
    }
  }
  
}
