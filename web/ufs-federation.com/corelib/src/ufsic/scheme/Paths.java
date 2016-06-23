package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.providers.Dataset;
import ufsic.providers.Value;

public class Paths<T extends Path> extends SchemeTable<T> {

  public final static String TableName = "PATHS";
  
  public final static String PathId = "PATH_ID";
  public final static String LangId = "LANG_ID";
  public final static String ParentId = "PARENT_ID";
  public final static String Created = "CREATED";
  public final static String Begin = "BEGIN";
  public final static String End = "END";
  public final static String Name = "NAME";
  public final static String Description = "DESCRIPTION";
  public final static String Priority = "PRIORITY";
  public final static String ContentType = "CONTENT_TYPE";
  public final static String Locked = "LOCKED";
  public final static String CheckPermisssions = "CHECK_PERMISSIONS";
  public final static String CacheTimeout = "CACHE_TIMEOUT";
  public final static String HandlerType = "HANDLER_TYPE";
  public final static String Path = "PATH";
  public final static String Level = "LEVEL";
  public final static String Link = "LINK";
  
  private final static String pathDelimeter = "/";
  
  public Paths(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Paths(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    return Path.class;
  }

  public Path newByType(String pageType) {
    
    Path ret = null;
    return ret;
  }
  
  private Path getCertainPath(String path) {
    
    Path ret = null;
    
    Scheme scheme = getScheme();
    
    if (isNotNull(scheme.getLang())) {
      
      String pathId = null;
      String[] s = path.split("/");
      if (s.length>0) {
        pathId = s[s.length-1];
      }
      
      Filter f1 = new Filter().Add(Path).Equal(path).Or(PathId,pathId);
      Filter f2 = new Filter().Add(Locked).IsNull();
      Filter f = new GroupFilter(f1).And(f2);

      Dataset<Record> ds = getProvider().select(getViewName(),f,new Orders(Priority,LangId));
      if (isNotNull(ds)) {
        Value currentLangId = scheme.getLang().getLangId();
        for (Record r: ds) {
          Value langId = r.getValue(LangId);
          if (langId.same(currentLangId)) {
            ret = new Path(this,r);
            break;
          }
        }
        if (isNull(ret)) {
          for (Record r: ds) {
            Value langId = r.getValue(LangId);
            if (langId.isNull()) {
              ret = new Path(this,r);
              break;
            }
          }
        }
      }
      if (isNotNull(ret)) {
        if (ret.getPathId().same(pathId)) {
          ret.redirectToSelf();
          ret = null;
        }
      }
    }
    return ret;
  }
  
  /*private Path getPathById(String path) {
    
    Path ret = null;
    if (isNotNull(path) && (!path.trim().equals(""))) {
      String[] s = path.split("/");
      if (s.length>0) {
        String last = s[s.length-1];
        if (!last.trim().equals("")) {

          Filter f1 = new Filter().Add(PathId).Equal(last);
          Filter f2 = new Filter().Add(Locked).IsNull();
          Filter f = new GroupFilter(f1).And(f2);
          
          Record r = provider.first(getViewName(),f);
          if (isNotNull(r)) {
            ret = new Path(this,r);
            
          }
        }
      }
    }
    return ret;  
  }*/
  
  private String getPathByParts(String[] parts, int index) {
    
    String ret = "";
    if (index>=0 && index<parts.length) {
      StringBuilder sb = new StringBuilder();
      for (int i=0; i<index; i++) {
        if (!parts[i].trim().equals("")) {
          sb.append(pathDelimeter).append(parts[i]);
        }
      }
      ret = sb.toString();
    }
    return ret;
  }
  
  private Path getClosestPath(String path) {
    
    Path ret = null;
    String[] parts = path.split(String.format("[%s]",pathDelimeter));
    if (parts.length>0) {
      for (int i=parts.length-1; i>=0; i--) {
        String s = getPathByParts(parts,i);
        ret = getCertainPath(s);
        if (isNotNull(ret)) {
          break;
        }
      }
    }
    return ret;
  }

  public Path queryPath(String path) {
    
    Path ret = getCertainPath(path);
    if (isNull(ret)) {
      
      ret = getClosestPath(path);
    }
    return ret;
  }
  
  
  
}
