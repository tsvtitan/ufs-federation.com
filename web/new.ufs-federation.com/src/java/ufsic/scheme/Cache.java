package ufsic.scheme;

import java.util.HashMap;
import java.util.Map;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.utils.Utils;

public class Cache extends SchemeRecord {

  private Path path = null;
  
  public Cache(SchemeTable table, Record record) {
    super(table, record);
  }

  public void setPath(Path path) {
    
    this.path = path;
  }
  
  public Value getCacheId() {
    
    return getValue(Caches.CommId);
  }
  
  public void setCacheId(Value cacheId) {
    
    if (!setValue(Caches.CacheId,cacheId)) {
      add(Caches.CacheId,cacheId);
    }
  }
  
  public void setSessionId(Value sessionId) {
    
    if (!setValue(Caches.SessionId,sessionId)) {
      add(Caches.SessionId,sessionId);
    }
  }

  public void setSessionId(Object sessionId) {
    
    if (!setValue(Caches.SessionId,sessionId)) {
      add(Caches.SessionId,sessionId);
    }
  }
  
  public void setPathId(Value pathId) {
    
    if (!setValue(Caches.PathId,pathId)) {
      add(Caches.PathId,pathId);
    }
  }

  public void setCommId(Value commId) {
    
    if (!setValue(Caches.CommId,commId)) {
      add(Caches.CommId,commId);
    }
  }

  public void setCreated(Value created) {
    
    if (!setValue(Caches.Created,created)) {
      add(Caches.Created,created);
    }
  }

  public void setExpired(Object expired) {
    
    if (!setValue(Caches.Expired,expired)) {
      add(Caches.Expired,expired);
    }
  }
  
  public Value getData() {
    
    return getValue(Caches.Data);
  }

  public void setData(Object data) {
    
    if (!setValue(Caches.Data,data)) {
      add(Caches.Data,data);
    }
  }
  
  public Value getHeaders() {
    
    return getValue(Caches.Headers);
  }

  public void setHeaders(Object headers) {
    
    if (!setValue(Caches.Headers,headers)) {
      add(Caches.Headers,headers);
    }
  }
  
  public void setLangId(Value langId) {
    
    if (!setValue(Caches.LangId,langId)) {
      add(Caches.LangId,langId);
    }
  }
  
  private boolean getHeaders(Map<String,String> headers) {

    String s = getHeaders().asString();
    String[] lines = s.split(Utils.getLineSeparator());
    for (String line: lines) {
      String[] vars = line.split(":",2);
      String name = "";
      String value = "";
      if (vars.length>0) {
        name = vars[0];
      }
      if (vars.length>1) {
        value = vars[1];
      }
      if (!name.equals("")) {
        headers.put(name,value);
      }
    }
    return headers.size()>0;
  }
  
  public boolean trySend() {

    byte[] bytes = getData().asBytes();
    boolean ret = getEcho().write(bytes);
    if (ret) {
      if (isNotNull(path)) {
        path.setContentHeaders(null,bytes.length);
      }
      Map<String,String> headers = new HashMap<>();
      if (getHeaders(headers)) {
        for (Map.Entry<String,String> entry: headers.entrySet()) {
          String name = entry.getKey();
          String value = entry.getValue();
          if (name.toUpperCase().equals("Content-Type".toUpperCase())) {
            getResponse().setContentType(value);
          } else {
            getResponse().setHeader(name,value);    
          }
        }
      }
    }
    return ret;
  }

  
}
