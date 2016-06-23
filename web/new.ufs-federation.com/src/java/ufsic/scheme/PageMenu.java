package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class PageMenu extends Menu {

  public PageMenu(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getPageMenuId() {

    return getValue(PageMenus.PageMenuId);
  }

  public Value getPageId() {

    return getValue(PageMenus.PageId);
  }

  public Value getAccountId() {

    return getValue(PageMenus.AccountId);
  }
  
  public Value getOnlyCurrent() {

    return getValue(PageMenus.OnlyCurrent);
  }

}
