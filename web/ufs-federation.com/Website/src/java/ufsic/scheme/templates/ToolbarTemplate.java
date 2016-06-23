package ufsic.scheme.templates;

import ufsic.contexts.IVarContext;

import ufsic.providers.Record;
import ufsic.scheme.Account;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class ToolbarTemplate extends Template {

  public ToolbarTemplate(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  public void process(IVarContext context) {
    
    super.process(context);
    
    if (!context.localExists("menus")) {
    
      Account account = getScheme().getAccount();
      if (isNotNull(account)) {
        context.setLocalVar("menus",account.getMenus());
      }
    }
  }
  
}
