package ufsic.scheme;

public class AccountRoles extends SchemeTable {
  
  public final static String TableName = "ACCOUNT_ROLES";
  
  public final static String AccountId = "ACCOUNT_ID";
  public final static String RoleId = "ROLE_ID";

  public AccountRoles(Scheme scheme, String name) {
    super(scheme, name);
  }

  public AccountRoles(Scheme scheme) {
    super(scheme, TableName);
  }

  @Override
  public Class getRecordClass() {
    return AccountRole.class;
  }
    
}