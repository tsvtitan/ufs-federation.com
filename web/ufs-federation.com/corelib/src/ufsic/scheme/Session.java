package ufsic.scheme;

import java.sql.Timestamp;
import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Session extends SchemeRecord {

  private final Comms comms;
  
  public Session(SchemeTable table, Record record) {
    super(table,record);
    this.comms = new Comms(getScheme());
  }
  
  public Session(SchemeTable table) {
    this(table,null);
  }

  public Value getSessionId() {
  
    return getValue(Sessions.SessionId);
  }
  
  public void setSessionId(Value sessionId) {

    if (!setValue(Sessions.SessionId,sessionId)) {
      add(Sessions.SessionId,sessionId);
    }
  }
  
  public Value getAccountId() {
  
    return getValue(Sessions.AccountId);
  }
  
  public void setAccountId(Value accountId) {
    
    if (!setValue(Sessions.AccountId,accountId)) {
      add(Sessions.AccountId,accountId);
    }
  }

  public Value getApplicationId() {
  
    return getValue(Sessions.ApplicationId);
  }
  
  public void setApplicationId(Value applicationId) {
    
    if (!setValue(Sessions.ApplicationId,applicationId)) {
      add(Sessions.ApplicationId,applicationId);
    }
  }
  
  public Value getCreated() {
  
    return getValue(Sessions.Created);
  }
  
  public void seCreated(Value created) {
    
    if (!setValue(Sessions.Created,created)) {
      add(Sessions.Created,created);
    }
  }
  
  public Value getLocked() {
  
    return getValue(Sessions.Locked);
  }
  
  public void setLocked(Value locked) {
    
    if (!setValue(Sessions.Locked,locked)) {
      add(Sessions.Locked,locked);
    }
  }

  public Value getExpired() {
  
    return getValue(Sessions.Expired);
  }
  
  public void setExpired(Value expired) {
    
    if (!setValue(Sessions.Expired,expired)) {
      add(Sessions.Expired,expired);
    }
  }
  
  public void setExpired(Timestamp expired) {
    
    if (!setValue(Sessions.Expired,expired)) {
      add(Sessions.Expired,expired);
    }
  }
  
  public void setAgent(Value agent) {
    
    if (!setValue(Sessions.Agent,agent)) {
      add(Sessions.Agent,agent);
    }
  }
  
  public void setIPHost(Value ipHost) {
    
    if (!setValue(Sessions.IPHost,ipHost)) {
      add(Sessions.IPHost,ipHost);
    }
  }
  
  public Value getNeedComms() {
    
    return getValue(Sessions.NeedComms);
  }
  
  public boolean needComms() {
    
    return getNeedComms().asBoolean();
  }
  
  public Comm beginComm(Value created, Object inHeaders, Object inData) {
    
    Value commId = getProvider().getUniqueId();
    
    Comm ret = new Comm(comms,null);
    
    ret.setCommId(commId);
    ret.setSessionId(getSessionId());
    ret.setPathId(getScheme().getPath().getPathId());
    ret.setCreated(created);
    ret.setInHeaders(inHeaders);
    ret.setInData(inData);
    ret.setLangId(getScheme().getLang().getLangId());
    
    if (comms.insert(ret)) {
      //ret = comms.getComm(commId);
    } else {
      ret = null;
    }
    
    return ret;
  }
  
  public Comm beginComm() {
    
    Comm ret = null;
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
      ret = beginComm(scheme.getStamp(),scheme.getRequestHeaders(),scheme.getRequestBody());
    }
    return ret;
  }
  
  public void endComm(Comm comm, Object outHeaders, Object outData) {
     
    if (isNotNull(comm)) {
      
      Value stamp = getProvider().getNow();
      double duration = getProvider().diffTimestamp(stamp,comm.getCreated());
              
      Comm upd = new Comm(comms,null);
      upd.setDuration(duration);
      upd.setFinished(stamp);
      upd.setOutHeaders(outHeaders);
      upd.setOutData(outData);
      
      comms.update(upd,new Filter(Comms.CommId,comm.getCommId()));
    }
  }
  
  public boolean valid(Value stamp) {
    
    boolean ret = getLocked().isNull();
    Value expired = getExpired();
    if (ret && stamp.isNotNull() && expired.isNotNull()) {
      double diff = getProvider().diffTimestamp(expired,stamp);
      ret = diff>=0;
    }
    return ret;
  }
  
  public boolean valid() {
    
    boolean ret = false;
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
      ret = valid(scheme.getStamp());
    }
    return ret;
  }
  
}
