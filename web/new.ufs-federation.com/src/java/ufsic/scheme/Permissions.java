package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Permissions extends SchemeTable {

  public final static String TableName = "PERMISSIONS";
  
  protected final static String PermissionId = "PERMISSION_ID";
  protected final static String PathId = "PATH_ID";
  protected final static String AccountId = "ACCOUNT_ID";
  protected final static String Action = "ACTION";
  protected final static String Right = "RIGHT";
  protected final static String OnlyCurrent = "ONLY_CURRENT";
  
  public Permissions(Scheme scheme, String viewName) {
    super(scheme, viewName);
  }

  public Permissions(Scheme scheme) {
    super(scheme, TableName);
  }

  @Override
  public Class getRecordClass() {
    return Permission.class;
  }

  public Permission find(Object accountId, Value pathId, String action) {
    
    Permission ret = null;
    for (Record r: this) {
      Permission p = (Permission)r; 
      if (p.getAccountId().same(accountId)) {
        if (p.getPathId().same(pathId)) {
          if (p.getAction().same(action)) {
            ret = p;
            break;
          }
        }
      }
    }
    return ret;
  }

}
