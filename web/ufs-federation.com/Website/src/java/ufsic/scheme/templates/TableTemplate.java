package ufsic.scheme.templates;

import java.lang.reflect.Constructor;
import java.util.Map;
import ufsic.contexts.IVarContext;
import ufsic.providers.Record;
import ufsic.scheme.Page;
import ufsic.scheme.PageTable;
import ufsic.scheme.PageTables;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;
import ufsic.scheme.tables.Table;

public class TableTemplate extends Template {

  public TableTemplate(SchemeTable table, Record record) {
    super(table, record);
  }
  
  
  protected Class getFormClass() {
    return Table.class;
  }
  
  protected Table newTable(PageTable table) {
    
    Table ret = null;

    Class cls = getFormClass();
    if (isNull(cls)) {
      cls = Table.class;
    }
    if (isNotNull(cls)) {
      try {
        Constructor con = cls.getConstructor(PageTable.class);
        if (isNotNull(con)) {

          ret = (Table)con.newInstance(table);
        }
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  @Override
  public void process(IVarContext context) {
    
    super.process(context);
            
    if (!context.localExists("table")) {
      
      PageTable table = null;
      
      Page page = getScheme().getPage(); 
      if (isNotNull(page)) {
        
        PageTables tables = page.getPageTables();
        if (isNotNull(tables) && (tables.size()>0)) {
      
          table = (PageTable)tables.get(0);
        }
      } else {

        Object pageTableId = getScheme().getPath().getParameterValue("pageTableId");
        if (isNotNull(pageTableId)) {
          
          PageTables tables = new PageTables(getScheme());
          Record r = getProvider().first(tables.getViewName(),new ufsic.providers.Filter(PageTables.PageTableId,pageTableId));
          if (isNotNull(r)) {
            table = new PageTable(tables,r);
          }
        }
      }
      
      if (isNotNull(table)) {
        
        Table tb = newTable(table);
        tb.process();

        context.setLocalVar("table",tb);
      }
    }
  }
  
}
