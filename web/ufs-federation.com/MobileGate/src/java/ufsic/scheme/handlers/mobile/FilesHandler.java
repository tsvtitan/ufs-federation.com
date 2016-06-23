package ufsic.scheme.handlers.mobile;

import com.sun.xml.messaging.saaj.packaging.mime.internet.MimeUtility;
import javax.servlet.http.HttpServletResponse;
import ufsic.providers.Filter;
import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.mobile.MobileFile;
import ufsic.scheme.mobile.MobileFiles;
import ufsic.scheme.Path;
import ufsic.utils.Location;

public class FilesHandler extends BaseHandler {

  public FilesHandler(Path path) {
    super(path);
  }
 
  public static boolean needSession() {
    return false;
  }

  final public static String getLocation(String table, String fieldId, String id, String fieldData) {
    
    String delim = "|";
    StringBuilder sb = new StringBuilder();
    sb.append(table);
    sb.append(delim);
    sb.append(fieldId);
    sb.append(delim);
    sb.append(id);
    sb.append(delim);
    sb.append(fieldData);
    
    return sb.toString();
  }
          
  private Value getDataByPath(String location) {
    
    Value ret = new Value(null);
    if (isNotNull(location)) {
      
      String[] list = location.split("\\|");
      if (list.length==4) {
        
        String table = list[0];
        String fieldId = list[1];
        String id = list[2];
        String fieldData = list[3];
        
        ret = getProvider().firstValue(table,fieldData,new Filter(fieldId,id));
      }
    }
    return ret;
  }
  
  private void setHeaders(MobileFile file) {
    
    Path path = getPath();
    
    Value contentType = file.getContentType();
    
    String s = contentType.isNotNull()?contentType.asString():"application/octet-stream";
    path.setContentHeaders(s,getEcho().getBufStream().size());
    
    Value name = file.getName();
    if (name.isNotNull()) {
      
      Value extension = file.getExtension();
      String ext = extension.isNotNull()?"."+extension.asString():"";
      String nameExt = String.format("%s%s",name.asString(),ext);
      
      try {
        nameExt = MimeUtility.encodeWord(nameExt);
        path.setHeader("Content-Disposition",String.format("attachment; filename=\"%s\"",nameExt));
      } catch (Exception e) {
        logException(e);
      }
      
      path.setHeader("Content-Transfer-Encoding","binary");
    }
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = false;
    
    setComm(comm);
    
    try {
      
      boolean exists = false;
      String rest = getPath().getRestPathValue();
      
      if (isNotNull(rest)) {

        MobileFile file = getMobileFiles().first(new Filter(MobileFiles.MobileFileId,rest));
        if (isNotNull(file)) {
          
          Integer cacheSize = file.getCacheSize().asInteger();
          boolean needCache = cacheSize==0;
          
          if (!needCache) {
            
            Value expired = file.getCacheExpired();
            if (expired.isNotNull()) {
              
              long t1 = getScheme().getStamp().asTimestamp().getTime();
              long t2 = expired.asTimestamp().getTime();
              needCache = t1>t2;
            }
            
            if (!needCache) {
              exists = getEcho().write(file.getCacheData().asBytes());
            }
          }
            
          if (needCache) {
            
            String location = file.getLocation().asString();        
            Value data = getDataByPath(location);
            Location loc = new Location(location,getDefaultLocation());
            BaseHandler.SizedCache cache = getCache(data,loc);
            
            if (isNotNull(cache) && cache.getCacheId().isNotNull()) {
              
              MobileFile f = new MobileFile(getMobileFiles());
              f.setCacheId(cache.getCacheId());
              f.setFileSize(cache.getSize());
              if (f.update(new Filter(MobileFiles.MobileFileId,file.getMobileFileId()))) {
                data = cache.getData();
                exists = getEcho().write(data.asBytes());
              }
            } else {
              exists = loc.write(getEcho().getBufStream());
              
              if (file.getFileSize().isNull()) {
                MobileFile f = new MobileFile(getMobileFiles());
                f.setFileSize(getEcho().getBufStream().size());
                f.update(new Filter(MobileFiles.MobileFileId,file.getMobileFileId()));
              }
            }
          }
          
          if (exists) {
            setHeaders(file);
          }
        }
      } 

      if (!exists) {
        getPath().getResponse().sendError(HttpServletResponse.SC_NOT_FOUND);
      }
      
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }

}
