package ufsic.scheme;

import java.sql.Timestamp;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.SchemeTable;

public class Token extends SchemeRecord {
  
  public Token(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Token(SchemeTable table) {
    super(table);
  }
  
  public Value getTokenId() {
  
    return getValue(Tokens.TokenId);
  }
  
  public void setTokenId(Value tokenId) {

    if (!setValue(Tokens.TokenId,tokenId)) {
      add(Tokens.TokenId,tokenId);
    }
  }
  
  public Value getDeviceId() {
  
    return getValue(Tokens.DeviceId);
  }
  
  public void setDeviceId(Value deviceId) {

    if (!setValue(Tokens.DeviceId,deviceId)) {
      add(Tokens.DeviceId,deviceId);
    }
  }
  
  public Value getSessionId() {
  
    return getValue(Tokens.SessionId);
  }
  
  public void setSessionId(Value sessionId) {
    
    if (!setValue(Tokens.SessionId,sessionId)) {
      add(Tokens.SessionId,sessionId);
    }
  }
  
  public Value getCreated() {
  
    return getValue(Tokens.Created);
  }
  
  public void setCreated(Value created) {

    if (!setValue(Tokens.Created,created)) {
      add(Tokens.Created,created);
    }
  }
  
  public Value getExpired() {
  
    return getValue(Tokens.Expired);
  }
  
  public void setExpired(Value expired) {

    if (!setValue(Tokens.Expired,expired)) {
      add(Tokens.Expired,expired);
    }
  }
  
  public void setExpired(Timestamp expired) {

    if (!setValue(Tokens.Expired,expired)) {
      add(Tokens.Expired,expired);
    }
  }
  
}
