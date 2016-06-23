package ufsic.scheme;

public class PageMenus extends Menus<PageMenu> {
  
  public final static String TableName = "PAGE_MENUS";
  
  protected final static String PageMenuId = "PAGE_MENU_ID";
  protected final static String PageId = "PAGE_ID";
  protected final static String AccountId = "ACCOUNT_ID";
  protected final static String OnlyCurrent = "ONLY_CURRENT";

  public PageMenus(Scheme scheme, String name) {
    super(scheme, name);
  }

  public PageMenus(Scheme scheme) {
    
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return PageMenu.class;
  }
  
}
