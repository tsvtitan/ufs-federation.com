package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class AccountPage extends SchemeRecord {

  public AccountPage(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getPageId() {
    
    return getValue(AccountPages.PageId);
  }

  public Value getPathId() {
    
    return getValue(AccountPages.PathId);
  }
  
  public Value getAction() {
    
    return getValue(AccountPages.Action);
  }
  
  public Value getPagePath() {
    
    return getValue(AccountPages.PagePath);
  }
  
}
