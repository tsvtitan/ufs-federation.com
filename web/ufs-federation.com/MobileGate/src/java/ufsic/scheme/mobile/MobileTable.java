package ufsic.scheme.mobile;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeTable;

public class MobileTable extends SchemeRecord {

  public MobileTable(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getMobileTableId() {
    
    return getValue(MobileTables.MobileTableId);
  }

  public Value getMobileMenuId() {
    
    return getValue(MobileTables.MobileMenuId);
  }
  
  public Value getLocked() {
    
    return getValue(MobileTables.Locked);
  }
  
  public Value getName() {
    
    return getValue(MobileTables.Name);
  }

  public Value getDescription() {
    
    return getValue(MobileTables.Description);
  }

  public Value getColumns() {
    
    return getValue(MobileTables.Columns);
  }

  public Value getAlignments() {
    
    return getValue(MobileTables.Alignments);
  }
  
  public Value getSql() {
    
    return getValue(MobileTables.Sql);
  }
  
  public Value getPriority() {
    
    return getValue(MobileTables.Priority);
  }
 
  public Value getMenuDescription() {
    
    return getValue(MobileTables.MenuDescription);
  }
  
  public Value getLangId() {
    
    return getValue(MobileTables.LangId);
  }
}
