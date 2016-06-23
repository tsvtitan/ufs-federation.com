package ufsic.scheme.mobile;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;

public class MobileMenus extends SchemeTable<MobileMenu> {

  public final static String TableName = "MOBILE_MENUS";
  
  public final static String MobileMenuId = "MOBILE_MENU_ID";
  public final static String ParentId = "PARENT_ID";
  public final static String LangId = "LANG_ID";
  public final static String Locked = "LOCKED";
  public final static String Name = "NAME";
  public final static String Description = "DESCRIPTION";
  public final static String MenuType = "MENU_TYPE";
  public final static String Priority = "PRIORITY";
  public final static String DefaultImage = "DEFAULT_IMAGE";
  public final static String HighlightImage = "HIGHLIGHT_IMAGE";
  public final static String NewsSql = "NEWS_SQL";
  public final static String NewsAllCountSql = "NEWS_ALL_COUNT_SQL";
  public final static String NewsActualCountSql = "NEWS_ACTUAL_COUNT_SQL";
  public final static String FilesSql = "FILES_SQL";
  public final static String GroupsSql = "GROUPS_SQL";
  public final static String LinksSql = "LINKS_SQL";
  public final static String KeywordsSql = "KEYWORDS_SQL";
  public final static String Html = "HTML";
  public final static String Ufs = "UFS";
  public final static String Premier = "PREMIER";
  public final static String Replacements = "REPLACEMENTS";
  
  public final static String ParentName = "PARENT_NAME";
  public final static String Level = "LEVEL";
  public final static String LangName = "LANG_NAME";
  public final static String Expired = "EXPIRED";
  
  public MobileMenus(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public MobileMenus(Scheme scheme) {
    
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return MobileMenu.class;
  }
  
}
