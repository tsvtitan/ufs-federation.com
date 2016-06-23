package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Menu extends SchemeRecord {

  public Menu(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getMenuId() {
    
    return getValue(Menus.MenuId);
  }

  public Value getParentId() {
    
    return getValue(Menus.ParentId);
  }

  public Value getLangId() {
    
    return getValue(Menus.LangId);
  }

  public Value getLinkId() {
    
    return getValue(Menus.LinkId);
  }

  public Value getCreated() {
    
    return getValue(Menus.Created);
  }

  public Value getName() {
    
    return getValue(Menus.Name);
  }

  public Value getDescription() {
    
    return getValue(Menus.Description);
  }
  
  public Value getPriority() {
    
    return getValue(Menus.Priority);
  }

  public Value getNewUpTo() {
    
    return getValue(Menus.NewUpTo);
  }

  public Value getIconImage() {
    
    return getValue(Menus.IconImage);
  }

  public Value getPromoImage() {
    
    return getValue(Menus.PromoImage);
  }

  public Value getInvisible() {
    
    return getValue(Menus.Invisible);
  }

  public Value getLevel() {
    
    return getValue(Menus.Level);
  }
  
  public Value getLink() {
    
    return getValue(Menus.Link);
  }
  
  public Value getIsNew() {
    
    return getValue(Menus.IsNew);
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

  public boolean getNEW() {
    
    return getIsNew().asBoolean();
  }
  
}
