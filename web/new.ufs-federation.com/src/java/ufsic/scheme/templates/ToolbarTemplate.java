package ufsic.scheme.templates;

import java.util.Map;

import ufsic.providers.Record;
import ufsic.scheme.Account;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class ToolbarTemplate extends Template {

  public ToolbarTemplate(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  public void process(Map<String, Object> vars) {
    
    super.process(vars);
    
    Account account = getScheme().getAccount();
    if (isNotNull(account)) {
      vars.put("menus",account.getMenus());
    }
  }
  
}
