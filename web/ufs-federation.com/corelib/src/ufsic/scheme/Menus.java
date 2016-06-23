package ufsic.scheme;

public class Menus<T extends Menu> extends SchemeTable<T> {

  public final static String TableName = "MENUS";
  
  protected final static String MenuId = "MENU_ID";
  protected final static String ParentId = "PARENT_ID";
  protected final static String LangId = "LANG_ID";
  protected final static String LinkId = "LINK_ID";
  protected final static String Created = "CREATED";
  protected final static String Name = "NAME";
  protected final static String Description = "DESCRIPTION";
  protected final static String Priority = "PRIORITY";
  protected final static String NewUpTo = "NEW_UP_TO";
  protected final static String IconImage = "ICON_IMAGE";
  protected final static String PromoImage = "PROMO_IMAGE";
  protected final static String Invisible = "INVISIBLE";
  
  protected final static String ParentName = "PARENT_NAME";
  protected final static String Level = "LEVEL";
  protected final static String Path = "PATH";
  protected final static String Link = "LINK";
  protected final static String IsNew = "IS_NEW";
  
  public Menus(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public Menus(Scheme scheme) {
    
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return Menu.class;
  }
  
}
