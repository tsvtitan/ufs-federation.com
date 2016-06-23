package ufsic.scheme.handlers;

import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.scheme.Account;
import ufsic.scheme.AccountPage;
import ufsic.scheme.Comm;
import ufsic.scheme.Handler;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Sessions;

public class LogoutHandler extends Handler {
  
  public LogoutHandler(Path path) {
    
    super(path);
    path.setUseCache(false);
  }

  @Override
  public boolean process(Comm comm) {
    
    boolean ret = super.process(comm);
    
    Scheme scheme = getScheme();
    Path path = getPath();
    
    Account a = scheme.getAccount();
    if (isNotNull(a)) {
      
      Record upd = new Record();
      upd.add(Sessions.Expired,getProvider().getNow());
      
      ret = scheme.getSessions().update(upd,new Filter(Sessions.SessionId,scheme.getSessionId()));
      if (ret) {
        
        AccountPage page = a.getLogoutSuccessPage();
        if (isNotNull(page)) {
          ret = path.clearPathCache(page.getPageId());
          if (ret) {
            ret = path.redirect(path.getBasePath(page.getPagePath().asString()));
          }
        } else {
        
          Path last = path.getLastPath();
          if (isNotNull(last)) {
            ret = path.clearPathCache(last.getPathId());
            if (ret) {
              ret = path.redirect(path.getBasePath(last.getPath().asString()));
            }
          }
        }
      }
    }
    
    if (!ret) {
      ret = path.redirect(path.getRootPath());
    }
    
    return ret;
  }

}
