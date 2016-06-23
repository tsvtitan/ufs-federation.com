package ufsic.contexts;

public interface IVarContext extends IGlobalVarContext {
  
  public void setLocalVar(String name, Object value);
  public Object getLocalVar(String name);
  public boolean localExists(String name);
}
