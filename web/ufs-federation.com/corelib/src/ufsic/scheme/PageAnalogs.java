package ufsic.scheme;

public class PageAnalogs extends SchemeTable {
  
  public final static String TableName = "PAGE_ANALOGS";
  
  public final static String LangId = "LANG_ID";
  public final static String PageId = "PAGE_ID";
  public final static String Path = "PATH";  

  public PageAnalogs(Scheme scheme, String name) {
    super(scheme, name);
  }

  public PageAnalogs(Scheme scheme) {
    super(scheme,TableName);
  }
  
  
}
