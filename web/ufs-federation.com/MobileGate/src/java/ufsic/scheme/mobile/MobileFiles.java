package ufsic.scheme.mobile;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;

public class MobileFiles extends SchemeTable<MobileFile> {
  
  public final static String TableName = "MOBILE_FILES";
  
  public final static String MobileFileId = "MOBILE_FILE_ID";
  public final static String TokenId = "TOKEN_ID";
  public final static String CacheId = "CACHE_ID";
  public final static String Created = "CREATED";
  public final static String Location = "LOCATION";
  public final static String Name = "NAME";
  public final static String Extension = "EXTENSION";
  public final static String ContentType = "CONTENT_TYPE";
  public final static String FileSize = "FILE_SIZE";
  
  public final static String CacheData = "CACHE_DATA";
  public final static String CacheSize = "CACHE_SIZE";
  public final static String CacheExpired = "CACHE_EXPIRED";
  
  public MobileFiles(Scheme scheme, String viewName) {
    super(scheme, viewName);
  }

  public MobileFiles(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    return MobileFile.class;
  }
  
}
