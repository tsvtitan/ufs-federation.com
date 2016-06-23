package ufsic.scheme.handlers.mobile;

import java.sql.Timestamp;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;
import ufsic.providers.Filter;
import ufsic.providers.MysqlProvider;
import ufsic.providers.Orders;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Cache;
import ufsic.scheme.Caches;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Session;
import ufsic.scheme.Token;
import ufsic.scheme.Tokens;
import ufsic.scheme.handlers.JsonHandler;
import ufsic.scheme.mobile.MobileActivities;
import ufsic.scheme.mobile.MobileFile;
import ufsic.scheme.mobile.MobileFiles;
import ufsic.utils.Location;
import ufsic.utils.Utils;

public abstract class BaseHandler extends JsonHandler {

  private Tokens tokens = null;
  private MobileFiles mobileFiles = null;
  private MysqlProvider wwwProvider = null;
  private MobileActivities mobileActivities = null;
  private Comm comm = null;
  
  final private Map<String,String> contentTypes = new HashMap<>();
  {
    contentTypes.put("jpg","image/jpg");
    contentTypes.put("jpeg","image/jpg");
    contentTypes.put("png","image/png");
    contentTypes.put("gif","image/gif");
    contentTypes.put("pdf","application/pdf");
    contentTypes.put("xls","application/xls");
  }
  
  final static protected Integer ErrorCodeInternalError = 1;
  final static protected Integer ErrorCodeLackOfParameters = 100;
  final static protected Integer ErrorCodeCategoryNotFound = 102;
  
  final static private String wwwProviderJndi = "BaseHandler.WWWProvider.Jndi";
  final static private String wwwDateTimeFormat = "yyyy-MM-dd HH:mm:ss";
  final static private String handlerFilesPath = "BaseHandler.FilesPath";
  final static private String defaultFilesPath = "/files";
  final static private String defaultLocation = "BaseHandler.DefaultLocation";
  
  private Integer maxCacheSize = 1024*1024*3;
  
  public BaseHandler(Path path) {
    super(path);
    maxCacheSize = getScheme().getOption("BaseHandler.MaxCacheSize",maxCacheSize);
  }
  
  public static boolean isPathRestricted() {
    return false;
  }
  
  public static boolean needSession() {
    return true;
  }
  
  protected class Error {
    
    private String code = "";
    private String message = "";
    
    public String getCode() {
      return code;
    }
    
    public void setCode(String code) {
      this.code = code;
    }
    
    public void setCode(Integer code) {
      this.code = isNotNull(code)?code.toString():"";
    }
    
    public String getMessage() {
      return message;
    }
    
    public void setMessage(String message) {
      this.message = isNotNull(message)?message:"";
    }
  }
  
  protected class BaseRequest {
    
  }
  
  protected class BaseResponse extends Response {
  
    private Error error = null;
    
    public Error getError() {
      
      if (isNull(error)) {
        error = new Error();
      }
      return error;
    }
    
    public void setError(Error error) {
      this.error = error;
    }
    
  }
  
  protected class BaseResponseException extends ResponseException {
    
    private BaseResponse response = null;
    private String code;
    
    protected BaseResponseException(BaseResponse response, Integer code, String message) {
      super(message);
      this.response = response;
      if (isNotNull(response)) {
        response.getError().setCode(code);
        response.getError().setMessage(message);
      }
    }
    
    protected BaseResponseException(BaseResponse response, Integer code) {
      this(response,code,null);
    }
    
    public BaseResponse getResponse() {
      return response;
    }
    
    public String getCode() {
      return code;
    }
   
  }
  
  final protected Tokens getTokens() {
    
    if (isNull(tokens)) {
      tokens = new Tokens(getScheme());
    }
    return tokens;
  }
  
  final protected MobileFiles getMobileFiles() {
  
    if (isNull(mobileFiles)) {
      mobileFiles = new MobileFiles(getScheme());
    }
    return mobileFiles;
  }
  
  final protected Provider getWWWProvider() {
    
    if (isNull(wwwProvider)) {
      String jndi = getScheme().getOption(wwwProviderJndi);
      wwwProvider = new MysqlProvider(getEcho(),getLogger(),jndi);
    } 
    return wwwProvider;
  }
  
  final protected String formatForWWWDateTime(Timestamp stamp) {
    
    SimpleDateFormat sdf = new SimpleDateFormat(wwwDateTimeFormat);
    return sdf.format(new Date(stamp.getTime()));
  }
  
  final protected MobileActivities getMobileActivities() {
    
    if (isNull(mobileActivities)) {
      mobileActivities = new MobileActivities(getScheme());
    }  
    return mobileActivities;
  }
  
  final protected class SizedCache extends Cache {

    private Integer size = null;
    
    public SizedCache(SchemeTable table, Record record) {
      super(table, record);
    }
    
    public SizedCache(SchemeTable table) {
      super(table);
    }
    
    public Integer getSize() {
      return size;
    }
    
    public void setSize(Integer size) {
      this.size = size;
    }
  }
  
  final protected SizedCache getCache(Value data, Location location, boolean save) {
    
    Scheme scheme = getScheme();
    SizedCache ret = new SizedCache(scheme.getCaches());
    
    byte[] bytes = new byte[] {};

    if (isNotNull(data) && data.isNotNull()) {
      int len = data.length();
      if (len>0 && len<=maxCacheSize) {
        bytes = data.asBytes();
      }
      ret.setSize(len);
    }

    if (bytes.length==0 && isNotNull(location)) {
      
      Location.Type type = location.getType();
      if (type!=Location.Type.UNKNOWN) {
        int size = location.length();
        if (save) {
          if (size>0 && size<=maxCacheSize) {
            bytes = location.getBytes();
          }
        }
        ret.setSize(size);
      }
    }

    if (bytes.length>0 && save) {

      Session session = scheme.getSession();
      
      String cacheId = Utils.md5(bytes).toUpperCase();
      Filter filter = new Filter(Caches.CacheId,cacheId);
      boolean needNew = true;
      
      Cache c = scheme.getCaches().first(filter);
      if (isNotNull(c)) {
        
        needNew = false;
        
        Value expired = c.getExpired();
        if (expired.isNotNull()) {
          
          long t1 = scheme.getStamp().asTimestamp().getTime();
          long t2 = expired.asTimestamp().getTime();
          if (t1<=t2) {
            
            ret = new SizedCache(scheme.getCaches(),c);
            ret.setSize(bytes.length);
            
          } else {
            
            SizedCache cache = new SizedCache(scheme.getCaches());
            cache.setLangId(scheme.getLangId());
            cache.setExpired(scheme.getStamp().addMonths(1)); 
            cache.setSessionId(isNotNull(session)?session.getSessionId():null);
            cache.setPathId(null);
            cache.setCommId(isNotNull(comm)?comm.getCommId():null);
            cache.setData(bytes);
            cache.setHeaders(null);
            cache.setSize(bytes.length);
            
            if (cache.update(filter)) {
              ret = cache;
              ret.setCacheId(cacheId);
            }
          }
        }
      }
      
      if (needNew) {
        
        SizedCache cache = new SizedCache(scheme.getCaches());
        cache.setCacheId(cacheId);
        cache.setLangId(scheme.getLangId());
        cache.setExpired(scheme.getStamp().addMonths(1)); 
        cache.setSessionId(isNotNull(session)?session.getSessionId():null);
        cache.setPathId(null);
        cache.setCommId(isNotNull(comm)?comm.getCommId():null);
        cache.setData(bytes);
        cache.setHeaders(null);
        cache.setSize(bytes.length);

        if (cache.insert()) {
          ret = cache;
        } 
      }
    }
    return ret;
  }
  
  final protected SizedCache getCache(Value data, Location location) {
    
    return getCache(data,location,true);
  }

  final protected SizedCache getCache(Location location, boolean save) {
    
    return getCache(null,location,save);
  }
  
  final protected String getDefaultLocation() {
  
    Scheme scheme = getScheme();
    return scheme.getOption(defaultLocation,scheme.getDefaultDir());
  }
  
  final protected SizedCache getCache(String location, Value data, boolean save) {
    
    SizedCache ret = null;
    if (isNotNull(location)) {
      
      Location loc = new Location(location,getDefaultLocation());
      ret = getCache(data,loc,save);
    }
    return ret;
  }
  
  final protected SizedCache getCache(String location, Value data) {
    
    return getCache(location,data,true);
  }
  
  final protected String getContentType(String extension) {
    
    String ret = null;
    if (isNotNull(extension)) {
      ret = contentTypes.get(extension);
    }
    return ret;
  }

  final protected MobileFile getFile(Token token, String location, String name, String extension, Value data, boolean save) {
    
    MobileFile ret = null;
    if (isNotNull(token) && isNotNull(location)) {
      
      boolean success = false;
      
      Filter filter = new Filter(MobileFiles.TokenId,token.getTokenId()).And(MobileFiles.Location,location);
      MobileFile file = getMobileFiles().first(filter,new Orders().AddDesc(MobileFiles.Created));
      if (isNull(file)) {
        
        SizedCache cache = getCache(location,data,save);
        
        String ext = extension;
        if (!isEmpty(ext)) {
          ext = ext.toLowerCase();
        }
        
        Integer size = cache.getSize();
        if (isNotNull(size) && size>0) {
        
          file = new MobileFile(getMobileFiles());
          file.setMobileFileId(getProvider().getUniqueId());
          file.setTokenId(token.getTokenId());
          file.setCacheId(isNotNull(cache)?cache.getCacheId():null);
          file.setLocation(location);
          file.setName(name);
          file.setExtension(ext);
          file.setContentType(getContentType(ext));
          file.setFileSize(cache.getSize());

          success = file.insert();
        }
        
      } else {
        success = true;
      }
      
      if (success && isNotNull(file)) {
        ret = file;
      }
    }
    return ret;
  }
  
  final protected String getFileUrl(MobileFile file) {
    
    String ret = "";
    if (isNotNull(file)) {
      
      String filesPath = getScheme().getOption(handlerFilesPath,defaultFilesPath);
        
      ret = String.format("%s/%s",filesPath,file.getMobileFileId().asString());
    }
    return ret;
  }
  
  final protected String getFileUrl(Token token, String location, String name, String extension, Value data, boolean save) {
    
    return getFileUrl(getFile(token,location,name,extension,data,save));
  }
  
  final protected String getFileUrl(Token token, String location, String name, String extension, Value data) {
    
    return getFileUrl(token,location,name,extension,data,true);
  }
          
  protected void setTestHtml(StringBuilder builder) {
    
  }
  
  private String getTestHtml() {
    
    StringBuilder builder = new StringBuilder();
    setTestHtml(builder);
    return builder.toString();
  }
  
  private boolean processTest() {
  
    Path path = getPath();
    String s = String.format("<form method=\"post\" action=\"%s\">%s<input type=\"submit\"/></form>",
                             path.getPathString(),getTestHtml());
    return getEcho().write(s);
  }
  
  private void setHeaders(boolean textOrJson) {
    
    Path path = getPath();
    path.getResponse().setCharacterEncoding(Utils.getCharset().name());
    path.setContentHeaders(textOrJson?"text/html":getContentType(),getEcho().getBufStream().size());
  }
  
  @Override
  protected Response prepareExceptionResponse(Request request, Exception exception) {
    
    Response ret = null;
    if (isNotNull(exception)) {
      if (exception instanceof BaseResponseException) { 
        ret = ((BaseResponseException)exception).getResponse();
      } else {
        BaseResponse r = new BaseResponse();
        r.getError().setCode(ErrorCodeInternalError);
        r.getError().setMessage(exception.getMessage());
        ret = r;
      }
    }
    return ret;
  }
  
  public void setComm(Comm comm) {
    this.comm = comm;
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = false;
    
    setComm(comm);
    
    /*try {
      Thread.currentThread().sleep(2500);
    } catch(Exception e) {
      
    }*/
    
    String rest = getPath().getRestPathValue();
    if (isNotNull(rest) && rest.equals("test")) {

      ret = processTest();
      if (ret) {
        setHeaders(true);
      }
      
    } else {

      ret = super.process(comm);
      if (ret) {
        setHeaders(false);
      }
      
      if (isNotNull(wwwProvider)) {
        wwwProvider.disconnect();
      }
    }
    return ret;
  }
  
}
