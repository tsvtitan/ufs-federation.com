package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Accounts extends SchemeTable {

  public final static String TableName = "ACCOUNTS";

  public final static String AccountId = "ACCOUNT_ID";
  public final static String Created = "CREATED";
  public final static String IsRole = "IS_ROLE";
  public final static String Surname = "SURNAME";
  public final static String Name = "NAME";
  public final static String Patronymic = "PATRONYMIC";
  public final static String Login = "LOGIN";
  public final static String Pass = "PASS";
  public final static String Phone = "PHONE";
  public final static String Email = "EMAIL";
  public final static String Description = "DESCRIPTION";
  public final static String Locked = "LOCKED";
  public final static String LockReason = "LOCK_REASON";
  
  public Accounts(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Accounts(Scheme scheme) {
    super(scheme);
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {
    return Account.class;
  }
  
  public Account getAccount(Value accountId) {
    
    Account ret = null;
    Record r = provider.first(getViewName(),null,new Filter(Accounts.AccountId,accountId));
    if (isNotNull(r)) {
      ret = new Account(this,r);
    }
    return ret;
  }
  
}