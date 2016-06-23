package ufsic.scheme;

import java.io.ByteArrayOutputStream;
import java.net.URI;
import java.text.DecimalFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Collection;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Locale;
import java.util.Map;
import javax.servlet.ServletInputStream;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import ufsic.applications.IApplication;
import ufsic.applications.IDatabaseApplication;
import ufsic.applications.ISchemeServletApplication;
import ufsic.applications.IServletApplication;
import ufsic.core.CoreObject;
import ufsic.gates.IMessageGateRemote;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;
import ufsic.providers.Provider;
import ufsic.providers.Value;
import ufsic.utils.Utils;

public class Scheme extends CoreObject {

  //private final ISchemeServletApplication application;
  private IApplication application = null;
  private URI uri = null;
  private Provider provider = null;
  
  private Value stamp = null;

  private Langs langs = null;
  private Sessions sessions = null;
  private Paths paths = null;
  private Files files = null;
  private Dirs dirs = null;
  private Pages pages = null;
  private Templates templates = null;
  private Accounts accounts = null;
  private Comms comms = null;
  private Caches caches = null;
  private Dictionary dictionary = null;
  private PageAnalogs pageAnalogs = null;
  private Menus menus = null;
  private Clients clients = null;
  private Publications publications = null;
  private Messages messages = null;
  private Patterns patterns = null;
  private Confirms confirms = null;
  private Branches branches = null;

  private Lang lang = null;
  private Path path = null;
  private Page page = null;
  private Session session = null;
  private Account account = null;
  private Client client = null;
  private Publication publication = null;
  
  private Path cssPath = null;
  private Path jsPath = null;
  
  private final Map<String,Path> internalPaths = new HashMap<>();
  
  public Scheme(IApplication application) {
    
    super(application.getLogger(),application.getEcho());
    
    this.application = application;
    this.uri = null;
    this.provider = null;
    
    /*this.cachePath = addInternalPath("/cache",CacheHandler.class);*/
  }
  
  public Scheme(IApplication application, URI uri, Provider provider) {
    
    this(application);
    this.uri = uri;
    this.provider = provider;
  }
  
  public Scheme(IApplication application, URI uri) {
    
    this(application,uri,null);
    this.uri = uri;
    this.provider = null;
  }
  
  public void setProvider(Provider provider) {
    this.provider = provider;
  }

  private Path addInternalPath(String path, Class handlerClass) {
  
    String ret = path;
    Path obj = new Path(getPaths(),null);
    obj.setInternal(true);
    obj.setHandlerClass(handlerClass);
    obj.setPath(ret);
    obj.setHandlerType(handlerClass.getName());
    internalPaths.put(Utils.md5(ret.toUpperCase()),obj);
    return obj;
  }
  
  public Path getCssPath() {
  
    if (isNull(cssPath)) {
      cssPath  = addInternalPath("/css",CacheHandler.class);
    }
    return cssPath; 
  }

  public Path getJsPath() {
  
    if (isNull(jsPath)) {
      jsPath = addInternalPath("/js",CacheHandler.class);
    }
    return jsPath; 
  }

  public class URINotFoundException extends RuntimeException { }
  
  public URI getURI() {
    
    if (isNull(uri)) {
      throw new URINotFoundException();
    }
    return uri;
  }
  
  private String getLastPath(String path) {
  
    String ret = path;
    String[] arr = ret.split("/");
    if (arr.length>0) {
      ret = String.format("/%s",arr[arr.length-1]);
    }
    return ret;
  }
  
  private Path searchInternalPath(String path) {
   
    String s = getLastPath(path);
    Path ret = internalPaths.get(Utils.md5(s.toUpperCase()));
    return ret;
  }
  
  public Path queryPath(String path) {
    
    this.path = searchInternalPath(path.toLowerCase());
    if (isNull(this.path)) {
      this.path = getPaths().queryPath(path);
    }
    return this.path;
  }
  
  public Session trySession(String sessionId) {
    
    session = getSessions().trySession(sessionId);
    if (isNotNull(session)) {
      account = getAccounts().getAccount(session.getAccountId());
    }
    return session;
  }

  public Lang getLang(boolean first) {
    
    if (isNull(lang)) {
      
      if (isNotNull(uri)) {
        String host = uri.getHost();
        String parts[] = host.split("[\\.]");
        if (parts.length>2) {
          String s = parts[0].toUpperCase();
          lang = getLangs().getLang(s);
        }
      }
      if (isNull(lang) && first) {
        lang = getLangs().first();
      }
    }
    return lang;
  }
  
  public Lang getLang() {
    return getLang(false);
  }

  public Value getLangId(boolean first) {
    
    Value ret;
    Lang l = getLang(first);
    if (isNotNull(l)) {
      ret = l.getLangId();
    } else {
      ret = new Value(null);
    }
    return ret;
  }
  
  public Value getLangId() {
    return getLangId(false);
  }
  
  public String getLANG() {
    return getLangId().asString();
  }
  
  public Session getSession() {
    return session;
  }

  public Object getSessionId() {

    Object ret = null;
    if (isNotNull(session)) {
      ret = session.getSessionId().asObject();
    }
    return ret;
  }
  
  public Account getAccount() {
    return account;  
  }
  
  public Object getAccountId() {
    
    Object ret = null;
    if (isNotNull(account)) {
      ret = account.getAccountId().asObject();
    }
    return ret;
  }
  
  public Path getPath() {
    return path;
  }
 
  public Langs getLangs() {
    
    if (isNull(langs)) {
      langs = new Langs(this);
    }
    return langs;
  }
 
  public Sessions getSessions() {
    
    if (isNull(sessions)) {
      sessions = new Sessions(this);
    }
    return sessions;
  }
 
  public Paths getPaths() {
    
    if (isNull(paths)) {
      paths = new Paths(this);
    }
    return paths;
  }
 
  public Files getFiles() {
    
    if (isNull(files)) {
      files = new Files(this);
    }
    return files;
  }

  public Pages getPages() {
    
    if (isNull(pages)) {
      pages = new Pages(this);
    }
    return pages;
  }

  public Accounts getAccounts() {
    
    if (isNull(accounts)) {
      accounts = new Accounts(this);
    }
    return accounts;
  }

  public Comms getComms() {
    
    if (isNull(comms)) {
      comms = new Comms(this);
    }
    return comms;
  }
  
  public Templates getTemplates() {
    
    if (isNull(templates)) {
      templates = new Templates(this);
    }
    return templates;
  }
  
  public Caches getCaches() {
    
    if (isNull(caches)) {
      caches = new Caches(this);
    }
    return caches;
  }
  
  public Branches getBranches(boolean refresh) {
    
    if (isNull(branches)) {
      branches = new Branches(this);
    }
    if (refresh) {
      branches.open(new Filter(Branches.LangId,getLangId()),new Orders(Branches.Priority));
    }
    return branches;
  }
  
  public Branches getBranches() {
    
    if (isNull(branches)) {
      branches = new Branches(this);
    }
    return getBranches(branches.isEmpty());
  }
 
  public Dictionary getDictionary() {
    
    if (isNull(dictionary)) {
      dictionary = new Dictionary(this);
    }
    return dictionary;
  }

  public PageAnalogs getPageAnalogs() {
    
    if (isNull(pageAnalogs)) {
      pageAnalogs = new PageAnalogs(this);
    }
    return pageAnalogs;
  }
  
  public Menus getMenus(boolean refresh, int maxLevel) {
    
    if (isNull(menus)) {
      menus = new Menus(this);
    }
    if (refresh) {
      
      GroupFilter gf = new GroupFilter(new Filter().And(ufsic.scheme.Menus.Invisible).Equal(0).
                                                    And(ufsic.scheme.Menus.Level).LessOrEqual(maxLevel));
      gf.And(new Filter(ufsic.scheme.Menus.LangId,getLang().getLangId()).Or(ufsic.scheme.Menus.LangId).IsNull());
      
      menus.open(null,gf,new Orders(ufsic.scheme.Menus.Level,ufsic.scheme.Menus.Priority));
    }
    return menus;
  }
  
  public Menus getMenus(int maxLevel) {
    
    if (isNull(menus)) {
      menus = new Menus(this);
    }
    return getMenus(menus.isEmpty(),maxLevel);
  }
  
  public Dirs getDirs() {
    
    if (isNull(dirs)) {
      dirs = new Dirs(this);
    }
    return dirs;
  }

  public Publications getPublications() {
    
    if (isNull(publications)) {
      publications = new Publications(this);
    }
    return publications;
  }

  public Messages getMessages() {
    
    if (isNull(messages)) {
      messages = new Messages(this);
    }
    return messages;
  }

  public Patterns getPatterns() {

    if (isNull(patterns)) {
      patterns = new Patterns(this);
    }
    return patterns;
  }

  public Confirms getConfirms() {
    
    if (isNull(confirms)) {
      confirms = new Confirms(this);
    }
    return confirms;
  }
  
  public Page getPage() {
    
    return page;
  }
  
  public void setPage(Page page) {
    
    this.page = page;
  }

  public Clients getClients() {
    
    if (isNull(clients)) {
      clients = new Clients(this);
    }
    return clients;
  }
  
  public Client getClient() {
 
    Client ret = null;
    if (isNotNull(client))  {
      ret = client;
    } else {
      if (isNotNull(account)) {
        ret = getClients().getClient(account.getAccountId());
        client = ret;
      }
    }
    return ret;
  }
  
  public Publication getPublication() {
    
    Publication ret = null;
    if (isNotNull(publication))  {
      ret = publication;
    } else {
      if (isNotNull(page)) {
        ret = getPublications().getPublication(page.getPageId());
        publication = ret;
      }
    }
    return ret;
  }

  public String buildURI(String scheme, String host, Integer port, String path, String query, String fragment, boolean full) {
    
    String ret = path;
    URI out;
    if (isNotNull(uri)) {

      String newScheme = isNotNull(scheme)?scheme:uri.getScheme();
      String newUserInfo = uri.getUserInfo();
      String newHost = isNotNull(host)?host:uri.getHost();
      int newPort = isNotNull(port)?port:uri.getPort();
      String newPath = isNotNull(path)?path:uri.getPath();
      String newQuery = isNotNull(query)?query:uri.getQuery();
      String newFragment = isNotNull(fragment)?fragment:uri.getFragment();
      
      try {
        out = new URI(newScheme,newUserInfo,newHost,newPort,newPath,newQuery,newFragment);
        out = out.normalize();
        String s = out.toASCIIString();
        if (full) {
          ret = s;
        } else {
          String p = out.getPath();
          String q = out.getQuery();
          ret = String.format("%s%s",isNotNull(p)?p:"",isNotNull(q)?"?"+q:"");
        }  
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  public String valueFormat(Value value, String format) {
    
    String ret = value.asString();
    if (value.isNotNull() && isNotNull(format)) {
      
      Object obj =  value.getObject();
      
      try {
        
        String lng = lang.getLangId().asString();
        
        if (obj instanceof oracle.sql.TIMESTAMP) {
          
          Calendar cal = Calendar.getInstance();
          cal.setTimeInMillis(value.asTimestamp().getTime());

          SimpleDateFormat sdf = new SimpleDateFormat(format,new Locale(lng.toLowerCase(),lng.toUpperCase()));

          ret = sdf.format(cal.getTime());
          
        } else if (obj instanceof Number) {
          
          DecimalFormat df = new DecimalFormat(format);
          ret = df.format(obj);
          
        } else {
          
          ret = String.format(new Locale(lng.toLowerCase(),lng.toUpperCase()),format,obj);
        }
        
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }

  public Value getApplicationId() {
    
    Value ret = null;
    if (isNotNull(application)) {
      ret = application.getAccountId();
    }
    return ret;
  }  
  
  public boolean templateExists(String templateId) {
    
    return getTemplates().exists(templateId);
  }
  
  public HttpServletRequest getRequest() {
    
    HttpServletRequest ret = null;
    if (isNotNull(application) && application instanceof IServletApplication) {
      ret = ((IServletApplication)application).getRequest();
    }
    return ret;
  }

  public HttpServletResponse getResponse() {

    HttpServletResponse ret = null;
    if (isNotNull(application) && application instanceof IServletApplication) {
      ret = ((IServletApplication)application).getResponse();
    }
    return ret;
  }

  public Provider getProvider() {

    Provider ret = provider;
    if (isNull(ret)) {
      if (isNotNull(application) && application instanceof IDatabaseApplication) {
        ret = ((IDatabaseApplication)application).getProvider();
      }
    }
    return ret;
  }
  
  public IMessageGateRemote getMessageGate() {
    
    IMessageGateRemote ret = null;
     if (isNotNull(application) && application instanceof ISchemeServletApplication) {
      ret = ((ISchemeServletApplication)application).getMessageGate();
    }
    return ret;
  }

  public String getResponseHeaders(String delim) {
  
    String ret = null;
    StringBuilder sb = new StringBuilder();
    HttpServletResponse response = getResponse();
    Collection<String> col = response.getHeaderNames();
    Iterator<String> it = col.iterator();
    while (it.hasNext()) {
      String name = (String)it.next();
      String value = response.getHeader(name);
      sb.append(name).append(": ").append(value).append(delim);
    }
    String s = sb.toString().trim();
    if (!s.equals("")) {
      ret = s;
    } 
    return ret;
  }
  
  public String getResponseHeaders() {
    
    return getResponseHeaders(Utils.getLineSeparator()); 
  }
  
  public String getRequestHeaders(String delim) {
  
    StringBuilder sb = new StringBuilder();
    Enumeration en = getRequest().getHeaderNames();
    while (en.hasMoreElements()) {
      String name = (String)en.nextElement();
      String value = getRequest().getHeader(name);
      sb.append(name).append(": ").append(value).append(delim);
    }
    return sb.toString().trim();
  }
  
  public String getRequestHeaders() {
    
    return getRequestHeaders(Utils.getLineSeparator());
  }
  
  public byte[] getRequestBody() {
    
    byte[] ret = new byte[] {};
    try {
      HttpServletRequest request = getRequest();
      String method = request.getMethod();
      if (isNotNull(method) && method.toUpperCase().equals("GET")) {
        String query = request.getQueryString();
        if (isNotNull(query)) {
          ret = query.getBytes();
        }
      } else {
        ServletInputStream stream = request.getInputStream();
        if (isNotNull(stream)) {
          try (ByteArrayOutputStream b = new ByteArrayOutputStream()) {
            byte[] buf = new byte[1024];
            int count = 0;
            while ((count = stream.read(buf))>0) {
              b.write(buf,0,count);
            }
            ret = b.toByteArray();
          }
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  public void setStamp(Value stamp) {
    this.stamp = stamp;
  }
  
  public Value getStamp() {
    
    if (isNull(stamp)) {
      stamp = getProvider().getNow();
    }
    return stamp;
  }
  
  public IApplication getApplication() {
    return application;
  }
  
  public String getOption(String name, String def) {
    
    String ret = def;
    if (isNotNull(application)) {
      ret = application.getOption(name,def);
    }
    return ret;
  }
  
  public String getOption(String name) {
    
    return getOption(name,(String)null);
  }
  
  public Integer getOption(String name, Integer def) {
    
    Integer ret = def;
    if (isNotNull(application)) {
      String s = getOption(name,isNotNull(def)?def.toString():null);
      if (isNotNull(s)) {
        ret = Utils.toInteger(s);
      }
    }
    return ret;
  }
  
  public String getDefaultDir() {
    
    return getOption("Scheme.DefaultDir","");
  }
  
  public String getJsDir() {
    
    return getOption("Scheme.JsDir","");
  }
  
  public String getCssDir() {
    
    return getOption("Scheme.CssDir","");
  }  
  
  public String getHtmlDir() {
    
    return getOption("Scheme.HtmlDir","");
  }

  public String getTemplateDir() {
    
    return getOption("Scheme.TemplateDir","");
  }

  public String getPatternDir() {
    
    return getOption("Scheme.PatternDir","");
  }
  
  
}