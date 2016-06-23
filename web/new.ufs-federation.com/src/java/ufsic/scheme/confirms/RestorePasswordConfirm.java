package ufsic.scheme.confirms;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.Confirm;
import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;

public class RestorePasswordConfirm extends Confirm {

  public RestorePasswordConfirm(Scheme scheme) {
    super(scheme.getConfirms());
  }
  
  public RestorePasswordConfirm(SchemeTable table, Record record) {
    super(table, record);
  }

  private boolean changePassword() {
    
    boolean ret = false;
    
    Value accountId = getAccountId();
    if (accountId.isNotNull()) {
      
      String password = getParams().getValue("Password");
      if (isNotNull(password)) {
        Account a = new Account(getScheme().getAccounts());
        a.setAccountId(accountId);
        ret = a.changePassword(password);
      }
    }
    return ret;
  }

  @Override
  public String getPathUrl() {
    
    String ret = super.getPathUrl();
    if (isNotNull(ret)) {
      ret = String.format("%s/%s",ret,getConfirmId().asString());
    }
    return ret;
  }
  
  
  @Override
  public boolean process() {
    
    return changePassword() && setConfirmed();
  }
    
  
}
