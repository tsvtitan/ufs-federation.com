package ufsic.scheme;

public class PromoBanners extends SchemeTable {

  public final static String TableName = "PROMO_BANNERS";
  
  protected final static String PromoBannerId = "PROMO_BANNER_ID";
  protected final static String PageId = "PAGE_ID";
  protected final static String ImageId = "IMAGE_ID";
  protected final static String PathId = "PATH_ID";
  protected final static String LangId = "LANG_ID";
  protected final static String Name = "NAME";
  protected final static String Description = "DESCRIPTION";
  protected final static String Priority = "PRIORITY";
  protected final static String OnlyCurrent = "ONLY_CURRENT";
  
  protected final static String Path = "PATH";
  protected final static String ImagePath = "IMAGE_PATH";
  protected final static String Link = "LINK";
  
  public PromoBanners(Scheme scheme, String name) {
    super(scheme, name);
  }

  public PromoBanners(Scheme scheme) {
    super(scheme,TableName);
  }
  

  @Override
  public Class getRecordClass() {

    return PromoBanner.class;
  }
  
}