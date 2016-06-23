package ufsic.scheme.templates;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.Map;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Menu;
import ufsic.scheme.Page;
import ufsic.scheme.PageMenus;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class PageMenuTemplate extends Template {

  public PageMenuTemplate(SchemeTable table, Record record) {
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

    private final SchemeTable table = new PageMenus(getScheme());
            
    public TemplateMenus() {
      
      super();
      refresh();
    }

    private TemplateMenu find(ArrayList<TemplateMenu> parentMenus, Value parentId) {
      
      TemplateMenu ret = null;
      for (TemplateMenu tm: parentMenus) {
        Value v = tm.getMenuId();
        if (v.same(parentId)) {
          ret = tm;
          break;
        } else {
          ret = find(tm.getItems(),parentId);
          if (isNotNull(ret)) {
            break;
          }
        }
      }
      return ret;
    }
    
    private void refresh() {
      
      Page page = getScheme().getPage();
      if (isNotNull(page)) {
        PageMenus menus = page.getPageMenus();
        if (isNotNull(menus)) {

          for (Record r: menus) {

            Menu m = (Menu)r;

            TemplateMenu parent = find(this,m.getParentId());
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
  public void process(Map<String, Object> vars) {
    
    super.process(vars);
    
    vars.put("pageMenus",new TemplateMenus());
  }
  
}
