package ufsic.providers;

public class Order {
  
  public enum Type {
    
    NONE, ASC, DESC;
    
    public static String asString(Type type) {
      
      String ret = "";
      switch (type) {
        case NONE: { break; }
        case ASC: { ret = "ASC"; break; }
        case DESC: { ret = "DESC"; break; }
        default: { break; }
      }
      return ret;
    }
    
  }
  
  private final String name;
  private final Type type;

  public Order(String name, Type type) {
    this.name = name;
    this.type = type;
  }
  
  public Order(String name) {
    this(name,Type.ASC);
  }

  public String getString(IProvider provider) {
    
    String ret = String.format("%s %s",provider.quoteName(name),Type.asString(type));
    return ret.trim();
  
  }  

}
