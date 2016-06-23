package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class PromoMenu extends SchemeRecord {

  public PromoMenu(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getPromoMenuId() {

    return getValue(PromoMenus.PromoMenuId);
  }
  
  public Value getPageId() {

    return getValue(PromoMenus.PageId);
  }

  public Value getLangId() {

    return getValue(PromoMenus.LangId);
  }
  
  public Value getName() {

    return getValue(PromoMenus.Name);
  }

  public Value getDescription() {

    return getValue(PromoMenus.Description);
  }

  public Value getPriority() {

    return getValue(PromoMenus.Priority);
  }

  public Value getLink() {

    return getValue(PromoMenus.Link);
  }

  public Value getPath() {

    return getValue(PromoMenus.Path);
  }

  public String getNAME() {

    return getScheme().getDictionary().replace(getName().asString());
  }

  public String getDESCRIPTION() {

    return getScheme().getDictionary().replace(getDescription().asString());
  }
  
  public String getLINK() {

    return getLink().asString();
  }
  
}
