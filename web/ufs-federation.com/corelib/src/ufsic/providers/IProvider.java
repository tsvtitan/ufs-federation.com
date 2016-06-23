package ufsic.providers;

public interface IProvider {
  
  public String quote(String s);
  public String quoteName(String name);
  public String getValueString(Value value, String prefix, String suffix);
  
}