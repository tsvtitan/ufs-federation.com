package ufsic.scheme;

import javax.servlet.http.Cookie;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import ufsic.out.Echo;
import ufsic.out.ILogger;
import ufsic.providers.Filter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.utils.Utils;

public class SchemeRecord extends Record implements ILogger {

  private SchemeTable table;
  private Scheme scheme;
  private Provider provider;
  private Echo echo;
  private HttpServletRequest request;
  private HttpServletResponse response;

  public SchemeRecord() {
    super();
  }

  public SchemeRecord(Scheme scheme) {
    super();
    this.table = null;
    this.scheme = scheme;
    this.provider = scheme.getProvider();
    this.echo = scheme.getEcho();
    this.request = scheme.getController().getRequest();
    this.response = scheme.getController().getResponse();
  }
  
  public SchemeRecord(SchemeTable table) {
    
    this();
    this.table = table;
    if (isNotNull(table)) {
      this.scheme = table.getScheme();
      this.provider = table.getProvider();
      this.echo = table.getEcho();
      this.request = table.getRequest();
      this.response = table.getResponse();
    } else {
      this.scheme = null;
      this.provider = null;
      this.echo = null;
      this.request = null;
      this.response = null;
    }
  }
  
  public SchemeRecord(SchemeTable table, Record record) {

    this(table);
    if (isNotNull(record))  {
      this.addAll(record);
    }
  }
  
  @Override
  public void copyFrom(Record source) {
    
    super.copyFrom(source);
    if (isNotNull(source) && (source instanceof SchemeRecord)) {
      
      SchemeRecord record = (SchemeRecord)source;
      this.table = record.table;
      this.scheme = record.scheme;
      this.provider = record.provider;
      this.echo = record.echo;
      this.request = record.request;
      this.response = record.response;
    }
  }

  public Scheme getScheme() {

    return scheme;
  }

  public SchemeTable getTable() {

    return table;
  }
  
  public Provider getProvider() {

    return provider;
  }
  
  public void setProvider(Provider provider) {
    
    this.provider = provider;
  }

  public Echo getEcho() {

    return echo;
  }

  public HttpServletRequest getRequest() {

    return request;
  }

  public HttpServletResponse getResponse() {

    return response;
  }
  
  @Override
  public void logInfo(Object obj) {
    
    if (isNotNull(scheme)) {
      scheme.getLogger().writeInfo(obj.toString());
    }
  }
  
  @Override
  public void logError(Object obj) {
    
    if (isNotNull(scheme)) {
      scheme.getLogger().writeError(obj.toString());
    }
  }
  
  @Override
  public void logWarn(Object obj) {
    
    if (isNotNull(scheme)) {
      scheme.getLogger().writeError(obj.toString());
    }
  }
  
  @Override
  public void logException(Exception e) {
  
    if (isNotNull(scheme)) {
      scheme.getLogger().writeException(e);
    }
  }
 
  public boolean parameterExists(String name) {
    
    Object ret = null;
    if (isNotNull(request)) {
      ret = request.getParameter(name);
    }
    return isNotNull(ret);
  }
 
  public Object getSessionValue(String name) {
  
    Object ret = null;
    if (isNotNull(request)) {
      HttpSession session = request.getSession();
      if (isNotNull(session)) {
        ret = session.getAttribute(name);
      }
    }
    return ret;
  }
  
  public boolean setSessionValue(String name, Object value) {
    
    boolean ret = false;
    HttpSession session = request.getSession();
    if (isNotNull(session)) {
      session.setAttribute(name,value);
      ret = true;
    }
    return ret;
  }
  
  public Object getCookieValue(String name) {

    Object ret = null; 
    if (isNotNull(request)) {
      Cookie[] cookies = request.getCookies();
      if (isNotNull(cookies)) {
        for (Cookie c: cookies) {
          if (name.equals(c.getName())) {
            ret = c.getValue();
            break;
          }
        }
      }
    }
    return ret;
  }
  
  public boolean setCookieValue(String name, Object value) {
    
    boolean ret = false;
    Cookie c = new Cookie(name,(isNull(value))?"":value.toString());
    if (isNotNull(c) && isNotNull(response)) {
      response.addCookie(c);
      ret = true;
    }
    return ret;
  }
  
  private String decodeFormValue(String value) {
    
    String ret = value;
    if (isNotNull(ret) && !ret.equals("")) {
      try {
        String charset = request.getCharacterEncoding();
        if (isNull(charset)) {
          charset = "ISO8859_1"; // Apache, GlassFish
        }
        ret = new String(ret.getBytes(charset),Utils.getCharset().name());
      } catch(Exception e) {
        logException(e);
      }
    }
    return ret;
    
  }
  
  public Object getFormValue(String name) {
    
    Object ret = null;
    if (isNotNull(request)) {
      String[] arr = request.getParameterValues(name);
      if (isNotNull(arr)) {
        if (arr.length>0) {
          if (arr.length==1) {
            ret = decodeFormValue(arr[0]);
          } else {
            for (int i=0;i<arr.length;i++) {
              arr[i] = decodeFormValue(arr[i]);
            }
            ret = arr;
          }
        }
      }
    }
    return ret;
  }
  
  public Object getParameterValue(String name) {
    
    Object ret = getFormValue(name);
    if (isNull(ret)) {
      ret = getSessionValue(name);
      if (isNull(ret)) {
        ret = getCookieValue(name);
      }
    }
    return ret;
  }

  public String getParameterValue(String name, String def) {
    
    String ret = def;
    Object o = getParameterValue(name);
    if (isNotNull(o)) {
      ret = o.toString();
    }
    return ret;
  }
  
  public int getParameterValue(String name, int def) {
    
    int ret = def;
    Object o = getParameterValue(name);
    if (Utils.isInteger(o)) {
      ret = Utils.toInt(o);
    }
    return ret;
  }

  public double getParameterValue(String name, double def) {
    
    double ret = def;
    Object o = getParameterValue(name);
    if (Utils.isDouble(o)) {
      ret = Utils.toDouble(o);
    }
    return ret;
  }
  
  public Integer getRecNum() {
    
    Integer ret = null;
    if (isNotNull(table)) {
      ret = table.indexOf(this);
    }
    return ret;
  }
  
  public String getRecNum(Integer padCount) {
    
    String ret = "";
    Integer recNum = getRecNum();
    if (isNotNull(recNum)) {
      if (isNotNull(padCount)) {
        String fmt = "%0" + padCount.toString() + "d";
        ret = String.format(fmt,recNum);
      } else {
        ret = recNum.toString();
      }
    }
    return ret;
  }
 
  public boolean select(Filter filter) {

    boolean ret = false;
    if (isNotNull(table)) {
      ret = table.select(this,filter);
    }
    return ret;
  }
  
  public boolean insert() {
    
    boolean ret = false;
    if (isNotNull(table)) {
      ret = table.insert(this);
    }
    return ret;
  }
  
  public boolean update(Filter filter) {
    
    boolean ret = false;
    if (isNotNull(table)) {
      ret = table.update(this,filter);
    }
    return ret;
  }
  
  protected boolean save() {
    return false;
  }
  
}