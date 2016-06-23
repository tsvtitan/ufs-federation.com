package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Lang extends SchemeRecord {
  
  public Lang(SchemeTable table, Record record) {
    super(table,record);
  }
  
  public Value getLangId() {
    
    return getValue(Langs.LangId);
  }
  
  public Value getName() {
    
    return getValue(Langs.Name);
  }

  public Value getPriority() {
    
    return getValue(Langs.Priority);
  }
  
}
