package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Comm extends SchemeRecord {

  public Comm(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getCommId() {
    
    return getValue(Comms.CommId);
  }
  
  public void setCommId(Value commId) {
    
    if (!setValue(Comms.CommId,commId)) {
      add(Comms.CommId,commId);
    }
  }

  public void setPathId(Value pathId) {
    
    if (!setValue(Comms.PathId,pathId)) {
      add(Comms.PathId,pathId);
    }
  }
  
  public void setSessionId(Value sessionId) {
    
    if (!setValue(Comms.SessionId,sessionId)) {
      add(Comms.SessionId,sessionId);
    }
  }

  public Value getCreated() {
    
    return getValue(Comms.Created);
  }
  
  public void setCreated(Value created) {
    
    if (!setValue(Comms.Created,created)) {
      add(Comms.Created,created);
    }
  }
  
  public void setFinished(Value finished) {
    
    if (!setValue(Comms.Finished,finished)) {
      add(Comms.Finished,finished);
    }
  }

  public void setDuration(Object duration) {
    
    if (!setValue(Comms.Duration,duration)) {
      add(Comms.Duration,duration);
    }
  }
  
  public void setInData(Object inData) {
    
    if (!setValue(Comms.InData,inData)) {
      add(Comms.InData,inData);
    }
  } 
  
  public void setInHeaders(Object inHeaders) {
    
    if (!setValue(Comms.InHeaders,inHeaders)) {
      add(Comms.InHeaders,inHeaders);
    }
  }

  public void setOutData(Object outData) {
    
    if (!setValue(Comms.OutData,outData)) {
      add(Comms.OutData,outData);
    }
  }

  public void setOutHeaders(Object outHeaders) {
    
    if (!setValue(Comms.OutHeaders,outHeaders)) {
      add(Comms.OutHeaders,outHeaders);
    }
  }

  public void setLangId(Value langId) {
    
    if (!setValue(Comms.LangId,langId)) {
      add(Comms.LangId,langId);
    }
  }
  
}
