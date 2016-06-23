package ufsic.providers;

import java.util.ArrayList;

public class FieldNames extends ArrayList<String> {

  public FieldNames(String... args) {

    for (String s: args) {
      add(s);
    }
  }

  public String getString(IProvider provider, String delim, String prefix) {
    
    StringBuilder sb = new StringBuilder();
    boolean first = false;
    
    for (String s: this) {
        
      if (!first) {
        first = !s.equals("");
        if (first) {
          sb.insert(0,prefix).append(provider.quoteName(s));
        }
      } else {
        sb.append(delim).append(prefix).append(provider.quoteName(s));
      }
    }
    String ret = sb.toString();
    return ret.trim();
  
  }  
}
