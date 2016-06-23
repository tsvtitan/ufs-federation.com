package ufsic.scheme.mobile;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeTable;

public class MobileMenu extends SchemeRecord {

  public MobileMenu(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getMobileMenuId() {
    
    return getValue(MobileMenus.MobileMenuId);
  }

  public Value getParentId() {
    
    return getValue(MobileMenus.ParentId);
  }

  public Value getLangId() {
    
    return getValue(MobileMenus.LangId);
  }
  
  public Value getLocked() {
    
    return getValue(MobileMenus.Locked);
  }

  public Value getName() {
    
    return getValue(MobileMenus.Name);
  }

  public Value getDescription() {
    
    return getValue(MobileMenus.Description);
  }
  
  public Value getMenuType() {
    
    return getValue(MobileMenus.MenuType);
  }
  
  public Value getPriority() {
    
    return getValue(MobileMenus.Priority);
  }
  
  public Value getDefaultImage() {
    
    return getValue(MobileMenus.DefaultImage);
  }
  
  public Value getHighlightImage() {
    
    return getValue(MobileMenus.HighlightImage);
  }
  
  public Value getNewsSql() {
    
    return getValue(MobileMenus.NewsSql);
  } 
  
  public Value getNewsAllCountSql() {
    
    return getValue(MobileMenus.NewsAllCountSql);
  }   

  public Value getNewsActualCountSql() {
    
    return getValue(MobileMenus.NewsActualCountSql);
  }   
  
  public Value getFilesSql() {
    
    return getValue(MobileMenus.FilesSql);
  }   
  
  public Value getGroupsSql() {
    
    return getValue(MobileMenus.GroupsSql);
  } 
  
  public Value getLinksSql() {
    
    return getValue(MobileMenus.LinksSql);
  } 

  public Value getKeywordsSql() {
    
    return getValue(MobileMenus.KeywordsSql);
  } 
  
  public Value getHtml() {
    
    return getValue(MobileMenus.Html);
  } 
  
  public Value getUfs() {
    
    return getValue(MobileMenus.Ufs);
  }
  
  public Value getPremier() {
    
    return getValue(MobileMenus.Premier);
  }
  
  public Value getReplacements() {
    
    return getValue(MobileMenus.Replacements);
  }
  
  public Value getLevel() {
    
    return getValue(MobileMenus.Level);
  }
  
  public Value getExpired() {
    
    return getValue(MobileMenus.Expired);
  }
  
}
