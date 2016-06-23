package ufsic.providers;

import java.util.HashMap;
import java.util.Map.Entry;

public class GroupFilter extends Filter {

  private final HashMap<Filter,Filter.Operator> list = new HashMap<Filter,Filter.Operator>();
  
  public GroupFilter() {
    super();
  }

  public GroupFilter(Filter filter) {
    super();
    list.put(filter,Filter.Operator.AND);
  }
  
  public GroupFilter And(Filter filter) {
    
    list.put(filter,Filter.Operator.AND);
    return this;
  }

  public GroupFilter Or(Filter filter) {
    
    list.put(filter,Filter.Operator.OR);
    return this;
  }
  
  @Override
  public String getString(IProvider provider) {
    
    StringBuilder sb = new StringBuilder();
    String def = super.getString(provider);
    
    boolean first = false;
    if (!def.equals("")) {
      sb.insert(0,def);
      first = true;
    }
    
    for (Entry<Filter,Filter.Operator> entry: list.entrySet()) {
        
      String s = entry.getKey().getString(provider);
      String op = Filter.Operator.asString(entry.getValue()); 
      if (!first) {
        first = !s.equals("");
        if (first) {
          sb.insert(0,s);
        }
      } else {
        sb.append(String.format(" %s ",op)).append(s);
      }
    }
    String ret = sb.toString();
    if (!ret.equals("")) {
      ret = String.format("(%s)",ret);
    }
    return ret.trim();
  }
 
  @Override
  public boolean isEmpty() {
    
    boolean ret = list.isEmpty();
    if (ret) {
      boolean r = true;
      for (Entry<Filter,Filter.Operator> entry: list.entrySet()) {
        Filter f = entry.getKey();
        r = r & f.isEmpty();
      }
      ret = ret & r;
    }
    return ret;
  }
  
}
