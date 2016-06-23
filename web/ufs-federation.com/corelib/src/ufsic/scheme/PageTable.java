package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class PageTable extends SchemeRecord {

  private final PageTableForms pageTableForms;
  
  public PageTable(SchemeTable table, Record record) {
    
    super(table, record);
    
    this.pageTableForms = new PageTableForms(getScheme());
  }

  public Value getPageTableId() {
    
    return getValue(PageTables.PageTableId);
  }
  
  public Value getPageId() {
    
    return getValue(PageTables.PageId);
  }

  public Value getLangId() {

    return getValue(PageTables.LangId);
  }

  public Value getTemplateId() {

    return getValue(PageTables.TemplateId);
  }
  
  public Value getName() {

    return getValue(PageTables.Name);
  }

  public Value getDescription() {

    return getValue(PageTables.Description);
  }
  
  public Value getQuery() {
    
    return getValue(PageTables.Query);
  }
  
  public Value getColumns() {
    
    return getValue(PageTables.Columns);
  }

  public Value getAlignments() {
    
    return getValue(PageTables.Alignments);
  }
  
  public Value getFormats() {
    
    return getValue(PageTables.Formats);
  }

  public Value getMaxCount() {
    
    return getValue(PageTables.MaxCount);
  }
  
  public Value getAutoLoad() {
    
    return getValue(PageTables.AutoLoad);
  }

  public Value getAsync() {
    
    return getValue(PageTables.Async);
  }
  
  public Value getPriority() {
    
    return getValue(PageTables.Priority);
  }

  public Value getSums() {
    
    return getValue(PageTables.Sums);
  }
  
  public Value getPath() {
    
    return getValue(PageTables.Path);
  }
  
  public PageTableForms getPageTableForms(boolean refresh) {
    
    if (refresh) {
      pageTableForms.open(new Filter(PageTableForms.PageTableId,getPageTableId()),new Orders(PageTableForms.Priority));
    }
    return pageTableForms;
  }
  
  public PageTableForms getPageTableForms() {
    
    return getPageTableForms(pageTableForms.isEmpty());
  }

}