package ufsic.contexts;

import java.util.Map;

public interface IGlobalVarContext {

  public Map<String,Object> getGlobalVars();
  public void setGlobalVar(String name, Object value);
  public Object getGlobalVar(String name);
  public boolean globalExists(String name);
  
}
