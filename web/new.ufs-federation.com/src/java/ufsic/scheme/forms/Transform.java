package ufsic.scheme.forms;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeObject;
import ufsic.utils.Utils;

public class Transform extends SchemeObject {

  private String name = null;
  private Method method = null;

  public Transform(Scheme scheme, String ident) {

    super(scheme);
    
    String[] tmp = ident.split("=");
    if (tmp.length>1) {

      this.name = tmp[0].trim();
      this.method = new Method(scheme,tmp[1].trim());

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


  private Object toInteger(Object obj, MethodParams params) {

    Object ret = obj;
    if (isNotNull(obj)) {
      String fmt = params.get(0).getValue();
      ret = Utils.toDate(obj,fmt);
    }
    return ret;
  }

  private Object toDate(Object obj, MethodParams params) {

    Object ret = obj;
    if (isNotNull(obj) && isNotNull(params) && !params.isEmpty()) {
      String fmt = params.get(0).getValue();
      ret = Utils.toDate(obj,fmt);
    }
    return ret;
  }

  private Object toTimestamp(Object obj, MethodParams params) {

    Object ret = obj;
    if (isNotNull(obj) && isNotNull(params) && !params.isEmpty()) {
      String fmt = params.get(0).getValue();
      ret = Utils.toTimestamp(obj,fmt);
    }
    return ret;
  }


  public Object convert(Object obj) {

    Object ret = obj;

    if (isNotNull(method)) {
      try {
        String name = method.getName();
        java.lang.reflect.Method m = this.getClass().getDeclaredMethod(name,Object.class,MethodParams.class);
        if (isNotNull(m)) {
          Object r = m.invoke(this,obj,method.getParams());
          if (isNotNull(r)) {
            ret = r;
          }
        }
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
}
