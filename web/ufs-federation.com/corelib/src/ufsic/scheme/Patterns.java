package ufsic.scheme;

import java.lang.reflect.Constructor;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Patterns extends SchemeTable<Pattern> {

  public final static String TableName = "PATTERNS";
  
  public final static String PatternId = "PATTERN_ID";
  public final static String Name = "NAME";
  public final static String Description = "DESCRIPTION";
  public final static String PatternType = "PATTERN_TYPE";
  public final static String Subject = "SUBJECT";
  public final static String Body = "BODY";
  public final static String Priority = "PRIORITY";
  public final static String Locked = "LOCKED";
  
    
  public Patterns(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Patterns(Scheme scheme) {
    super(scheme,TableName);
  }
  
  
  public Patterns() {
    super();
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {

    return Pattern.class;
  }
  
  public Pattern newPattern(Record record, Value patternType) {
    
    Pattern ret = null;
    Class cls = Pattern.class;
    try {
      if (isNotNull(patternType) && patternType.isNotNull()) {
        String pkg = Patterns.class.getPackage().getName();
        String name = String.format("%s.patterns.%s",pkg,patternType.asString());
        cls = Patterns.class.getClassLoader().loadClass(name);
      }
      if (isNotNull(cls)) {
        Constructor con = cls.getConstructor(SchemeTable.class,Record.class);
        if (isNotNull(con)) {
          ret = (Pattern)con.newInstance(this,record);
          if (isNotNull(con)) {
            this.add(ret);
          }
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  public Pattern newPattern(Record record) {
    
    return newPattern(record,null);
  }
  
}
