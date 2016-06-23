package ufsic.providers;

import java.util.*;
import ufsic.utils.Utils;

public class Filter {

  public enum Condition {
  
    DISABLED, EQUAL, NOT_EQUAL, LESS, LESS_OR_EQUAL, GREATER, GREATER_OR_EQUAL, IS_NULL, IS_NOT_NULL, LIKE;
    
    public static String asString(Condition condition) {
      
      String ret = "";
      switch (condition) {
        case DISABLED: { break; }
        case EQUAL: { ret = "="; break; }
        case NOT_EQUAL: { ret = "<>"; break; }
        case LESS: { ret = "<"; break; }
        case LESS_OR_EQUAL: { ret = "<="; break; }
        case GREATER: { ret = ">"; break; }
        case GREATER_OR_EQUAL: { ret = ">="; break; }
        case IS_NULL: { ret = "IS NULL"; break; }
        case IS_NOT_NULL: { ret = "IS NOT NULL"; break; }
        case LIKE: { ret = "LIKE"; break; }
        default: { break; }
      }
      return ret;
    }
    
    public static Condition asCondition(String ident, Condition def) {
      
      Condition ret = def;
      switch (ident.toUpperCase()) {
        case "": { ret = DISABLED; break; }
        case "=": { ret = EQUAL; break; }
        case "<>": { ret = NOT_EQUAL; break; }
        case "<": { ret = LESS; break; }
        case "<=": { ret = LESS_OR_EQUAL; break; }
        case ">": { ret = GREATER; break; }
        case ">=": { ret = GREATER_OR_EQUAL; break; }
        case "IS NULL": { ret = IS_NULL; break; }
        case "IS NOT NULL": { ret = IS_NOT_NULL; break; }
        case "LIKE": { ret = LIKE; break; }
        default: { break; }
      }
      return ret;
    }
    
    public static Condition asCondition(String ident) {
      return asCondition(ident,DISABLED);
    }

  }

  public enum Operator {
    
    NONE, AND, OR;

    public static String asString(Operator operator) {
      
      String ret = "";
      switch (operator) {
        case NONE: { break; }
        case AND: { ret = "AND"; break; }
        case OR: { ret = "OR"; break; }
        default: { break; }
      }
      return ret;
    }
  }
  
  public class FilterField extends Field {

    private final Filter filter;
    public Condition condition=Condition.DISABLED;
    public Operator operator=Operator.NONE;
    
    public FilterField(Filter filter, String name) {
      super(name,null);
      this.filter = filter;
    }
    
    public FilterField(Filter filter, String name, Condition condition, Object value, Operator operator) {
      
      super(name,Value.getValueBy(value));
      this.filter = filter;
      this.condition = condition;
      this.operator = operator;
    }
    
    private Filter getFilter(Object value, Condition condition) {
      this.setValue(Value.getValueBy(value));
      this.condition = condition;
      return filter;
    }
    
    public Filter Equal(Object value) {
      return getFilter(value,Condition.EQUAL);
    }

    public Filter NotEqual(Object value) {
      return getFilter(value,Condition.NOT_EQUAL);
    }

    public Filter Less(Object value) {
      return getFilter(value,Condition.LESS);
    }

    public Filter LessOrEqual(Object value) {
      return getFilter(value,Condition.LESS_OR_EQUAL);
    }

    public Filter Greater(Object value) {
      return getFilter(value,Condition.GREATER);
    }

    public Filter GreaterOrEqual(Object value) {
      return getFilter(value,Condition.GREATER_OR_EQUAL);
    }

    public Filter IsNull() {
      return getFilter(null,Condition.IS_NULL);
    }

    public Filter IsNotNull() {
      return getFilter(null,Condition.IS_NOT_NULL);
    }
    
    public Filter Like(Object value) {
      return getFilter(value,Condition.LIKE);
    }

    public Filter NotLike(Object value) {
      return getFilter(value,Condition.NOT_EQUAL);
    }
    
    public String getString(IProvider provider) {

      String s1 = provider.quoteName(getName());
      String s2 = Condition.asString(condition);
      String s3;

      Value v = getValue();
        
      if ((condition==Condition.IS_NULL) || (condition==Condition.IS_NOT_NULL)) {
        s3 = "";
      } else {
        if (v.isNotNull()) {
          if (condition==Condition.LIKE) {
            s3 = provider.getValueString(v,"","%");
          } else {
            s3 = provider.getValueString(v,"","");
          }
        } else {
          s2 = Condition.asString(Condition.IS_NULL);
          s3 = "";
        }
      }
      
      //boolean need_quote = (v.isNotNull())?(v.sameClass(String.class)):false;
      boolean flag = (condition!=Condition.DISABLED);

      /*if (need_quote && (provider!=null)) {
        s3 = provider.quote(s3);
      }*/

      String ret = "";
      if (flag) {
        ret = String.format("%s %s %s",s1,s2,s3);  
      }
      return ret.trim();
    }

    public void isNull() {
      throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }
  }
  
  protected ArrayList<FilterField> list = new ArrayList<>();
  
  public Filter() {
    super();  
  }
  
 /* public Filter(Condition condition, Map<String,Object>... args) {
    super();  
    for (Map<String,Object> entry: args) {
      list.add(new FilterField(entry.get(entry)));
    } 
  }*/
  
  private Condition getDefaultCondition(Object value, Condition con) {
    
    Condition ret = con;
    if (Utils.isNotNull(value)) {
      
      if (value instanceof Value) {
        if (((Value)value).isNull()) {
          ret = Condition.IS_NULL;
        }
      }
    } else {
      ret = Condition.IS_NULL;
    }
    return ret;
  }
  
  public Filter(String name, Object value) {
    super();
    list.add(new FilterField(this,name,getDefaultCondition(value,Condition.EQUAL),value,Operator.NONE));
  }
  
  public Filter(String name, Condition condition, Object value) {
    
    super();
    list.add(new FilterField(this,name,getDefaultCondition(value,condition),value,Operator.NONE));
  }
  
  public Filter Or(String name, Condition condition, Object value) {
    
    list.add(new FilterField(this,name,getDefaultCondition(value,condition),value,Operator.OR));
    return this;
  }
  
  public Filter Or(String name, Object value) {
    
    return Or(name,getDefaultCondition(value,Condition.EQUAL),value);
  }

  public FilterField Or(String name) {
    
    FilterField ff = new FilterField(this,name);
    ff.operator = Operator.OR;
    list.add(ff);
    return ff;
  }
  
  public Filter And(String name, Condition condition, Object value) {
    
    list.add(new FilterField(this,name,getDefaultCondition(value,condition),value,Operator.AND));
    return this;
  }

  public Filter And(String name, Object value) {
    
    return And(name,getDefaultCondition(value,Condition.EQUAL),value);
  }

  public FilterField And(String name) {
    
    FilterField ff = new FilterField(this,name);
    ff.operator = Operator.AND;
    list.add(ff);
    return ff;
  }
  
  public FilterField Add(String name) {

    FilterField ff = new FilterField(this,name);
    list.add(ff);
    return ff;
  }
  
  public String getString(IProvider provider) {
  
    StringBuilder sb = new StringBuilder();
    boolean first = false;
    
    for (FilterField ff: list) {
        
      String s = ff.getString(provider);
      String op = Filter.Operator.asString(ff.operator); 
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
  
  public boolean isEmpty() {
    
    return list.isEmpty();
  }
  
}
