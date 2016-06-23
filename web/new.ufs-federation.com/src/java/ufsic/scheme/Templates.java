package ufsic.scheme;

import java.lang.reflect.Constructor;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Templates extends SchemeTable {

  public final static String TableName = "TEMPLATES";
  
  public final static String TemplateId = "TEMPLATE_ID";
  public final static String Name = "NAME";
  public final static String Description = "DESCRIPTION";
  public final static String TemplateType = "TEMPLATE_TYPE";
  public final static String Css = "CSS";
  public final static String Js = "JS";
  public final static String Html = "HTML";
  public final static String Priority = "PRIORITY";
  public final static String Locked = "LOCKED";
  
  public Templates(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Templates(Scheme scheme) {
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {
    
    return Template.class; 
  }

  public Template newTemplate(Record record, Value templateType) {
    
    Template ret = null;
    Class cls = Template.class;
    try {
      if (isNotNull(templateType) && templateType.isNotNull()) {
        String pkg = Templates.class.getPackage().getName();
        String name = String.format("%s.templates.%s",pkg,templateType.asString());
        cls = Templates.class.getClassLoader().loadClass(name);
      }
      if (isNotNull(cls)) {
        Constructor con = cls.getConstructor(SchemeTable.class,Record.class);
        if (isNotNull(con)) {
          ret = (Template)con.newInstance(this,record);
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
  
  public Template newTemplate(Record record) {
    
    return newTemplate(record,null);
  }
  
  public boolean exists(String templateId) {
    
    Record r = findFirst(TemplateId,templateId);
    return isNotNull(r);
  }
  
}
