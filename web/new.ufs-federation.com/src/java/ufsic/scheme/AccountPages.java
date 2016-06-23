package ufsic.scheme;

public class AccountPages extends SchemeTable {
  
  public final static String TableName = "ACCOUNT_PAGES";
  
  public final static String AccountId = "ACCOUNT_ID";
  public final static String PageId = "PAGE_ID";
  public final static String Action = "ACTION";
  public final static String Path = "PATH";

  public AccountPages(Scheme scheme, String name) {
    super(scheme, name);
  }

  public AccountPages(Scheme scheme) {
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {
    return AccountPage.class;
  }
  
}
