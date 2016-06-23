package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Permission extends SchemeRecord {
 
  public enum Action {
    
    SHOW, CREATE, EDIT, DELETE;
    
    public static String asString(Action action) {
      
      String ret = "";
      switch (action) {
        case SHOW: { ret="SHOW"; break; }
        case CREATE: { ret="CREATE"; break; }
        case EDIT: { ret="EDIT"; break; }
        case DELETE: { ret="DELETE"; break; }
        default: { break; }
      }
      return ret;
    }
    
  }
  
  public enum Right {
    
    GRANTED, DENIED;

    public static String asString(Right right) {
      
      String ret = "";
      switch (right) {
        case GRANTED: { ret="GRANTED"; break; }
        case DENIED: { ret="DENIED"; break; }
        default: { break; }
      }
      return ret;
    }
    
  }
  
  public Permission(SchemeTable table, Record record) {
    super(table,record);
  }
  
  public Value getPermissionId() {
    
    return getValue(Permissions.PermissionId);
  }
  
  public Value getPathId() {
    
    return getValue(Permissions.PathId);
  }
  
  public Value getAccountId() {
    
    return getValue(Permissions.AccountId);
  }

  public Value getAction() {
    
    return getValue(Permissions.Action);
  }

  public Value getRight() {
    
    return getValue(Permissions.Right);
  }
  
}
