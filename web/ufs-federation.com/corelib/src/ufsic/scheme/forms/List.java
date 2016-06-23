package ufsic.scheme.forms;

import java.util.ArrayList;
import java.util.Arrays;
import ufsic.providers.Dataset;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.Agreement;
import ufsic.scheme.Agreements;
import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeObject;
import ufsic.utils.Utils;


class Item {
  
  private Value value = null;
  private Value text = null;
  private Value style = null;
  private boolean selected = false;

  public Item(Value value, Value text, Value style) {

    this.value = value;
    this.text = text;
    this.style = style;
  }

  public Item(String text) {

    this(new Value(text),new Value(null),new Value(null));
  }

  public Item() {

    this(null);
  }

  protected void setValue(Value value) {
    this.value = value;
  }

  public String getValue() {

    String ret = null;
    if (Utils.isNotNull(value) && value.isNotNull()) {
      ret = value.asString();
    }
    return ret;
  }

  protected void setText(Value text) {
    this.text = text;
  }

  public String getText() {

    String ret;
    if (Utils.isNotNull(text) && text.isNotNull()) {
      ret = text.asString();
    } else {
      ret = getValue();
    }
    return ret;
  }

  protected void setStyle(Value style) {
    this.style = style;
  }

  public String getStyle() {

    String ret = null;
    if (Utils.isNotNull(style) && style.isNotNull()) {
      ret = style.asString();
    }
    return ret;
  }

  protected void setSelected(boolean selected) {
    this.selected = selected;
  }

  public boolean getSelected() {

    return selected;
  }
  
  protected boolean sameValue(Object value) {
    
    boolean ret = false;
    if (Utils.isNotNull(this.value)) {
      ret = this.value.same(value);
    }
    return ret;
  }
}
        
class Items extends ArrayList<Item> {

  public Items() {
    super();
  }
  
  protected void setDefault(Object value) {

    for (Item item: this) {

      if (Utils.isNotNull(value) && value.getClass().isArray()) {

        String[] arr = (String[])value;
        boolean exists = Arrays.asList(arr).contains(item.getValue());
        item.setSelected(exists);
      } else {
        item.setSelected(item.sameValue(value));
      }
    }
  }
  
}

public class List extends SchemeObject {

  private String name = null;
  private Method method = null;
  private String list = null;
  private Items items = new Items();
  private Object def = null;
  private boolean isSelect = false;
  
  public List(Scheme scheme, String ident) {
    
    super(scheme);
    
    String[] tmp = ident.split("=");
    if (tmp.length>1) {

      this.name = tmp[0].trim();
      this.list = tmp[1].trim();
      
      isSelect = scheme.getProvider().isSelect(list);
      if (!isSelect) {
        this.method = new Method(scheme,list);
      }

    } else {
      this.name = ident;
      this.method = null;
    }
    
  }
  
  String getName() {
    return name;
  }

  Method getMethod() {
    return method;
  }
  
  private void Agreements(MethodParams params) {
    
    Account a = getScheme().getAccount();
    if (isNotNull(a)) {
      Agreements ags = a.getAgreements();
      for (Record r: ags) {
        Agreement ag = (Agreement)r;
        items.add(new Item(ag.getAgreementId(),ag.getName(),null));
      }
    }
    
  }
  
  public Items getItems(boolean needEmpty) {
    
    Items ret = items;
    if (items.isEmpty()) {
      
      if (needEmpty) {
        items.add(new Item());
      }
      
      if (isSelect) {
        
          Provider p = getScheme().getProvider();
        
          String sql = p.getWrappedSelectSql(list);
          Dataset<Record> ds = p.querySelect(sql);
          if (isNotNull(ds)) {
            
            for (Record r: ds) {
              
              Item item = new Item();
              
              for (ufsic.providers.Field f: r) {
                
                int index = r.indexOf(f);
                switch (index) {
                  case 1: {
                    item.setValue(f.getValue());
                    break;
                  }
                  case 2: {
                    item.setText(f.getValue());
                    break;
                  }
                  case 3: {
                    item.setStyle(f.getValue());
                    break;
                  }
                }
              }
              items.add(item);
            }
          }
        
      } else {
        
        if (isNotNull(method)) {
        
          try {
            String name = method.getName();
            java.lang.reflect.Method m = this.getClass().getDeclaredMethod(name,MethodParams.class);
            if (isNotNull(m)) {
              m.invoke(this,method.getParams());
            }
          } catch (Exception e) {
            logException(e);
          }
          
        } else {
        
          String[] tmp = list.split("\\|");
          if (tmp.length>0) {
            
            for (String s: tmp) {
              items.add(new Item(s));
            }
          }
          
        }
      }
      
      items.setDefault(def);
    }
    return items;
  }
  
  public Items getItems() {
    return getItems(false);
  }
  
  public void setDefault(Object value) {
    this.def = value;
    items.setDefault(value);
  }
  
}
