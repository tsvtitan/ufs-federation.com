package ufsic.scheme;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import ufsic.providers.DataSet;

import ufsic.providers.FieldNames;
import ufsic.providers.Filter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.utils.Utils;

public class Dictionary extends HashMap<String,String> {

  public final static String TableName = "DICTIONARY";
  
  private final static String DictionaryId = "DICTIONARY_ID";
  private final static String Description = "DESCRIPTION";
  private final static String Locked = "LOCKED";
  
  private final ArrayList<String> cache = new ArrayList<>();
  private final ArrayList<String> stack = new ArrayList<>();
  
  private final Provider provider;
  private final String langField;
  
  private String prefix;
  
  public Dictionary(Scheme scheme, String name, String prefix) {
    
    this.provider = scheme.getProvider();
    this.prefix = prefix;
    
    if (Utils.isNotNull(scheme.getLang())) {
      
      this.langField = scheme.getLang().getLangId().asString();

      refreshList(String.format("%s%s",SchemeTable.ViewPrefix,name));
      
    } else {
      this.langField = null;
    }
  }

  public Dictionary(Scheme scheme, String prefix) {
    
    this(scheme,TableName,prefix);
  }
  
  public Dictionary(Scheme scheme) {
    
    this(scheme,TableName,"#");
  }
  
  public String getPrefix() {
    return prefix;
  }
  
  private String getRelpaced(String value, Map<String,String> map, String missKey) {
  
    String ret = value;
    for (Map.Entry<String,String> entry: map.entrySet()) {
      String key = entry.getKey();
      if (!Utils.equals(key,missKey)) {
        String v = entry.getValue();
        ret = ret.replaceAll(Matcher.quoteReplacement(key),Matcher.quoteReplacement(v));
      }
    }
    return ret;
  }
  
  private void setValue(String value, Map<String,String> map, String parentKey) {
    
    String s = value;
    //Pattern p = Pattern.compile(String.format("\\%s\\{[\\w|\\.]{1,100}\\}",prefix));
    Pattern p = Pattern.compile(String.format("\\%s[\\w|\\.]{1,100}",prefix));
    Matcher m = p.matcher(value);
    while (m.find()) {
      String k = m.group(0);
      String key = k.replace("{","").replace("}","");
      if (map.containsKey(key)) {
        if (!stack.contains(key)) {
          try {
            stack.add(key);
            setValue(map.get(key),map,key);
            s = s.replaceAll(Matcher.quoteReplacement(k),Matcher.quoteReplacement(map.get(key)));
          } finally {
            stack.remove(key);
          }
        } else {
          s = s.replaceAll(Matcher.quoteReplacement(key),Matcher.quoteReplacement(key.substring(prefix.length())));
        }
      } 
    }
    if (!cache.contains(parentKey)) {
      map.put(parentKey,s);
      cache.add(parentKey);
    }
  }
  
  private void refreshList(String name) {

    FieldNames fn = new FieldNames(DictionaryId,Description,langField.toUpperCase());
    Filter f = new Filter(Locked,null);
    DataSet ds = provider.select(name,fn,f);
    if (Utils.isNotNull(ds)) { 

      Map<String,String> temp = new HashMap<>();
      
      for (Record r: ds) {
        put(prefix+r.getValue(DictionaryId).asString(),r.getValue(langField).asString());
      }
      
      temp.putAll(this);
      
      for (Map.Entry<String,String> entry: temp.entrySet()) {
        String key = entry.getKey();
        if (!cache.contains(key)) {
          setValue(entry.getValue(),temp,key);
        }
      }
      
      clear(); 
      putAll(temp);
      temp.clear();
    }
  }

  public String replace(String raw) {
    
    //"\\$[\\w]{1,100}"
    String ret = raw;
    if (Utils.isNotNull(ret)) {
      for (Map.Entry<String,String> entry: entrySet()) {
        String key = entry.getKey();
        ret = ret.replaceAll(Matcher.quoteReplacement(key),Matcher.quoteReplacement(entry.getValue()));
      }
    }
    return ret;
  }
  
}
