package ufsic.scheme;

public class Branches extends SchemeTable<Branch> {
  
  public final static String TableName = "BRANCHES";
  
  public final static String BranchId = "BRANCH_ID";
  public final static String LangId = "LANG_ID";
  public final static String Name = "NAME";
  public final static String Created = "CREATED";
  public final static String Locked = "LOCKED";
  public final static String Region = "REGION";
  public final static String City = "CITY";
  public final static String Address = "ADDRESS";
  public final static String Latitude = "LATITUDE";
  public final static String Logitude = "LONGITUDE";
  public final static String Priority = "PRIORITY";
  public final static String Contacts = "CONTACTS";
  public final static String Map = "MAP";
  public final static String Id = "ID";
  
  public Branches(Scheme scheme, String viewName) {
    super(scheme, viewName);
  }

  public Branches(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    return Branch.class;
  }
  
  
}
