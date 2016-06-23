package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class AccountMenu extends SchemeRecord {

  public AccountMenu(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getMenuId() {
    
    return getValue(AccountMenus.MenuId);
  }

  public Value getName() {
    
    return getValue(AccountMenus.Name);
  }

  public Value getLink() {
    
    return getValue(AccountMenus.Link);
  }
  
  public String getNAME() {
    
    return getName().asString();
  }
  
  public String getLINK() {
    
    return getLink().asString();
  }
  
}
