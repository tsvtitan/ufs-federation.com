package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.Record;

public class CacheHandler extends Handler {

  public CacheHandler(Path path) {
    super(path);
  }

  public static boolean needSession() {
    return false;
  }
  
  @Override
  public boolean process(Comm comm) {

    boolean ret = super.process(comm);
    
    String id = "id";
    if (getPath().parameterExists(id)) {
      
      Object cacheId = getPath().getParameterValue(id);
      if (isNotNull(cacheId)) {
      
        Caches chs = getScheme().getCaches();
        Filter f = new Filter(Caches.CacheId,cacheId);
        Record r = getProvider().first(chs.getViewName(),null,f);
        if (isNotNull(r)) {
          Cache cache = new Cache(chs,r);
          cache.setPath(getPath());
          ret = cache.trySend();
        }
      }
    }
    return ret;
  }
  
}
