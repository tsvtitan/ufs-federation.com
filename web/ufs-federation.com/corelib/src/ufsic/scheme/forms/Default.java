package ufsic.scheme.forms;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import ufsic.scheme.Account;
import ufsic.scheme.Agreement;
import ufsic.scheme.Agreements;
import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeObject;
import ufsic.utils.Utils;

public class Default extends SchemeObject {

  private String name = null;
  private Method method = null;
  
  public Default(Scheme scheme, String ident) {
    
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
  
  private Object Date(MethodParams params) {

    Object ret = null;
    if (isNotNull(params) && !params.isEmpty()) {
      String fmt = params.get(0).getValue();
      Date d = new Date();
      if (params.size()>1) {
        String s = params.get(1).getValue();
        if (Utils.isInteger(s)) {
          int days = Utils.toInt(s);  
          Calendar c = Calendar.getInstance();
          c.setTime(d);
          c.add(Calendar.DATE,days);
          d = c.getTime();
        }
      }
      SimpleDateFormat sdf = new SimpleDateFormat(fmt);
      ret = sdf.format(d);
    }
    return ret;
  }
  
  private Object Timestamp(MethodParams params) {

    Object ret = null;
    if (isNotNull(params) && !params.isEmpty()) {
      String fmt = params.get(0).getValue();
      SimpleDateFormat sdf = new SimpleDateFormat(fmt);
      ret = sdf.format(new Date());
    }
    return ret;
  }
  
  private Object FirstDayOfMonth(MethodParams params) {
    
    Object ret = null;
    if (isNotNull(params) && !params.isEmpty()) {
      String fmt = params.get(0).getValue();
      SimpleDateFormat sdf = new SimpleDateFormat(fmt);
      Calendar c = Calendar.getInstance();
      c.setTime(new Date());
      c.set(Calendar.DAY_OF_MONTH,1); 
      ret = sdf.format(c.getTime());
    }
    return ret;
  }
  
  private Object LastDayOfMonth(MethodParams params) {
    
    Object ret = null;
    if (isNotNull(params) && !params.isEmpty()) {
      String fmt = params.get(0).getValue();
      SimpleDateFormat sdf = new SimpleDateFormat(fmt);
      Calendar c = Calendar.getInstance();
      c.setTime(new Date());
      c.set(Calendar.DAY_OF_MONTH,c.getActualMaximum(Calendar.DAY_OF_MONTH)); 
      ret = sdf.format(c.getTime());
    }
    return ret;
  }

  private Object FirstDayOfYear(MethodParams params) {
    
    Object ret = null;
    if (isNotNull(params) && !params.isEmpty()) {
      String fmt = params.get(0).getValue();
      SimpleDateFormat sdf = new SimpleDateFormat(fmt);
      Calendar c = Calendar.getInstance();
      c.setTime(new Date());
      c.set(Calendar.DAY_OF_YEAR,1); 
      ret = sdf.format(c.getTime());
    }
    return ret;
  }

  private Object LastDayOfYear(MethodParams params) {
    
    Object ret = null;
    if (isNotNull(params) && !params.isEmpty()) {
      String fmt = params.get(0).getValue();
      SimpleDateFormat sdf = new SimpleDateFormat(fmt);
      Calendar c = Calendar.getInstance();
      c.setTime(new Date());
      c.set(Calendar.DAY_OF_YEAR,c.getActualMaximum(Calendar.DAY_OF_YEAR)); 
      ret = sdf.format(c.getTime());
    }
    return ret;
  }
  
  private Object AccountId(MethodParams params) {
    
    return getScheme().getAccountId();
  }
  
  private Object AgreementId(MethodParams params) {
    
    Object ret = null;
    Account a = getScheme().getAccount();
    if (isNotNull(a)) {
      Agreements ags = a.getAgreements();
      if (ags.size()>0) {
        ret = ((Agreement)ags.get(0)).getAgreementId().asObject();
      }
    }
    return ret;
  }
 
  public Object getValue() {

    Object ret = null;

    if (isNotNull(method)) {
      try {
        String name = method.getName();
        java.lang.reflect.Method m = this.getClass().getDeclaredMethod(name,MethodParams.class);
        if (isNotNull(m)) {
          Object r = m.invoke(this,method.getParams());
          if (isNotNull(r)) {
            ret = r;
          }
        }
      } catch (Exception e) {
        ret = method.getName();
      }
    }
    return ret;
  }
  
}
