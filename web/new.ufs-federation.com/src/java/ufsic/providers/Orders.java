package ufsic.providers;

import java.util.ArrayList;

public class Orders extends ArrayList<Order> {

  public Orders() {
    super();
  }
  
  public Orders(String... args) {
    super();
    for (String s: args) {
      add(new Order(s,Order.Type.ASC));
    }
  }
  
  public Orders(String name, Order.Type type) {
    super();
    Order o = new Order(name,type);
    this.add(o);

  }
  
  public Orders Add(String name, Order.Type type) {
    
    Order o = new Order(name,type);
    this.add(o);
    return this;
  }

  public Orders Add(String name) {
    
    return Add(name,Order.Type.ASC);
  }
  
  public String getString(IProvider provider, String delim, String prefix) {
    
    StringBuilder sb = new StringBuilder();
    boolean first = false;
    
    for (Order o: this) {
        
      String s = o.getString(provider);
      if (!first) {
        first = !s.equals("");
        if (first) {
          sb.insert(0,prefix).append(s);
        }
      } else {
        sb.append(delim).append(prefix).append(s);
      }
    }
    String ret = sb.toString();
    return ret.trim();
  
  }  
  
}