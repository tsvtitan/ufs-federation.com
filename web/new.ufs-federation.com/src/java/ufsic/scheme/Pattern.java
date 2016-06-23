package ufsic.scheme;

import java.io.ByteArrayInputStream;
import java.io.InputStream;
import java.util.HashMap;
import java.util.Map;
import ufsic.contexts.IGlobalVarContext;
import ufsic.contexts.IVarContext;
import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.utils.Location;

public class Pattern extends SchemeRecord {

  private InputStream stream = null;
  private final HashMap<String,Object> vars = new HashMap<>();
  
  public Pattern(Scheme scheme) {
    super(scheme);
  }
  
  public Pattern(SchemeTable table, Record record) {
    super(table, record);
  }

  public Pattern(SchemeTable table, boolean autoLoad) {
    super(table);
    if (autoLoad) {
      load();
    }
  }
  
  public Pattern(SchemeTable table) {
    this(table,true);
  }
  
  private boolean load(String patternId) {
    
    boolean ret = false;
    Object pid = patternId;
    if (isNull(pid)) {
      pid = getPatternId().asObject();
    }
    if (isNull(pid)) {
      pid = getClass().getSimpleName();
    }
    if (isNotNull(pid)) {
      
      Record r = getTable().findFirst(Patterns.PatternId,pid);
      if (isNotNull(r)) {
        copyFrom(r);
        ret = true;
      } else {
        ret = select(new Filter(Patterns.PatternId,pid));  
        if (ret) {
          getTable().add(this);
        }
      }
    }
    return ret;
  }
  
  private boolean load() {
    
    return load(null);
  }
  
  public Value getPatternId() {
    
    return getValue(Patterns.PatternId);
  }
  
  public Value getName() {
    
    return getValue(Patterns.Name);
  }

  public Value getDescription() {
    
    return getValue(Patterns.Description);
  }
  
  public Value getPatternType() {
    
    return getValue(Patterns.PatternType);
  }

  public Value getSubject() {
    
    return getValue(Patterns.Subject);
  }
  
  public Value getBody() {
    
    return getValue(Patterns.Body);
  }

  public void setBody(Value body) {
    
    if (!setValue(Patterns.Body,body)) {
      add(Patterns.Body,body);
    }
  }

  public void setBody(Object body) {

    setBody(new Value(body));
  }
  
  public Value getPriority() {
    
    return getValue(Patterns.Priority);
  }
  
  public Value getLocked() {
    
    return getValue(Patterns.Locked);
  }

  protected byte[] getBodyByLocation() {
    
    byte[] ret = new byte[] {};
    try {
      byte[] bytes = getValue(Patterns.Body).asBytes();
      if (bytes.length>0) {
        Location loc = new Location(new String(bytes),getScheme().getPatternDir());
        if (isNotNull(loc)) {
          if (loc.exists()) {
            ret = loc.getBytes();
          } else {
            ret = bytes;
          }
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  public InputStream getBodyStream() {
  
    ByteArrayInputStream ret;
    if (isNotNull(stream)) {
      ret = (ByteArrayInputStream)stream;
    } else {
      ret = new ByteArrayInputStream(getBodyByLocation());
      stream = ret;
    }
    return ret;
  }

  public void process(IGlobalVarContext context) {
    
    context.setGlobalVar("pattern",this);
  }
  
  public String getContentType() {
    return null;
  }
  
  protected final Map<String,Object> getVars() {
    
    return vars;
  }
  
  public void setVar(String name, Object value) {
    
    vars.put(name,value);
  }
  
  public String parseBody() {
    
    return getBody().asString();
  }
  
}
