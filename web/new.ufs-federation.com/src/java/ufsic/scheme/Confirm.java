package ufsic.scheme;

import java.io.ByteArrayOutputStream;
import java.io.PrintWriter;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Map.Entry;
import java.util.Properties;
import ufsic.providers.Filter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Confirm extends SchemeRecord {

  
  public class Param {
    
    private String name = null;
    private String value = null;
    
    public Param(String name, String value) {
      this.name = name;  
      this.value = value;
    }
    
    public String getName() {
      return name;
    }
    
    public String getValue() {
      return value;
    }
  }
  
  public class Params extends ArrayList<Param> {
     
    private Param find(String name) {
      
      Param ret = null;
      for (Param p: this) {
        String n = p.getName();
        if (n.equalsIgnoreCase(name)) {
          ret = p;
          break;
        }
      }
      return ret;
    }

    public String getValue(String name, String def) {

      String ret = def;
      Param p = find(name);
      if (isNotNull(p)) {
        ret = p.getValue();
      }
      return ret;
    }
    
    public String getValue(String name) {

      return getValue(name,null);
    }
    
    protected Param add(String name, String value) {
      
       Param ret = new Param(name,value);
       this.add(ret);
       return ret;
    }
    
    protected void refresh(Value params) {
      
      if (params.isNotNull()) {
        clear();
        Properties props = params.asProperties();
        for (Entry<Object,Object> entry: props.entrySet()) {
          add(entry.getKey().toString(),entry.getValue().toString());
        }
      }
    }
    
    public String asProperties() {
      
      String ret = null;
      if (!isEmpty()) {
        Properties props = new Properties();
        for (Param p: this) {
          String s = null;
          Object value = p.getValue();
          if (isNotNull(value)) {
            s = value.toString();
          }
          props.setProperty(p.getName(),s);
        }
        ByteArrayOutputStream stream = new ByteArrayOutputStream();
        try {
          props.store(stream,null);
          ret = stream.toString();
        } catch (Exception e) {
          ret = e.getMessage();
        }  
      }
      return ret;
    }
  }
  
  private final Params params = new Params();
  
  public Confirm(Scheme scheme) {
    super(scheme);  
  }
  
  public Confirm(SchemeTable table, Record record) {
    super(table, record);
  }

  public Confirm(SchemeTable table) {
    super(table, null);
  }
  
  public Value getConfirmId() {
    
    return getValue(Confirms.ConfirmId);
  }
  
  public void setConfirmId(Value confirmId) {
    
    if (!setValue(Confirms.ConfirmId,confirmId)) {
      add(Confirms.ConfirmId,confirmId);
    }
  }

  public Value getSessionId() {
    
    return getValue(Confirms.SessionId);
  }
  
  public void setSessionId(Value sessionId) {
    
    if (!setValue(Confirms.SessionId,sessionId)) {
      add(Confirms.SessionId,sessionId);
    }
  }

  public void setSessionId(Object sessionId) {

    setSessionId(new Value(sessionId));
  }
  
  public Value getAccountId() {
    
    return getValue(Confirms.AccountId);
  }
  
  public void setAccountId(Value accountId) {
    
    if (!setValue(Confirms.AccountId,accountId)) {
      add(Confirms.AccountId,accountId);
    }
  }

  public Value getMessageId() {
    
    return getValue(Confirms.MessageId);
  }
  
  public void setMessageId(Value messageId) {
    
    if (!setValue(Confirms.MessageId,messageId)) {
      add(Confirms.MessageId,messageId);
    }
  }

  public Value getCreated() {
    
    return getValue(Confirms.Created);
  }
  
  public void setCreated(Value created) {
    
    if (!setValue(Confirms.Created,created)) {
      add(Confirms.Created,created);
    }
  }
  
  public Value getBegin() {
    
    return getValue(Confirms.Begin);
  }
  
  public void setBegin(Value begin) {
    
    if (!setValue(Confirms.Begin,begin)) {
      add(Confirms.Begin,begin);
    }
  }

  public void setBegin(Timestamp begin) {
   
    setBegin(new Value(begin));
  }
  
  public Value getEnd() {
    
    return getValue(Confirms.End);
  }
  
  public void setEnd(Value end) {
    
    if (!setValue(Confirms.End,end)) {
      add(Confirms.End,end);
    }
  }

  public void setEnd(Timestamp end) {
   
    setEnd(new Value(end));
  }

  public Value getLocked() {
    
    return getValue(Confirms.Locked);
  }
  
  public void setLocked(Value locked) {
    
    if (!setValue(Confirms.Locked,locked)) {
      add(Confirms.Locked,locked);
    }
  }

  public void setLocked(Timestamp locked) {
   
    setLocked(new Value(locked));
  }

  public Value getCode() {
    
    return getValue(Confirms.Code);
  }
  
  public void setCode(Value code) {
    
    if (!setValue(Confirms.Code,code)) {
      add(Confirms.Code,code);
    }
  }

  public void setCode(Object code) {

    setCode(new Value(code));
  }
  
  public Value getConfirmType() {
    
    return getValue(Confirms.ConfirmType);
  }
  
  protected void setConfirmType(Value confirmType) {
    
    if (!setValue(Confirms.ConfirmType,confirmType)) {
      add(Confirms.ConfirmType,confirmType);
    }
  }

  protected void setConfirmType(Object confirmType) {
    
    setConfirmType(new Value(confirmType));
  }
  
  protected void setParams(Value params) {
    
    if (!setValue(Confirms.Params,params)) {
      add(Confirms.Params,params);
    }
  }

  protected void setParams(Object params) {

    setParams(new Value(params));
  }
  
  public Value getConfirmed() {
    
    return getValue(Confirms.Confirmed);
  }
  
  public void setConfirmed(Value confirmed) {
    
    if (!setValue(Confirms.Confirmed,confirmed)) {
      add(Confirms.Confirmed,confirmed);
    }
  }

  public Value getPathId() {
    
    return getValue(Confirms.PathId);
  }
  
  public void setPathId(Value pathId) {
    
    if (!setValue(Confirms.PathId,pathId)) {
      add(Confirms.PathId,pathId);
    }
  }

  public void setPathId(String pathId) {

    setPathId(new Value(pathId));
  }
  
  public Value getRecipientContact() {
    
    return getValue(Confirms.RecipientContact);
  }

  public Value getPath() {
    
    return getValue(Confirms.Path);
  }
  
  protected Params getParams(boolean refresh) {
    
    if (refresh) {
      params.refresh(getValue(Confirms.Params));
    }
    return params;
  }
  
  protected Params getParams() {
    
    return getParams(params.isEmpty());
  }
  
  public Param addParam(String name, String value) {
    
    return params.add(name,value);
  }
  
  @Override
  public boolean save() {
    
    boolean ret;
    Value confirmId = getConfirmId();
    if (confirmId.isNull()) {
      Provider p = getProvider();
      setConfirmId(p.getUniqueId());
      setConfirmType(getClass().getSimpleName());
      setParams(params.asProperties());
      ret = insert();
    } else {
      ret = update(new Filter(Confirms.ConfirmId,confirmId));
    }
    return ret;
  }
  
  protected boolean setConfirmed() {
    
    boolean ret = false;

    Value confirmId = getConfirmId();
    Value confirmed = getConfirmed();
    if (confirmId.isNotNull() && confirmed.isNull()) {

      Value stamp = getProvider().getNow();
      Confirm c = new Confirm(getTable());
      c.setConfirmed(stamp);
      
      ret = c.update(new Filter(Confirms.ConfirmId,confirmId));
    }
    return ret;
  }

  public String getPathUrl() {
    
    String ret = null;
    Value path = getPath();
    if (path.isNotNull()) {
      ret = getScheme().getPath().buildURI(null,null,null,path.asString(),null,null,true);
    }
    return ret;
  }
  
  public boolean process(boolean redirect) {
    
    boolean ret = false;
    
    ret = setConfirmed();
    if (ret && redirect) {
      String url = getPathUrl();
      if (isNotNull(url)) {
        ret = getScheme().getPath().redirect(url);
      }
    }
    return ret;
  }
  
  public boolean process() {
    return process(true);
  }
  
  public String getUrl(String path) {
    
    String ret = null;
    Value confirmId = getConfirmId();
    if (confirmId.isNotNull()) {
      path = getScheme().getPath().getFullPath(path);
      ret = String.format("%s/%s",path,confirmId.asString());
    }
    return ret;
  }
  
}