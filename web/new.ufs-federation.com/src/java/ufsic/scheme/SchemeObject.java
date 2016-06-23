package ufsic.scheme;

import ufsic.core.CoreObject;

public class SchemeObject extends CoreObject {
  
  private final Scheme scheme;
  
  public SchemeObject(Scheme scheme) {
    
    super(scheme.getLogger(),scheme.getEcho());
    this.scheme = scheme;
  }
    
  public Scheme getScheme() {
    
    return scheme;
  }
  
}
