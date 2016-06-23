package ufsic.scheme;

import java.sql.Timestamp;
import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Sessions extends SchemeTable<Session> {

  public final static String TableName = "SESSIONS";
  
  public final static String SessionId = "SESSION_ID";
  public final static String AccountId = "ACCOUNT_ID";
  public final static String ApplicationId = "APPLICATION_ID";
  public final static String Created = "CREATED";
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
    
    Record r = getProvider().first(getViewName(),new Filter(SessionId,sessionId));
    if (isNotNull(r)) {
      ret = new Session(this,r);
    }
    return ret;
  }

  public Session trySession(String sessionId) {
    
    Scheme sch = getScheme();
    Value appId = new Value(null);
    if (isNotNull(sch)) {
      appId = sch.getApplicationId();
    }
    Session ret = getSession(sessionId);
    if (isNull(ret)) {
      
      Value created = getProvider().getNow();
      
      ret = new Session(this,null);
      ret.setSessionId(new Value(sessionId));
      ret.seCreated(created);
      ret.setApplicationId(appId);
      
      String to = sch.getOption("Session.Timeout");
      if (isNotNull(to)) {
        
        Integer timeout = Integer.parseInt(to);
        
        Timestamp expired = new Timestamp(created.asTimestamp().getTime() + (timeout*1000L));
        ret.setExpired(expired);
        
      }
      
      if (insert(ret)) {
        ret = getSession(ret.getSessionId());
      } else {
        ret = null;
      }
    } else {
      if (!ret.getApplicationId().same(appId)) {
        Session temp = new Session(this);
        temp.setApplicationId(appId);
        if (temp.update(new Filter(SessionId,ret.getSessionId()))) {
          ret.setApplicationId(appId);
        }
      }
    }
    return ret;
  }
  
}
