package ufsic.scheme;

public class Caches extends SchemeTable {

  public final static String TableName = "CACHES";
  
  public final static String CacheId = "CACHE_ID";
  public final static String SessionId = "SESSION_ID";
  public final static String PathId = "PATH_ID";
  public final static String CommId = "COMM_ID";
  public final static String Created = "CREATED";
  public final static String Expired = "EXPIRED";
  public final static String Data = "DATA";
  public final static String Headers = "HEADERS";
  public final static String LangId = "LANG_ID";
  
  public Caches(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Caches(Scheme scheme) {
    super(scheme, TableName);
  }

  @Override
  public Class getRecordClass() {
    return Cache.class;
  }

}
