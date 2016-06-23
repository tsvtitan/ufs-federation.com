package ufsic.scheme.forms;

import ufsic.scheme.Path;
import ufsic.utils.Utils;

public class Requirement extends FormObject {

  private String message = null;
  private String name = null;
  private Method method = null;

  public Requirement(Form form, String ident) {

    super(form);
    
    String[] tmp = ident.split(":");
    if (tmp.length>0) {

      String s = null;
      if (tmp.length>1) {
        this.message = tmp[0].trim();
        s = tmp[1];  
      } else {
        this.message = ident;
      }

      if (isNotNull(s)) {

        String[] nt = s.split("=");
        if (nt.length>1) {

          this.name = nt[0].trim();
          this.method = new Method(getScheme(),nt[1].trim());

        } else {
          this.name = s;
        }
      }
    }
  }

  public String getMessage() {
    return message;
  }

  public String getName() {
    return name;
  }

  public Method getMethod() {
    return method;
  }

  public boolean isNotEmpty(Object obj, MethodParams params) {

    boolean ret = true;
    if (isNotNull(obj) && (obj instanceof String)) {
      ret = !obj.toString().equals("");
    }
    return ret;
  }

  public boolean isEmpty(Object obj, MethodParams params) {

    return !isNotEmpty(obj,params);
  }
  
  public boolean isRequired(Object obj, MethodParams params) {

    return isNotEmpty(obj,params);
  }

  public boolean isNotRequired(Object obj, MethodParams params) {

    return !isNotEmpty(obj,params);
  }

  public boolean isEmail(Object obj, MethodParams params) {

    boolean ret = true;
    if (isNotNull(obj) && (obj instanceof String)) {
      ret = Utils.isEmail(obj.toString());
    }
    return ret;
  }

  public boolean isPhone(Object obj, MethodParams params) {

    boolean ret = true;
    if (isNotNull(obj) && (obj instanceof String)) {
      ret = Utils.isPhone(obj.toString());
    }
    return ret;
  }

  public boolean isEmailOrPhone(Object obj, MethodParams params) {

    return isEmail(obj,params) || isPhone(obj,params);
  }
  
  /*private boolean isLength(Object obj, MethodParams params) {

    boolean ret = true;
    if (isNotEmpty(obj,params) && isNotNull(maxLength)) {
      int maxL = Integer.parseInt(maxLength);
      ret = maxL>=value.asString().length();
    }
    return ret;
  }*/

  private boolean isDate(Object obj, MethodParams params) {

    boolean ret = true;
    if (isNotEmpty(obj,params) && isNotNull(params) && !params.isEmpty()) {
      String fmt = params.get(0).getValue();
      ret = Utils.isDate(obj,fmt);
    }
    return ret;
  }

  private boolean isCaptcha(Object obj, MethodParams params) {

    boolean ret = false;
    if (isNotNull(obj) && (obj instanceof String)) {
      
      Path path = getScheme().getPath();
      if (isNotNull(path)) {

        String captcha = path.getCaptcha(getForm().getFieldId(name));
        if (obj.equals(captcha)) {
          ret = true;
        }
      }
    }
    return ret;
  }
  
  public boolean check(Object obj) {

    boolean ret = true;
    if (isNull(method)) {
      ret = isNotEmpty(obj,null);
    } else {

      try {
        String name = method.getName();
        java.lang.reflect.Method m = this.getClass().getDeclaredMethod(name,Object.class,MethodParams.class);
        if (isNotNull(m)) {
          Object r = m.invoke(this,obj,method.getParams());
          if (isNotNull(r)) {
            ret = (boolean)r;
          }
        }
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
 
}