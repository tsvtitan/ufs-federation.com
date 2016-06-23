package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class PageTableForm extends SchemeRecord {

  public PageTableForm(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getPageTableId() {
    
    return getValue(PageTableForms.PageTableId);
  }

  public Value getPageFormId() {
    
    return getValue(PageTableForms.PageFormId);
  }

  public Value getFilters() {
    
    return getValue(PageTableForms.Filters);
  }

  public Value getParams() {
    
    return getValue(PageTableForms.Params);
  }
  
  public Value getPriority() {
    
    return getValue(PageTableForms.Priority);
  }
  
}
