package ufsic.scheme.templates;

import java.util.ArrayList;
import ufsic.contexts.IVarContext;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Menu;
import ufsic.scheme.Menus;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class MenuTemplate extends Template {

  public MenuTemplate(SchemeTable table, Record record) {
    super(table, record);
  }

  public class TemplateMenu extends Menu {

    private ArrayList<TemplateMenu> items = new ArrayList<>();
    
    public TemplateMenu(SchemeTable table, Record record) {
      super(table, record);
    }
    
    public ArrayList<TemplateMenu> getItems() {
      
      return items;
    }
  }
  
  private class TemplateMenus extends ArrayList<TemplateMenu> {

    private final SchemeTable table = new Menus(getScheme());
            
    public TemplateMenus() {
      
      super();
      refresh();
    }

    private TemplateMenu find(Value parentId) {
      
      TemplateMenu ret = null;
      for (TemplateMenu tm: this) {
        Value v = tm.getMenuId();
        if (v.same(parentId)) {
          ret = tm;
          break;
        }
      }
      return ret;
    }
    
    private void refresh() {
      
      Menus<Menu> menus = getScheme().getMenus(2);  
      if (isNotNull(menus)) {
        
        for (Menu m: menus) {
          
          Value parentId = m.getParentId();
                  
          if (parentId.isNull()) {
            
            this.add(new TemplateMenu(table,m));
            
          } else {
            
            TemplateMenu parent = find(m.getParentId());
            if (isNotNull(parent)) {
              parent.getItems().add(new TemplateMenu(table,m));
            } else {
              this.add(new TemplateMenu(table,m));
            }
          }
        }
      }
    }
  }
  
  @Override
  public void process(IVarContext context) {
    
    super.process(context);
    
    if (!context.localExists("menus")) {
      context.setLocalVar("menus",new TemplateMenus());
    }
  }
  
}
