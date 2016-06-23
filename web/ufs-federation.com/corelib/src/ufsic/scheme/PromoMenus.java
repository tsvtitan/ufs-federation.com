package ufsic.scheme;

public class PromoMenus extends SchemeTable<PromoMenu> {

  public final static String TableName = "PROMO_MENUS";
  
  protected final static String PromoMenuId = "PROMO_MENU_ID";
  protected final static String PageId = "PAGE_ID";
  protected final static String LangId = "LANG_ID";
  protected final static String Name = "NAME";
  protected final static String Description = "DESCRIPTION";
  protected final static String Priority = "PRIORITY";
  protected final static String OnlyCurrent = "ONLY_CURRENT";
  
  protected final static String Path = "PATH";
  protected final static String Link = "LINK";
  
  public PromoMenus(Scheme scheme, String name) {
    super(scheme, name);
  }

  public PromoMenus(Scheme scheme) {
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return PromoMenu.class;
  }
  
}
