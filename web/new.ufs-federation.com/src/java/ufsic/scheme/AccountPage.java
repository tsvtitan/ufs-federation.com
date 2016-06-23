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

  public Value getAction() {
    
    return getValue(AccountPages.Action);
  }
  
  public Value getPath() {
    
    return getValue(AccountPages.Path);
  }
  
}
