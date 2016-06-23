package ufsic.scheme.handlers;

import ufsic.providers.Filter;
import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.Handler;
import ufsic.scheme.PageAnalogs;
import ufsic.scheme.Path;
import ufsic.scheme.Paths;
import ufsic.scheme.Scheme;

public class LangHandler extends Handler {

  public LangHandler(Path path) {
    super(path);
  }

  @Override
  public boolean process(Comm comm) {

    boolean ret;
    
    Scheme scheme = getScheme();
    Path path = getPath();
    
    Value langId = scheme.getLang().getLangId();
    if (!langId.same(path.getName().asString().toUpperCase())) {

      String host = scheme.getURI().getHost();
      String[] parts = host.split("[\\.]");
      String newLangId = path.getName().asString().toUpperCase();

      StringBuilder sb = new StringBuilder();
      boolean first = true;

      for (String p: parts) {
        sb.append((first)?"":".");
        if (first) {
          if (langId.same(p.toUpperCase())) {
            p = newLangId.toLowerCase();
          }
          first = false;
        }
        sb.append(p);
      }

      Value pathId = new Value(null);
      String newPath = "";

      Object langLastPageId = path.getLangLastPageId(langId.asString());
      if (isNotNull(langLastPageId)) {

        Value v1 = getProvider().firstValue(scheme.getPaths().getViewName(),Paths.Path,new Filter(Paths.PathId,langLastPageId));
        if (v1.isNotNull()) {
          newPath = v1.asString();
          pathId.setObject(langLastPageId);
        }
      }

      if (pathId.isNotNull()) {

        Value v2 = getProvider().firstValue(scheme.getPageAnalogs().getViewName(),PageAnalogs.Path,new Filter(PageAnalogs.PageId,pathId).And(PageAnalogs.LangId,newLangId));
        if (v2.isNotNull()) {
          newPath = v2.asString();
        }
        
        path.clearPathCache(pathId);
      }

      ret = path.redirect(path.buildURI(null,sb.toString().toLowerCase(),null,path.getBasePath(newPath),null,null,true));
      
    } else {
      
      ret = path.redirect(path.getRootPath());
    }
    return ret;
  }
  
}