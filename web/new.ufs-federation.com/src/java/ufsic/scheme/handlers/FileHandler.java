package ufsic.scheme.handlers;

import com.sun.xml.messaging.saaj.packaging.mime.internet.MimeUtility;
import java.io.UnsupportedEncodingException;
import java.nio.charset.Charset;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.apache.commons.codec.binary.Base64;

import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.File;
import ufsic.scheme.Files;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.utils.Location;
import ufsic.utils.Utils;

public class FileHandler extends Handler {

  private final static Charset charset = Utils.getCharset(); 
  
  public FileHandler(Path path) {
    super(path); 
  }

  public static boolean isPathRestricted() {
    return false;
  }

  public static boolean needSession() {
    return false;
  }
  
  private Location getLocation(File file) {
  
    Location ret = null;
    
    Value location = file.getLocation();
    if (location.isNotNull()) {
      
      StringBuilder sb = new StringBuilder();
      sb.append(getScheme().getDefaultDir());
      
      Path path = getPath();
      String p = path.getRestOfRootPath(path.getBasePath(path.getPrevPath()));
      p = p.replace("/",Utils.getPathDelimeter());
      sb.append(p);
      
      ret = new Location(location.asString(),sb.toString());
    }
    return ret;
  }
  
  public void setHeaders(String fileName, Long size) {
    
    Path path = getPath();
    
    path.setContentHeaders("application/octet-stream",size);
    
    /*byte[] bytes = Base64.encodeBase64(fileName.getBytes(charset));
    String newFileName = String.format("=?%s?B?%s?=",charset.name(),new String(bytes));*/
    
    // support for IE?
    
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
      
      Path path = getPath();
      Record r = getProvider().first(scheme.getFiles().getViewName(),null,new Filter(Files.FileId,path.getPathId()));
      if (isNotNull(r)) {
        
        File f = new File(scheme.getFiles(),r);
        Location loc = getLocation(f);
        
        if (isNotNull(loc)) {

          if (loc.exists()) {
            if (f.getName().isNotNull()) {
              String ext = (f.getExtension().isNull())?"":"."+f.getExtension().asString();
              setHeaders(String.format("%s%s",f.getName().asString(),ext),(long)loc.length());
            } else {
              setHeaders("",(long)loc.length());
            }
            ret = loc.write(getEcho().getBufStream());
          }
          
        } else {
          
          Value data = f.getData();
          if (data.isNotNull()) {
            
            byte[] bytes = data.asBytes();
            
            if (f.getName().isNotNull()) {
              String ext = (f.getExtension().isNull())?"":"."+f.getExtension().asString();
              setHeaders(String.format("%s%s",f.getName().asString(),ext),(long)bytes.length);
            } else {
              setHeaders("",(long)bytes.length);
            }
            ret = getEcho().write(bytes);
          }
        }
      } else {

        Location loc = new Location(path.getRestOfRootPath(path.getBasePath(path.getPath().asString())),scheme.getDefaultDir());
        if (loc.exists()) {
          setHeaders(loc.getFile().getName(),loc.getFile().length());
          ret = loc.write(getEcho().getBufStream());
        }
      }
    }
    return ret;
  }
  
  
}
