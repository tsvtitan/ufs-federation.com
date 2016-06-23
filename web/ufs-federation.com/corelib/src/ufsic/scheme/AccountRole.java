package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class AccountRole extends SchemeRecord {

  public AccountRole(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getRoleId() {
    
    return getValue(AccountRoles.RoleId);
  }
}
