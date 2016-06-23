package ufsic.scheme.handlers;

import com.sun.xml.messaging.saaj.packaging.mime.internet.MimeUtility;
import java.io.UnsupportedEncodingException;
import java.net.URI;
import java.nio.charset.Charset;
import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.Dir;
import ufsic.scheme.Dirs;
import ufsic.scheme.Handler;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.utils.Location;
import ufsic.utils.Utils;

public class DirHandler extends Handler {

  private final static Charset charset = Utils.getCharset(); 
  
  public DirHandler(Path path) {
    super(path);
  }
  
  public static boolean isPathRestricted() {
    return false;
  }
  
  public static boolean needSession() {
    return false;
  }
  
  public void setHeaders(String fileName, Long size) {
    
    Path path = getPath();
    
    path.setContentHeaders("application/octet-stream",size);
    
    /*byte[] bytes = Base64.encodeBase64(fileName.getBytes(charset));
    String newFileName = String.format("=?%s?B?%s?=",charset.name(),new String(bytes));
    path.setHeader("Content-Disposition",String.format("attachment; filename=\"%s\"",newFileName));*/
    
    
    String newFileName;
    try {
      newFileName = MimeUtility.encodeWord(fileName);
      path.setHeader("Content-Disposition",String.format("attachment; filename=\"%s\"",newFileName));
    } catch (UnsupportedEncodingException ex) {
      logException(ex);
    }
    
    path.setHeader("Content-Transfer-Encoding","binary");
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = super.process(comm); 
    
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
      
      URI uri = scheme.getURI();
      if (isNotNull(uri)) {
        
        Path path = getPath();
        
        Record r = getProvider().first(scheme.getDirs().getViewName(),null,new Filter(Dirs.DirId,path.getPathId()));
        Dir dir = new Dir(scheme.getDirs(),r);
        Value location = dir.getLocation();
        
        StringBuilder sb = new StringBuilder();
        if (location.isNull()) {
          sb.append(path.getMiddlePath());
        } else {
          sb.append(location.asString());
        }
        sb.append(path.getRestPath());
                
        Location loc = new Location(sb.toString(),scheme.getDefaultDir());
        if (loc.exists()) {
          setHeaders(loc.getFile().getName(),loc.getFile().length());
          ret = loc.write(getEcho().getBufStream());
        }
      }
    }
    return ret;
  }
  
}
