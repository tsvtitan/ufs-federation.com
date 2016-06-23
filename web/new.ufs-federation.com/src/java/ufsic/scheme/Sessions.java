package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Sessions extends SchemeTable {

  public final static String TableName = "SESSIONS";
  
  public final static String SessionId = "SESSION_ID";
  public final static String AccountId = "ACCOUNT_ID";
  public final static String Locked = "LOCKED";
  public final static String Expired = "EXPIRED";
  public final static String Agent = "AGENT";
  public final static String IPHost = "IP_HOST";
  public final static String NeedComms = "NEED_COMMS";

  
  public Sessions(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Sessions(Scheme scheme) {
    super(scheme, TableName);
  }

  @Override
  public Class getRecordClass() {
    return Session.class;
  }

  public Session getSession(Object sessionId) {
  
    Session ret = null;
    if (provider.isConnected()) {
      
      Record r = provider.first(getViewName(),new Filter(SessionId,sessionId));
      if (isNotNull(r)) {
        ret = new Session(this,r);
      }
    }
    return ret;
  }

  public Session trySession(String sessionId) {
    
    Session ret = getSession(sessionId);
    if (isNull(ret) && provider.isConnected()) {
      
      ret = new Session(this,null);
      ret.setSessionId(new Value(sessionId));
      
      if (insert(ret)) {
        ret = getSession(ret.getSessionId());
      } else {
        ret = null;
      }
    }
    return ret;
  }
  
}
