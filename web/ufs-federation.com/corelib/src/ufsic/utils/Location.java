package ufsic.utils;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.StringReader;
import java.net.URI;
import java.net.URL;
import java.net.URLConnection;
import java.util.Collections;
import java.util.Enumeration;
import java.util.LinkedHashSet;
import java.util.Properties;

public class Location {

  public enum Type {
  
    UNKNOWN, FILE, HTTP, FTP;
    
    public static Type getTypeByPath(String path) {
      
      Type ret = UNKNOWN;
      try {
        File f = new File(path);
        if (f.exists()) {
          ret = FILE; 
        } else {
          URI uri = new URI(path);
          if (Utils.isNotNull(uri)) {

            String scheme = uri.getScheme();
            switch (scheme.toLowerCase()) {
              case "http": {
                ret = HTTP;
                break;
              }
              case "ftp": {
                ret = FTP;
                break;
              }
            }
          }
        }
      } catch(Exception e) {
        //
      }
      return ret;
    }
  }
  
  private final String path;
  private File file;
  private Type type = Type.UNKNOWN;
  
  public Location(String path) {
    
    this.path = path;
    this.type = Type.getTypeByPath(path);
  }

  private boolean exists(String path) {

    boolean ret = false;
    Type t = Type.getTypeByPath(path);
    switch (t) {
      case UNKNOWN: {
        break;
      }
      case FILE: {
        if (Utils.isNotNull(file)) {
          ret = file.exists();
        }
        if (!ret) {
          file = new File(path);
          ret = file.exists();
        }
        break;
      }
      case HTTP: {
        try {
          URLConnection con = new URL(path).openConnection();
          ret = Utils.isNotNull(con);
        } catch (Exception e) {
          //
        }
        break;
      }
    }
    return ret;
  }
  
  private String getComplexPath(String path, String rootPath) {
    
    String ret = path;
    boolean b = exists(path);
    if (!b && !rootPath.equals("")) {
      StringBuilder sb = new StringBuilder();
      String delim = Utils.getPathSeparator();
      if (Utils.isNotNull(path)) {
        if (path.length()>0 && path.substring(0,delim.length()).equals(delim)) {
          delim = ""; 
        }
      }
      sb.append(rootPath).append(delim).append(path);
      b = exists(sb.toString());
      if (b) {
        ret = sb.toString();
      }
    }
    return ret;
  }
  
  public Location(String path, String rootPath) {
    
    this.path = getComplexPath(path,rootPath);
    this.type = Type.getTypeByPath(this.path);
  }
  
  public Type getType() {
    return type;
  }
  
  public boolean exists() {
    
    return exists(path);
  }

  public File getFile() {
    
    File ret = file;
    if (Utils.isNull(ret)) {
      file = new File(path);
      ret = file;
    }
    return ret;
  }
  
  public int length() {
    
    int ret = 0;
    
    switch (type) {
      case UNKNOWN: {
        break;
      }
      case FILE: {
        ret = (int)getFile().length();
        break;
      }
      case HTTP: {
        try {
          ret = new URL(path).openConnection().getContentLength();
        } catch (Exception e) {
          //
        }
        break;
      }
    }
    return ret;
  }
  
  private byte[] getFileBytes() {
    
    byte[] ret = new byte[] {};
    
    File f = getFile();
    if (f.exists() && f.canRead()) {
      try (FileInputStream stream = new FileInputStream(f)) {
        ret = new byte[(int)f.length()];
        stream.read(ret);
      } catch (Exception e) {
        //
      }
    }
    return ret;
  }
  
  private byte[] getHttpBytes() {
    
    byte[] ret = new byte[] {};
    
    URL url;
    try {
      url = new URL(path);
      if (Utils.isNotNull(url)) {
        URLConnection con = url.openConnection();
        if (Utils.isNotNull(con)) {
          int len = con.getContentLength();
          if (len>0) {
            ret = new byte[len];
            int count;
            int offset = 0;
            try (InputStream is = new BufferedInputStream(con.getInputStream())) {
              while (offset < len) {
                count = is.read(ret,offset,len-offset);
                if (count==-1) {
                  break;
                }
                offset += count;
              }
            }
          } else if (len<0) {
            String charset = con.getContentEncoding();
            StringBuilder sb = new StringBuilder();
            BufferedReader reader = new BufferedReader(new InputStreamReader(con.getInputStream()));
            String line;
            while ((line = reader.readLine())!=null) {
              sb.append((sb.length()!=0)?Utils.getLineSeparator():"").append(line);
            }
            if (Utils.isNotNull(charset)) {
              ret = sb.toString().getBytes(charset);
            } else {
              ret = sb.toString().getBytes();
            }
          }
        }
      }
    } catch (Exception e) {
      
    }
    return ret;
  }
  
  public byte[] getBytes() {
    
    byte[] ret = new byte[] {};
    
    switch (type) {
      case UNKNOWN: {
        break;
      }
      case FILE: {
        ret = getFileBytes();
        break;
      }
      case HTTP: {
        ret = getHttpBytes();
        break;
      }
    }
    
    return ret;
  }
  
  public String getContent() {
    
    return new String(getBytes());
  }
  
  public Properties getProperties() {
    
    Properties ret = new LinkedProperties();
    String content = getContent();
    if (!Utils.isEmpty(content)) {
      try {
        ret.load(new StringReader(content));
      } catch (Exception e) {
        //
      }
    }
    return ret;
  }

  public boolean writeFile(OutputStream stream) {

    boolean ret = false;
    File f = getFile();
    if (f.exists() && f.canRead()) {
      try (FileInputStream ins = new FileInputStream(f)) {
        byte buffer[] = new byte[4096];
        int count;
        while ((count = ins.read(buffer))!=-1) {
          stream.write(buffer,0,count);
        }
        ret = true;
      } catch (Exception e) {
        //
      }
    }
    return ret;
  }

  public boolean writeHttp(OutputStream stream) {
    
    boolean ret = false;
    URL url;
    try {
      url = new URL(path);
      if (Utils.isNotNull(url)) {
        URLConnection con = url.openConnection();
        if (Utils.isNotNull(con)) {
          int len = con.getContentLength();
          if (len>0) {
            try (InputStream is = new BufferedInputStream(con.getInputStream())) {
              byte buffer[] = new byte[4096];
              int count;
              while ((count = is.read(buffer))!=-1) {
                stream.write(buffer,0,count);
              }
            }
            ret = true;
          } else if (len<0) {
            String charset = con.getContentEncoding();
            BufferedReader reader = new BufferedReader(new InputStreamReader(con.getInputStream()));
            String line;
            while ((line = reader.readLine())!=null) {
              if (Utils.isNotNull(charset)) {
                stream.write(line.getBytes(charset));
              } else {
                stream.write(line.getBytes());
              }
            }
          }
        }
      }
    } catch (Exception e) {
      
    }
    return ret;
  }
  
  public boolean write(OutputStream stream) {
    
    boolean ret = false;
    
    switch (type) {
      case UNKNOWN: {
        break;
      }
      case FILE: {
        ret = writeFile(stream);
        break;
      }
      case HTTP: {
        ret = writeHttp(stream);
        break;
      }
    }
    return ret;
  }
  
}
