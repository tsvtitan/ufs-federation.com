package ufsic.scheme.handlers.mobile;

import java.util.Collection;
import java.util.Date;
import java.util.HashSet;
import java.util.Set;
import org.apache.commons.lang3.StringUtils;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.AccountDevice;
import ufsic.scheme.AccountDevices;
import ufsic.scheme.Accounts;
import ufsic.scheme.Device;
import ufsic.scheme.Devices;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Subscription;
import ufsic.scheme.Subscriptions;
import ufsic.scheme.Token;
import ufsic.scheme.Tokens;
import ufsic.utils.Utils;

public abstract class TokenHandler extends BaseHandler {

  final static protected Integer ErrorCodeTokenExpiredOrNotFound = 101;
  final static protected Integer ErrorCodeAccountTokenNotFound = 102;
  
  private AccountDevices accountDevices = null;
  private Devices devices = null;
  
  public TokenHandler(Path path) {
    super(path);
  }
  
  final protected Token getToken(BaseResponse response) throws BaseResponseException {
  
    Token ret = null;
    
    String token = getPath().getParameterValue("token",(String)null);
    if (isNotNull(token)) {
      
      Token t = getTokens().first(new Filter(Tokens.TokenId,token));
      if (isNotNull(t)) {
        
        Value expired = t.getExpired();
        if (expired.isNotNull()) {
          
          Value stamp = getScheme().getStamp();
          if (stamp.isNotNull()) {
            
            long t1 = stamp.asTimestamp().getTime();
            long t2 = expired.asTimestamp().getTime();
            if (t1>t2) {
              throw new BaseResponseException(response,ErrorCodeTokenExpiredOrNotFound);
            }
          }
        }
        ret = t;
      } else throw new BaseResponseException(response,ErrorCodeTokenExpiredOrNotFound);
    }
    return ret;
  }
  
  private AccountDevices getAccountDevices() {
  
    if (isNull(accountDevices)) {
      accountDevices = new AccountDevices(getScheme());
    }
    return accountDevices;
  }
  
  final protected Account getAccount(Token token) {
    
    Account ret = null;
    if (isNotNull(token)) {
      
      AccountDevice device = getAccountDevices().first(new Filter(AccountDevices.DeviceId,token.getDeviceId()));
      if (isNotNull(device)) {
        Value accountId = device.getAccountId();
        if (accountId.isNotNull()) {
          ret = getScheme().getAccounts().first(new Filter(Accounts.AccountId,accountId));
        }
      }
    }
    return ret;
  }
  
  private Devices getDevices() {
  
    if (isNull(devices)) {
      devices = new Devices(getScheme());
    }
    return devices;
  }
  
  final protected Device getDevice(Token token) {
    
    Device ret = null;
    if (isNotNull(token)) {
      ret = getDevices().first(new Filter(Devices.DeviceId,token.getDeviceId()));
    }
    return ret;
  }
  
  final protected Subscriptions getSubscriptions(Account account, Filter filter) {
  
    Subscriptions ret = null;
    
    if (isNotNull(account)) {
      
      Scheme scheme = getScheme();
      Value stamp = scheme.getStamp();

      GroupFilter f = new GroupFilter();
      f.And(Subscriptions.AccountId,account.getAccountId());
      f.And(Subscriptions.LangId,scheme.getLangId());
      if (isNotNull(filter)) {
        f.And(filter);
      }

      GroupFilter f1 = new GroupFilter();
      f1.Or(new Filter().And(Subscriptions.Started).IsNull());
      f1.Or(new Filter().Add(Subscriptions.Started).IsNotNull().And(Subscriptions.Started).LessOrEqual(stamp));
      f.And(f1);

      GroupFilter f2 = new GroupFilter();
      f2.Or(new Filter().And(Subscriptions.Finished).IsNull());
      f2.Or(new Filter().Add(Subscriptions.Finished).IsNotNull().And(Subscriptions.Finished).Greater(stamp));
      f.And(f2);

      Subscriptions subs = new Subscriptions(scheme);
      if (subs.open(f,new Orders(Subscriptions.Keyword))) {
        ret = subs;
      }
    }
    return ret;
  }
  
  final protected Subscriptions getSubscriptions(Token token, Filter filter) {
    
    Subscriptions ret = null;
    Account account = getAccount(token);
    if (isNotNull(account)) {
      ret = getSubscriptions(account,filter);
    }
    return ret;
  }
  
  final protected Set<String> getSet(Set<String> set, String before, String format, String after) {
    
    HashSet<String> ret = new HashSet<>();
    if (!set.isEmpty()) {
      for (String s: set) {
        if (!isEmpty(s)) {
          if (!isEmpty(before)) {
            s = before.concat(s);
          }
          if (!isEmpty(format)) {
            s = String.format(format,s);
          } 
          if (!isEmpty(after)) {
            s = s.concat(after);
          }
          ret.add(s);
        }
      }
    }
    return ret;
  }
  
  final protected Set<String> getSet(Set<String> set, String format) {
    
    return getSet(set,null,format,null);
  }
  
  final protected Set<String> getKeywords(Token token) {
    
    HashSet<String> ret = new HashSet<>();
    Filter filter = new Filter(Subscriptions.DeliveryType,Subscription.DeliveryType.APP.name());
    Subscriptions subs = getSubscriptions(token,filter);
    if (isNotNull(subs)) {

      StringBuilder sb = new StringBuilder();
      for (Subscription sub: subs) {
        String s = sub.getKeyword().asString();
        if (!isEmpty(s)) {
          ret.add(s);
        }
      }
    }
    if (ret.isEmpty()) {
      ret.add(Utils.getUniqueId());
    }
    return ret;
  }
  
  @Override
  protected void setTestHtml(StringBuilder builder) {
  
    builder.append(String.format("<input name=\"token\" placeholder=\"token\" value=\"%1$s\"></br>",""));
  }
  
}
