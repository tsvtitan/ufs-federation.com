package ufsic.scheme.forms;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeObject;

public class MethodParam extends SchemeObject {

  private String value = null;

  public MethodParam(Scheme scheme, String value) {

    super(scheme);
    
    this.value = value;
  }

  String getValue() {
    return getScheme().getDictionary().replace(value);       
  }
  
}
