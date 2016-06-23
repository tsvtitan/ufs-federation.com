package ufsic.scheme.handlers;

import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.scheme.Account;
import ufsic.scheme.AccountPage;
import ufsic.scheme.Accounts;
import ufsic.scheme.Comm;
import ufsic.scheme.Handler;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Sessions;
import ufsic.utils.Utils;

public class LoginHandler extends Handler {

  public LoginHandler(Path path) {
    
    super(path);
    path.setUseCache(false);
  }

  private boolean loginSuccess(Account account) {
    
    boolean ret = false;

    Path path = getPath();
    
    AccountPage page = account.getLoginSuccessPage();
    if (isNotNull(page)) {
      ret = path.clearPathCache(page.getPageId());
      if (ret) {
        path.setAuthFailed(false);
        ret = path.redirect(path.getBasePath(page.getPagePath().asString()));
      }
    }
    
    if (!ret) {
      Path last = path.getLastPath();
      if (isNotNull(last)) {
        ret = path.clearPathCache(last.getPathId());
        if (ret) {
          ret = path.redirect(path.getBasePath(last.getPath().asString()));
        }
      }
    }
    
    return ret;
  }
  
  private boolean authFailed(Account account) {
    
    boolean ret = false;
    
    Path path = getPath();
    
    if (isNotNull(account)) {
      AccountPage page = account.getAuthFailedPage();
      if (isNotNull(page)) {
        ret = path.clearPathCache(page.getPageId());
        if (ret) {
          path.setAuthFailed(true);
          ret = path.redirect(path.getBasePath(page.getPagePath().asString()));
        }
      }
    }
    
    if (!ret) {
      Path last = path.getLastPath();
      if (isNotNull(last)) {
        ret = path.clearPathCache(last.getPathId());
        if (ret) {
          ret = path.redirect(path.getBasePath(last.getPath().asString()));
        }
      }
    }
    
    return ret;
  }
          
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = super.process(comm);
    
    Scheme scheme = getScheme();
    Path path = getPath();
    
    Object login = path.getParameterValue("login");
    Object pass = path.getParameterValue("password");
    if (isNotNull(login) && isNotNull(pass)) { 
      
      Filter f = new Filter(Accounts.Login,login).And(Accounts.IsRole,0);
      Record r = getProvider().first(scheme.getAccounts().getViewName(),null,f,new Orders(Accounts.Created));
      if (isNotNull(r)) { 
        
        Account a = new Account(scheme.getAccounts(),r);
        if (!a.getLocked().asBoolean()) {
          
          String passMd5 = Utils.md5(pass.toString()).toUpperCase();
          if (a.getPass().same(passMd5)) {
            
            Record upd = new Record();
            upd.add(Sessions.AccountId,a.getAccountId());
            
            ret = scheme.getSessions().update(upd,new Filter(Sessions.SessionId,scheme.getSessionId()));
            if (ret) {
              
              ret = loginSuccess(a);
            }
          } else {
            ret = authFailed(a);
          }
        } else {
          ret = authFailed(a);
        }
      } else {
        ret = authFailed(new Account(scheme.getAccounts(),null));
      }
    }
    return ret;
  }
  
}
