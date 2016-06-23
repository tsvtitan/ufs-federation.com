package ufsic.scheme;

public class AccountMenus extends SchemeTable {
  
  public final static String TableName = "ACCOUNT_MENUS";
  
  public final static String AccountId = "ACCOUNT_ID";
  public final static String MenuId = "MENU_ID";
  public final static String Priority = "PRIORITY";

  public final static String Name = Menus.Name;
  public final static String Link = Menus.Link;
  public final static String LangId = Menus.LangId;
  
  public AccountMenus(Scheme scheme, String name) { 
    super(scheme, name);
  }

  public AccountMenus(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    
    return AccountMenu.class;
  }
    
}
