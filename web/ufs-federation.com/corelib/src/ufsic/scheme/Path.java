package ufsic.scheme;

import java.lang.reflect.Constructor;
import java.lang.reflect.Method;
import java.net.URI;
import java.nio.charset.Charset;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Random;
import javax.servlet.http.Cookie;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Permission.Action;
import ufsic.scheme.Permission.Right;
import ufsic.utils.Utils;

public class Path extends SchemeRecord {

  private final Permissions permissions;
  private final ParentPaths parentPaths;
  
  private final ArrayList<String> jsIdents = new ArrayList<>();
  
  private final Charset charset = Utils.getCharset(); 
  private Class handlerClass = null;
  private boolean internal = false;
  private boolean useCache = true;

  public Path(SchemeTable table, Record record) {
    
    super(table,record);
    
    this.permissions = new Permissions(getScheme());
    this.parentPaths = new ParentPaths(getScheme());
  }
  
  public Value getPathId() {
    
    return getValue(Paths.PathId);
  }
  
  public void setPathId(Object pathId) {

    if (!setValue(Paths.PathId,pathId)) {
      add(Paths.PathId,pathId);
    }
  }
  
  public Value getLangId() {
    
    return getValue(Paths.LangId);
  }

  public Value getParentId() {
    
    return getValue(Paths.ParentId);
  }
  
  public Value getName() {
    
    return getValue(Paths.Name);
  }

  public Value getDescription() {
    
    return getValue(Paths.Description);
  }

  public Value getContentType() {
    
    return getValue(Paths.ContentType);
  } 
  
  public Value getCheckPermission() {
    
    return getValue(Paths.CheckPermisssions);
  }
  
  public Value getCacheTimeout() {
    
    return getValue(Paths.CacheTimeout);
  }

  public Value getHandlerType() {
    
    return getValue(Paths.HandlerType);
  }

  public void setHandlerType(Object handlerType) {

    if (!setValue(Paths.HandlerType,handlerType)) {
      add(Paths.HandlerType,handlerType);
    }
  }
  
  public Value getPath() {
    
    return getValue(Paths.Path);
  }

   public String getPathString() {
    
    return getPath().asString();
  }
 
  public void setPath(Object path) {

    if (!setValue(Paths.Path,path)) {
      add(Paths.Path,path);
    }
  }

  public Value getLevel() {
    
    return getValue(Paths.Level);
  }

  public Value getLink() {
    
    return getValue(Paths.Link);
  }
  
  public void setInternal(boolean internal) {
    
    this.internal = internal;
  }

  public void setUseCache(boolean useCache) {
    
    this.useCache = useCache;
  }

  public String getNAME() {
    
    return getScheme().getDictionary().replace(getName().asString());
  }
  
  public String getPATH() {
    
    return getPath().asString();
  }

  public String getLANG() {
    
    return getScheme().getLang().getLangId().asString();
  }

  public Integer getLEVEL() {
    
    return getLevel().asInteger();
  }

  public String buildURI(String scheme, String host, Integer port, String path, String query, String fragment, boolean full) {
    
    return this.getScheme().buildURI(scheme,host,port,path,query,fragment,full);
  }
  
  public String buildParamURI(String path, String param, Object value, boolean full) {
    
    String s1 = isNull(param)?"":param;
    String s2 = isNull(value)?"":"="+value.toString();
    return buildURI(null,null,null,path,String.format("%s%s",s1,s2),null,full);
  }

  public String buildParamURI(String param, Object value, boolean full) {
    
    return buildParamURI(getBasePath(getPath().asString()),param,value,full);
  }

  // http://ru.domain.com/context/root/handler/rest => http://ru.domain.com/context/root/handler/rest/path
  public String getFullPath(String path) {
    
    return buildURI(null,null,null,path,null,null,true);
  }

  public String getFullPath() {
    
    return getFullPath(null);
  }
  
  // http://ru.domain.com/context/root/handler/rest => /context/path
  public String getBasePath(String path) {
    
    path = isNotNull(path)?path:"";
    String contextPath = getRequest().getContextPath();
    if (path.equals(contextPath)) {
      contextPath = "";
    }
    String p = String.format("%s%s",(!contextPath.equals("")?contextPath:""),(!path.equals("")?path:""));
    return buildURI(null,null,null,p,null,null,false);
  }
  
  public String getBasePath() {
    
    return getBasePath("");
  }
  
  // http://ru.domain.com/context/root/handler/rest => /context/root/path
  public String getRootPath(String path) {
  
    String rootPath;
    path = isNotNull(path)?path:"";
    ParentPaths pp = getParentPaths();
    if (pp.size()>0) {
      rootPath = ((Path)pp.get(0)).getPath().asString();
    } else {
      rootPath = getPath().asString();
    }
    if (rootPath.equals(Utils.getUrlSeparator())) {
      rootPath = "";
    }
    rootPath = getBasePath(rootPath);
    String p = String.format("%s%s",(!rootPath.equals("")?rootPath:""),(!path.equals("")?path:""));
    return buildURI(null,null,null,p,null,null,false);
  }

  public String getRootPath() {
  
    return getRootPath("");
  }
  
  // http://ru.domain.com/context/root/handler1/handler2/rest => /handler1
  public String getMiddlePath() {
    
    String ret = "";
    ParentPaths pp = getParentPaths();
    if (pp.size()>0) {
      ret = ((Path)pp.get(pp.size()-1)).getPath().asString();
      String[] arr = ret.split(Utils.getUrlSeparator());
      if (arr.length>2) {
        StringBuilder sb = new StringBuilder();
        for (int i=2; i<arr.length; i++) {
          if (!arr[i].equals("")) {
            sb.append(Utils.getUrlSeparator()).append(arr[i]); 
          }
          if (i==2) {
            break;
          }
        }
        ret = sb.toString();
      }
    }
    return buildURI(null,null,null,ret,null,null,false);
  }
  
  // http://ru.domain.com/context/root/handler/rest => /context/root/handler/rest
  public String getRelativePath(String path) {
    
    path = isNotNull(path)?path:"";
    String fmt = (isNotNull(path) && !path.equals(""))?"%s/%s":"%s";
    String p = String.format(fmt,getScheme().getURI().getPath(),path);
    return buildURI(null,null,null,p,null,null,false);
  }
  
  public String getRelativePath() {
    
    return getRelativePath("");
  }
  
  public Path getLastPath() {

    Path ret = null;
    
    Object langLastPageId = getSessionValue(getLangLastPageIdName(getScheme().getLang().getLangId().asString()));
    if (isNotNull(langLastPageId)) {

      Value v1 = getProvider().firstValue(getScheme().getPaths().getViewName(),Paths.Path,new Filter(Paths.PathId,langLastPageId));
      if (v1.isNotNull()) {
        Record r = new Record();
        r.add(Paths.PathId,langLastPageId);
        r.add(Paths.Path,v1);
        ret = new Path(null,r);
      }
    }

    if (isNull(ret)) {
/*      Session session = getScheme().getSession();
      
      if (isNotNull(session)) {
        if (session.needComms()) {

          Filter f = new Filter(Comms.SessionId,session.getSessionId()).
                            And(Comms.PathHandler,PageHandler.class.getSimpleName()).
                            And(Comms.LangId,getScheme().getLang().getLangId().asString());
          Record r = getProvider().first(getScheme().getComms().getViewName(),new FieldNames(Comms.PathId,Comms.Path),f,new Orders(Comms.Created,Order.Type.DESC));
          if (isNotNull(r)) {
            ret = new Path(null,r);
          }
        }
      }*/
    }

    return ret;
  }
  
  // http://ru.domain.com/context/root/handler/rest1/rest2 => /rest1/rest2
  public String getRestPath() {
    
    String info = getScheme().getURI().getPath();
    int len = getPath().asString().length();
    return info.substring(len);
  }

  // http://ru.domain.com/context/root/handler/rest => rest
  public String getRestPathValue() {

    String ret = null;
    String s = getRestPath();
    if (isNotNull(s)) {
      s = s.replace(Utils.getUrlSeparator(),"").trim();
      if (!s.equals("")) {
        ret = s;
      }
    }
    return ret;
  }
  
  public String getRestOfRootPath(String path) {
    
    path = isNotNull(path)?path:"";
    String rootPath = getRootPath();
    int len = rootPath.length();
    String ret = path.substring(len);
    return ret;
  }

  public String getRestOfRootPath() {
    
    return getRestOfRootPath(null);
  }
  
  public boolean granted(Action action, Right right) {
    
    boolean ret = true;
    if (getCheckPermission().asBoolean()) {
      
      Object accountId = getScheme().getAccountId();
      String sAction = Action.asString(action);
      String sRight = Right.asString(right);
              
      Permission permission = permissions.find(accountId,getPathId(),sAction);
      if (isNotNull(permission)) {
        
        ret = permission.getRight().same(sRight);
        
      } else {
      
        Filter f2 = new Filter();
        if (isNull(accountId)) {
          f2 = new Filter().Add(Permissions.AccountId).IsNull();
        } else {
          Account account = getScheme().getAccount();
          if (isNotNull(account)) {
            f2.Add(Permissions.AccountId).Equal(account.getAccountId());
            for (Record r1: account.getRoles()) {
              f2.Or(Permissions.AccountId).Equal(((AccountRole)r1).getRoleId());
            }
          }
        }
        Filter f3 = new Filter(Permissions.Action,sAction);
        
        GroupFilter gf = new GroupFilter(f2).And(f3);
        
        ParentPaths parents = getParentPaths();
        if (isNotNull(parents) && !parents.isEmpty()) {
          GroupFilter gf2 = new GroupFilter().Or(new Filter(Permissions.PathId,getPathId()));
          Filter f = new Filter();
          for (Record r: parents) {
            f.Or(Permissions.PathId,((ParentPath)r).getPathId());
          }
          GroupFilter gf3 = new GroupFilter(f).And(new Filter().Add(Permissions.OnlyCurrent).IsNull());
          gf2.Or(gf3);
          gf.And(gf2);
        }
        
        Record r = getProvider().first(permissions.getViewName(),null,gf);
        if (isNotNull(r)) {
          permission = new Permission(permissions,r);
          permissions.add(permission);
          ret = permission.getRight().same(sRight);
        } else {
          ret = false;        
        }
      } 
    }
    return ret;
  }
  
  public boolean canShow() {
    
    return granted(Action.SHOW,Right.GRANTED);
  }

  public void setHeader(String name, String value) {
    
    getResponse().setHeader(name,value);
  }
  
  public void setErrorHeaders() {
    
    getResponse().setContentType(Handler.getContentType());
    getResponse().setCharacterEncoding(charset.name());
  }
  
  public Class getHandlerClass() {
  
    Class ret = handlerClass;
    if (isNull(ret)) {
      Value handlerType = getHandlerType();
      if (handlerType.isNotNull()) {
        try {
          String pkg = Scheme.class.getPackage().getName();
          String name = String.format("%s.handlers.%s",pkg,handlerType.asString());
          //ret = Handler.class.getClassLoader().loadClass(name);
          ret = Thread.currentThread().getContextClassLoader().loadClass(name);
        } catch (Exception e) {
          logException(e);
        }
      }
    }
    return ret;
  }
  
  public void setHandlerClass(Class cls) {
    
    this.handlerClass = cls;
  }
  
  private String getHandlerContentType(Class cls) {
    
    String ret = "";
    if (isNotNull(cls)) {
      try {
        Method m = cls.getDeclaredMethod("getContentType");
        if (isNotNull(m)) {
          Object r = m.invoke(null);
          if (isNotNull(r) && r instanceof String) {
            ret = (String)r;
          }
        }
      } catch (Exception e) {
        ret = getHandlerContentType(cls.getSuperclass());
      }
    }
    return ret;
  }
  
  private String getDefaultContentType(String contentType) {
    
    String ret;
    Value pathContentType = getContentType();
    if (pathContentType.isNull() || pathContentType.asString().trim().equals("")) {
      if (isNull(contentType)) {
        ret = getHandlerContentType(getHandlerClass());
      } else {
        ret = contentType;
      }
    } else {
      ret = pathContentType.asString();
    }
    return ret;
  }
  
  public void setContentHeaders(String contentType, Long contentLength) {
    
    String conType = getDefaultContentType(contentType);
    
    getResponse().setContentType(conType);
    if (isNotNull(contentLength)) {
      setHeader("Content-Length",contentLength.toString());
    }
  }
  
  public void setContentHeaders(String contentType, Integer contentLength) {
    
    setContentHeaders(contentType,(isNotNull(contentLength))?(long)contentLength:null);
  }
  
  private boolean isRestricted(Class cls) {
    
    boolean ret = false;
    if (isNotNull(cls)) {
      try {
        Method m = cls.getDeclaredMethod("isPathRestricted");
        if (isNotNull(m)) {
          Object r = m.invoke(null);
          if (isNotNull(r)) {
            ret = (boolean)r;
          }
        } else {
          ret = true;
        }
      } catch (Exception e) {
        ret = isRestricted(cls.getSuperclass());
      }
    }
    return ret;
  }
  
  private boolean isClean() {
    
    String rest = getRestPath();
    return isEmpty(rest);
  }
  
  public boolean process(Comm comm) {

    boolean ret = false;
    
    Value handlerType = getHandlerType();
    if (handlerType.isNotNull()) {
      try {
        
        Class cls = getHandlerClass();
        boolean clean = isClean();
        if (clean || (!clean && !isRestricted(cls))) {

          Constructor con = cls.getConstructor(Path.class);
          if (isNotNull(con)) {

            Handler handler = (Handler)con.newInstance(this);
            if (isNotNull(handler)) {

              ret = handler.process(comm);
              if (ret) {
                String s = getResponse().getContentType();
                if (isNull(s) || s.trim().equals("")) {
                  getResponse().setContentType(getDefaultContentType(null));
                }
              }
            }
          }
        }      
      } catch (Exception e) {
        logException(e);
      } 
    }
    return ret;
  }
  
  private String getRestPathWithNull() {
    
    String ret = getRestPath();
    if (isNull(ret) || (isNotNull(ret) && ret.trim().equals(""))) {
      ret = null;
    }
    return ret;
  }
  
  public Cache queryCache(Value stamp) {
    
    Cache ret = null;
    if (useCache && !internal) {
      
      Caches caches = getScheme().getCaches();
      Filter f = new Filter(Caches.PathId,getPathId()).
                        And(Caches.SessionId,getScheme().getSessionId()).
                        And(Caches.Expired).GreaterOrEqual(stamp).
                        And(Caches.Rest,getRestPathWithNull());
      Record r = getProvider().first(caches.getViewName(),null,f);
      if (isNotNull(r)) {
        ret = new Cache(caches,r);
        if (isNotNull(ret)) {
          ret.setPath(this);
        }
      }
    }
    return ret;
  }
  
  public Cache queryCache() {
    
    Cache ret = null;
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
      ret = queryCache(scheme.getStamp());
    }
    return ret;
  }
  
  public boolean clearExpiredCache(Value expired) {
    
    Filter f = new Filter(Caches.PathId,getPathId()).
                      And(Caches.SessionId,getScheme().getSessionId()).
                      And(Caches.LangId,getScheme().getLang().getLangId()).
                      And(Caches.Rest,getRestPathWithNull()).
                      And(Caches.Expired).Less(expired);
    return getProvider().delete(Caches.TableName,f);
  }
  
  public boolean clearPathCache(Value pathId) {
    
    Filter f = new Filter(Caches.PathId,pathId).
                      And(Caches.SessionId,getScheme().getSessionId());
    return getProvider().delete(Caches.TableName,f);
  }
  
  public boolean needCache() {
  
    return useCache && !internal && (getCacheTimeout().asInteger()>0);
  }
  
  public Cache makeCache(Comm comm, Object headers, Object data) {
    
    Cache ret = null;
    
    Value timeout = getCacheTimeout();
    Value created = getProvider().getNow();
    if (clearExpiredCache(created) && timeout.isNotNull()) {
      
      Timestamp expired = created.addSeconds(timeout.asInteger());
      
      Value cacheId = getProvider().getUniqueId();
      Caches caches = getScheme().getCaches();
    
      ret = new Cache(caches,null);
      ret.setCacheId(cacheId);
      ret.setPathId(getPathId());
      ret.setSessionId(getScheme().getSessionId());
      ret.setCommId((isNull(comm))?null:comm.getCommId());
      ret.setCreated(created);
      ret.setExpired(expired);
      ret.setData(data);
      ret.setHeaders(headers);
      ret.setLangId(getScheme().getLang().getLangId());
      ret.setRest(getRestPathWithNull());
      
      if (caches.insert(ret)) {
        ret = null;
      }
    }
    return ret;
  }

  private String getLangLastPageIdName(String langId) {
    
    return String.format("%s_LAST_PAGE_ID",langId);
  }
  
  public Object getLangLastPageId(String langId) {
    
    return getSessionValue(getLangLastPageIdName(langId));
  }
  
  public void setLangLastPageId(String langId, String pathId) {
    
    setSessionValue(getLangLastPageIdName(langId),pathId);
  }

  private String getAuthFailedName() {
    
    return "AUTH_FAILED";
  }

  public boolean getAuthFailed() {
  
    boolean ret = false;
    Object v = getSessionValue(getAuthFailedName());
    if (isNotNull(v)) {
      ret = (boolean)v;
    }
    return ret;
  }
  
  public void setAuthFailed(boolean authFailed) {
    
    setSessionValue(getAuthFailedName(),authFailed);
  }
  
  private String getFormSuccessName(String formId) {
    
    return String.format("%s_SUCCESS",formId);
  }

  public boolean getFormSuccess(String formId) {
  
    boolean ret = false;
    Object v = getSessionValue(getFormSuccessName(formId));
    if (isNotNull(v)) {
      ret = (boolean)v;
    }
    return ret;
  }
  
  public void setFormSuccess(String formId, boolean success) {
    
    setSessionValue(getFormSuccessName(formId),success);
  }

  private String getCaptchaName(String id) {
    
    return String.format("%s_CAPTCHA",id);
  }

  public String getCaptcha(String id) {
  
    String ret = null;
    Object v = getSessionValue(getCaptchaName(id));
    if (isNotNull(v)) {
      ret = v.toString();
    }
    return ret;
  }
  
  public void setCaptcha(String id, String value) {
    
    setSessionValue(getCaptchaName(id),value);
  }
  
  public boolean redirect(String location) {

    boolean ret = false;
    try {
      getResponse().sendRedirect(location);
      ret = true;
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }

  public boolean redirect(Value location) {

    boolean ret = false;
    if (isNotNull(location)) {
      ret = redirect(location.asString());
    }
    return ret;
  }

  public boolean redirect(Path path) {

    boolean ret = false;
    if (isNotNull(path)) {
      ret = redirect(path.getPath());
    }
    return ret;
  }
  
  public boolean redirectToLink() {
    
    boolean ret = false;
    Value link = getLink();
    if (link.isNotNull()) {
      ret = redirect(link.asString());
    }
    return ret;
  }

  public boolean redirectToSelf() {
    
    boolean ret = false;
    Value path = getPath();
    if (path.isNotNull()) {
      ret = redirect(path.asString());
    }
    return ret;
  }
  
  private boolean needSession(Class cls) {
    
    boolean ret = true;
    if (isNotNull(cls)) {
      try {
        Method m = cls.getDeclaredMethod("needSession");
        if (isNotNull(m)) {
          Object r = m.invoke(null);
          if (isNotNull(r)) {
            ret = (boolean)r;
          }
        }
      } catch (Exception e) {
        ret = needSession(cls.getSuperclass());
      }
    }
    return ret;
  }
  
  public boolean needSession() {
    
    boolean ret = true;
    Class cls = getHandlerClass();
    if (isNotNull(cls)) {
      ret = needSession(cls);
    }
    return ret;
  }
  
  public ParentPaths getParentPaths(boolean refresh) {
    
    if (refresh) {
      parentPaths.open(new Filter(ParentPaths.LastPathId,getPathId()),new Orders(ParentPaths.Level,ParentPaths.Priority));
    }
    return parentPaths;
  }
  
  public ParentPaths getParentPaths() {
    
    return getParentPaths(parentPaths.isEmpty());
  }
 
  public String getPrevPath(int offset) {
    
    String ret = "";
    ParentPaths paths = getParentPaths();
    if (isNotNull(paths)) {
      int index = (paths.size()-1) + offset;
      if (index>=0) {
        ParentPath path = (ParentPath)paths.get(index);
        if (isNotNull(path)) {
          ret = path.getPath().asString();
        }
      }
    }
    return ret;
  }
  
  public String getProto(String def) {
    
    String proto = getRequest().getHeader("proto");
    if (isEmpty(proto)) {
      proto = getRequest().getHeader("x-forwarded-proto");
    }
    return !isEmpty(proto)?proto:def;
  }
  
  public String getProto() {
    
    return getProto(getScheme().getURI().getScheme());
  }
  
  public String getHost(String def) {
    
    String host = getRequest().getHeader("host");
    if (isEmpty(host)) {
      host = getRequest().getHeader("x-forwarded-host");
    }
    return !isEmpty(host)?host:def;
  }
  
  public String getHost() {
    
    return getProto(getScheme().getURI().getHost());
  }
  
  // http://ru.domain.com/context/root/handler1/handler2/rest => /root/handler1
  public String getPrevPath() {
    
    return getPrevPath(-1);
  }
  
  // http://ru.domain.com:8080/context/root/handler => http://ru.domain.com:8080
  // http://ru.domain.com:80/context/root/handler => http://ru.domain.com
  // https://ru.domain.com:8181/context/root/handler => https://ru.domain.com:8181
  // https://ru.domain.com:443/context/root/handler => https://ru.domain.com
  public String getAddress(boolean checkHeaders) {
    
    String ret = null;
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
      
      URI uri = scheme.getURI();
      if (isNotNull(uri)) {

        String proto = checkHeaders?getProto(uri.getScheme()):uri.getScheme();
        String host = checkHeaders?getHost(uri.getHost()):uri.getHost();
        
        /*String p = proto.toLowerCase();
        int port = uri.getPort();
        boolean flag = (p.equals("http") && port!=80) ||
                       (p.equals("https") && port!=443);
        if (flag) {
          host = String.format("%s:%d",host,port);
        }*/
          
        ret = String.format("%s://%s",proto,host);
      }
    }
    return ret;
  }
  
  public String getAddress() {
    
    return getAddress(true);
  }
  
  public Integer newJsIdent(String prefix, int pads) {
    
    Integer ret = null;
    String add = null;
    do {
      Random rn = new Random();
      double a = 10, b = pads-1;
      int min = (int)Math.pow(a,b);
      int max = (int)Math.pow(a,pads);
      ret = min + rn.nextInt(max - min);
      add = prefix+ret;
    } while (jsIdents.contains(add));
    
    jsIdents.add(add);
    
    return ret;
  }
  
  public Integer newJsIdent(String prefix) {
    return newJsIdent(prefix,4); 
  }
  
  public Integer lastJsIdent(String prefix) {
    
    Integer ret = null;
    if (jsIdents.size()>0) {
      for (int i=jsIdents.size()-1;i>=0;i--) {
        String s = jsIdents.get(i);
        if (s.startsWith(prefix)) {
          s = s.substring(prefix.length());
          if (Utils.isInteger(s)) {
            ret = Integer.parseInt(s);
            break;
          }
        }
      }
    }
    return ret;
  }
 
  public HttpServletRequest getRequest() {

    return getScheme().getRequest();
  }

  public HttpServletResponse getResponse() {

    return getScheme().getResponse();
  }
  
  public boolean parameterExists(String name) {
    
    Object ret = null;
    HttpServletRequest request = getRequest();
    if (isNotNull(request)) {
      ret = request.getParameter(name);
    }
    return isNotNull(ret);
  }
 
  public Object getSessionValue(String name) {
  
    Object ret = null;
    HttpServletRequest request = getRequest();
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
    HttpServletRequest request = getRequest();
    if (isNotNull(request)) {
      HttpSession session = request.getSession();
      if (isNotNull(session)) {
        session.setAttribute(name,value);
        ret = true;
      }
    }
    return ret;
  }
  
  public Object getCookieValue(String name) {

    Object ret = null; 
    HttpServletRequest request = getRequest();
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
    HttpServletResponse response = getResponse();
    Cookie c = new Cookie(name,(isNull(value))?"":value.toString());
    if (isNotNull(c) && isNotNull(response)) {
      response.addCookie(c);
      ret = true;
    }
    return ret;
  }
  
  private String decodeFormValue(String value) {
    
    String ret = value;
    HttpServletRequest request = getRequest();
    if (isNotNull(ret) && !ret.equals("") && isNotNull(request)) {
      try {
        String cset = request.getCharacterEncoding();
        if (isNull(cset)) {
          cset = "ISO8859_1"; // Apache, GlassFish WTF?
        }
        ret = new String(ret.getBytes(cset),Utils.getCharset().name());
      } catch(Exception e) {
        logException(e);
      }
    }
    return ret;
    
  }
  
  public Map<String,String[]> getParameterValues() {
    
    Map<String,String[]> ret = new HashMap<>();
    HttpServletRequest request = getRequest();
    if (isNotNull(request)) {
      
      Map<String,String[]> map = request.getParameterMap();
      for (Entry<String,String[]> entry: map.entrySet()) {
        
        String[] values = entry.getValue();
        for (int i=0; i<values.length; i++) {
          
          values[i] = decodeFormValue(values[i]);
        }
        ret.put(entry.getKey(),values);
      }
    }
    return ret;
  }
  
  public String[] getParameterValues(String name) {
    
    String[] ret = new String[] {};
    String[] temp = getParameterValues().get(name);
    if (isNotNull(temp)) {
      ret = temp;
    }
    return ret;
  }
  
  public String getParameterValue(String name) {
    
    String ret = null; 
    String[] values = getParameterValues(name);
    if (values.length>0) {
      ret = values[0];
    }
    return ret;
  }
  
  /*public Object getParameterValue(String name) {
    
    Object ret = getFormValue(name);
    if (isNull(ret)) {
      ret = getSessionValue(name);
      if (isNull(ret)) {
        ret = getCookieValue(name);
      }
    }
    return ret;
  }*/

  public String getParameterValue(String name, String def) {
    
    String ret = def;
    Object o = getParameterValue(name);
    if (isNotNull(o)) {
      ret = o.toString();
    }
    return ret;
  }
  
  public Integer getParameterValue(String name, Integer def) {
    
    Integer ret = def;
    Object o = getParameterValue(name);
    if (Utils.isInteger(o)) {
      ret = Utils.toInteger(o);
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
  
  public String getResponseHeaders() {
  
    String ret = null;
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
      ret = scheme.getResponseHeaders();
    }
    return ret;
  }
  
  private String getRequestHeaders() {
  
    String ret = null;
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
      ret = scheme.getRequestHeaders();
    }
    return ret;
  }
  
  
}