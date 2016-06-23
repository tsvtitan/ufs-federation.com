package ufsic.scheme;

import ufsic.scheme.handlers.Handler;
import ufsic.scheme.handlers.CacheHandler;
import ufsic.scheme.handlers.PageHandler;
import ufsic.scheme.handlers.DirHandler;
import ufsic.scheme.handlers.FileHandler;
import java.lang.reflect.Constructor;
import java.lang.reflect.Method;
import java.net.URI;
import java.nio.charset.Charset;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Random;
import ufsic.providers.FieldNames;

import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Order;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Permission.Action;
import ufsic.scheme.Permission.Right;
import ufsic.utils.Utils;

public class Path extends SchemeRecord {

  private Permissions permissions;
  private ParentPaths parentPaths;
  
  private ArrayList<String> jsIdents = new ArrayList<>();
  
  private Charset charset = Utils.getCharset(); 
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

  public String getFullPath(String path) {
    
    return buildURI(null,null,null,path,null,null,true);
  }

  public String getFullPath() {
    
    return getFullPath(null);
  }
  
  public String getBasePath(String path) {
    
    path = isNotNull(path)?path:"";
    String contextPath = getRequest().getContextPath(); 
    String p = String.format("%s%s",(!contextPath.equals("")?contextPath:""),(!path.equals("")?path:""));
    return buildURI(null,null,null,p,null,null,false);
  }
  
  public String getBasePath() {
    
    return getBasePath("");
  }
  
  public String getRootPath(String path) {
  
    String rootPath;
    path = isNotNull(path)?path:"";
    ParentPaths pp = getParentPaths();
    if (pp.size()>0) {
      rootPath = ((Path)pp.get(0)).getPath().asString();
    } else {
      rootPath = getPath().asString();
    }
    if (rootPath.equals("/")) {
      rootPath = "";
    }
    rootPath = getBasePath(rootPath);
    String p = String.format("%s%s",(!rootPath.equals("")?rootPath:""),(!path.equals("")?path:""));
    return buildURI(null,null,null,p,null,null,false);
  }

  public String getRootPath() {
  
    return getRootPath("");
  }
  
  public String getMiddlePath() {
    
    String ret = "";
    ParentPaths pp = getParentPaths();
    if (pp.size()>0) {
      ret = ((Path)pp.get(pp.size()-1)).getPath().asString();
      String[] arr = ret.split("/");
      if (arr.length>2) {
        StringBuilder sb = new StringBuilder();
        for (int i=2; i<arr.length; i++) {
          if (!arr[i].equals("")) {
            sb.append("/").append(arr[i]); 
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
      Session session = getScheme().getSession();
      
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
      }
    }

    return ret;
  }
  
  public String getRestPath() {
    
    String info = getRequest().getPathInfo();
    int len = getPath().asString().length();
    
    return info.substring(len);
  }

  public String getRestPathValue() {

    String ret = null;
    String s = getRestPath();
    if (isNotNull(s)) {
      s = s.replace("/","").trim();
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
      
//        Filter f1 = new Filter(Permissions.PathId,getPathId());

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
    
    setHeader("Content-Type",String.format("%s;charset=%s",Handler.getContentType(),charset.name()));
  }
  
  public Class getHandlerClass() {
  
    Class ret = handlerClass;
    if (isNull(ret)) {
      Value handlerType = getHandlerType();
      if (handlerType.isNotNull()) {
        try {
          String pkg = Scheme.class.getPackage().getName();
          String name = String.format("%s.handlers.%s",pkg,handlerType.asString());
          ret = Handler.class.getClassLoader().loadClass(name);
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
  
  public void setContentHeaders(Object contentType, Long contentLength) {
    
    String conType = Handler.getContentType();
    Value defContentType = getContentType();
    if (defContentType.isNull()) {
      if (isNotNull(contentType)) {
        conType = contentType.toString();
      }
    } else {
      conType = defContentType.asString();
    }
    
    setHeader("Content-Type",String.format("%s;charset=%s",conType,charset.name()));
    if (isNotNull(contentLength)) {
      setHeader("Content-Length",contentLength.toString());
    }
  }
  
  public void setContentHeaders(Object contentType, Integer contentLength) {
    
    setContentHeaders(contentType,(isNotNull(contentLength))?(long)contentLength:null);
  }
  
  public boolean process(Comm comm) {

    boolean ret = false;
    
    Value handlerType = getHandlerType();
    if (handlerType.isNotNull()) {
      try {
        Class cls = getHandlerClass();
        if (isNotNull(cls)) {

          Constructor con = cls.getConstructor(Path.class);
          if (isNotNull(con)) {

            Handler han = (Handler)con.newInstance(this);
            if (isNotNull(han)) {

              ret = han.process(comm);
            }
          }
        }      
      } catch (Exception e) {
        logException(e);
      } 
    }
    return ret;
  }
  
  public Cache queryCache(Value stamp) {
    
    Cache ret = null;
    if (useCache && !internal) {
      Filter f = new Filter(Caches.PathId,getPathId()).
                        And(Caches.SessionId,getScheme().getSessionId()).
                        And(Caches.Expired).GreaterOrEqual(stamp);
      Record r = getProvider().first(getScheme().caches.getViewName(),null,f);
      if (isNotNull(r)) {
        ret = new Cache(getScheme().caches,r);
        if (isNotNull(ret)) {
          ret.setPath(this);
        }
      }
    }
    return ret;
  }
  
  public boolean clearExpiredCache(Value expired) {
    
    Filter f = new Filter(Caches.PathId,getPathId()).
                      And(Caches.SessionId,getScheme().getSessionId()).
                      And(Caches.LangId,getScheme().getLang().getLangId()).
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
      
      Timestamp expired = new Timestamp(created.asTimestamp().getTime() + (timeout.asInteger() * 1000L));
      
      Value cacheId = getProvider().getUniqueId();
    
      ret = new Cache(getScheme().caches,null);
      ret.setCacheId(cacheId);
      ret.setPathId(getPathId());
      ret.setSessionId(getScheme().getSessionId());
      ret.setCommId((isNull(comm))?null:comm.getCommId());
      ret.setCreated(created);
      ret.setExpired(expired);
      ret.setData(data);
      ret.setHeaders(headers);
      ret.setLangId(getScheme().getLang().getLangId());
      
      if (!getScheme().caches.insert(ret)) {
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
        //
      }
    }
    return ret;
  }
  
  public boolean needSession() {
    
    boolean ret = true;
    Class cls = getHandlerClass();
    if (isNotNull(cls)) {
      ret = needSession(cls);
      /*if ((cls==CacheHandler.class) || 
          (cls==FileHandler.class) ||
          (cls==DirHandler.class)) {
        ret = false;
      }*/
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
  
  public String getPrevPath() {
    
    return getPrevPath(-1);
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
  
}