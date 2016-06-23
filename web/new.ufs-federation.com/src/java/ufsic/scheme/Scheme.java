package ufsic.scheme;

import java.net.URI;
import java.text.DecimalFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

import ufsic.controllers.Controller;
import ufsic.controllers.Options;
import ufsic.core.CoreObject;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;

import ufsic.providers.Provider;
import ufsic.providers.Value;
import ufsic.utils.Utils;

import ufsic.scheme.handlers.CacheHandler;

//import oracle.sql.TIMESTAMP;

public class Scheme extends CoreObject {

  protected final URI uri;

  protected final Provider provider;
  protected final Controller controller;
  protected final Options options;
  protected final Langs langs;
  protected final Sessions sessions; 
  protected final Paths paths;
  protected final Files files;
  protected final Dirs dirs;
  protected final Pages pages;
  protected final Templates templates;
  protected final Accounts accounts;
  protected final Comms comms;
  protected final Caches caches;
  protected final Dictionary dictionary;
  protected final PageAnalogs pageAnalogs;
  protected final Menus menus;
  protected final Clients clients;
  protected final Publications publications;
  protected final Messages messages;
  protected final Patterns patterns;
  protected final Confirms confirms;

  private Lang lang = null;
  private Path path = null;
  private Page page = null;
  private Session session = null;
  private Account account = null;
  private Client client = null;
  private Publication publication = null;
  private Value applicationId = null;
  
  private final Path cssPath;
  private final Path jsPath;
  private final Path cachePath;
  
  private final Map<String,Path> internalPaths = new HashMap<>();
  
  public Scheme(Controller controller, String langId, URI uri) {
    
    super(controller.getLogger(),controller.getEcho());
    
    this.uri = uri;
    this.controller = controller;
    this.provider = controller.getProvider();

    this.options = controller.getOptions();
    this.applicationId = new Value(options.getProperty("Application.ID","WebSite"));
    
    this.langs = new Langs(this);
    this.lang = langs.getLang(langId);
    this.dictionary = new Dictionary(this);
    this.sessions = new Sessions(this);
    this.paths = new Paths(this);
    this.files = new Files(this);
    this.dirs = new Dirs(this);
    this.pages = new Pages(this);
    this.templates = new Templates(this);
    this.accounts = new Accounts(this);
    this.comms = new Comms(this);
    this.caches = new Caches(this);
    this.pageAnalogs = new PageAnalogs(this);
    this.menus = new Menus(this);
    this.clients = new Clients(this);
    this.publications = new Publications(this);
    this.messages = new Messages(this);
    this.patterns = new Patterns(this);
    this.confirms = new Confirms(this);
    
    this.cssPath = addInternalPath("/css",CacheHandler.class);
    this.jsPath = addInternalPath("/js",CacheHandler.class);
    this.cachePath = addInternalPath("/cache",CacheHandler.class);
    
  }

  private Path addInternalPath(String path, Class handlerClass) {
  
    String ret = path;
    Path obj = new Path(paths,null);
    obj.setInternal(true);
    obj.setHandlerClass(handlerClass);
    obj.setPath(ret);
    obj.setHandlerType(handlerClass.getName());
    internalPaths.put(Utils.md5(ret.toUpperCase()),obj);
    return obj;
  }
  
  public Path getCssPath() {
  
    return cssPath; 
  }

  public Path getJsPath() {
  
    return jsPath; 
  }
  
  public URI getURI() {
    return uri;
  }
  
  public String getDefaultDir() {
    
    return options.getProperty("DefaultDir","");
  }
  
  public String getJsDir() {
    
    return options.getProperty("JsDir","");
  }
  
  public String getCssDir() {
    
    return options.getProperty("CssDir","");
  }  
  
  public String getHtmlDir() {
    
    return options.getProperty("HtmlDir","");
  }

  public String getTemplateDir() {
    
    return options.getProperty("TemplateDir","");
  }

  public String getPatternDir() {
    
    return options.getProperty("PatternDir","");
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
    /*if (isNull(ret)) {
      
      String[] arr = path.split("/");
      for (int i=0;i<arr.length;i++) {
        arr[i] = String.format("/%s",arr[i]);
      }
      for (Map.Entry<String,Path> entry: internalPaths.entrySet()) {
        
        Path p = entry.getValue();
        s = p.getPath().asString();
        if (Arrays.asList(arr).contains(s)) {
          ret = p;
          break;
        }
      }
    }*/
    return ret;
  }
  
  public Path queryPath(String path) {
    
    //String s = getLastPath(path);
    //this.path = internalPaths.get(s.toLowerCase());
    this.path = searchInternalPath(path.toLowerCase());
    if (isNull(this.path)) {
      this.path = paths.queryPath(path);
    }
    return this.path;
  }
  
  public Session trySession(String sessionId) {
    
    session = sessions.trySession(sessionId);
    if (isNotNull(session)) {
      account = accounts.getAccount(session.getAccountId());
    }
    return session;
  }

  public Controller getController() {
    return controller;
  }
  
  public Provider getProvider() {
    return provider;
  }

  public Lang getLang() {
    return lang;
  }

  public Value getLangId() {
    
    Value ret;
    if (isNotNull(lang)) {
      ret = lang.getLangId();
    } else {
      ret = new Value(null);
    }
    return ret;
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
    return langs;
  }
 
  public Sessions getSessions() {
    return sessions;
  }
 
  public Paths getPaths() {
    return paths;
  }
 
  public Files getFiles() {
    return files;
  }

  public Pages getPages() {
    return pages;
  }

  public Accounts getAccounts() {
    return accounts;
  }

  public Comms getComms() {
    return comms;
  }
  
  public Templates getTemplates() {
    return templates;
  }
  
  public Caches getCaches() {
    return caches;
  }
 
  public Dictionary getDictionary() {
    return dictionary;
  }

  public PageAnalogs getPageAnalogs() {
    return pageAnalogs;
  }
  
  public Menus getMenus(boolean refresh, int maxLevel) {
    
    if (refresh) {
      
      GroupFilter gf = new GroupFilter(new Filter().And(ufsic.scheme.Menus.Invisible).Equal(0).
                                                    And(ufsic.scheme.Menus.Level).LessOrEqual(maxLevel));
      gf.And(new Filter(ufsic.scheme.Menus.LangId,getLang().getLangId()).Or(ufsic.scheme.Menus.LangId).IsNull());
      
      menus.open(null,gf,new Orders(ufsic.scheme.Menus.Level,ufsic.scheme.Menus.Priority));
    }
    return menus;
  }
  
  public Menus getMenus(int maxLevel) {
    
    return getMenus(menus.isEmpty(),maxLevel);
  }
  
  public Dirs getDirs() {
    
    return dirs;
  }

  public Publications getPublications() {
    
    return publications;
  }

  public Messages getMessages() {
    
    return messages;
  }

  public Patterns getPatterns() {

    return patterns;
  }

  public Confirms getConfirms() {
    
    return confirms;
  }
  
  public Page getPage() {
    
    return page;
  }
  
  public void setPage(Page page) {
    
    this.page = page;
  }
  
  public Client getClient() {
 
    Client ret = null;
    if (isNotNull(client))  {
      ret = client;
    } else {
      if (isNotNull(account)) {
        ret = clients.getClient(account.getAccountId());
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
        ret = publications.getPublication(page.getPageId());
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
    
    return applicationId;
  }  
  
  public boolean templateExists(String templateId) {
    
    return templates.exists(templateId);
  }
}