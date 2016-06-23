package ufsic.scheme.forms;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeObject;

public class Method extends SchemeObject {

  private String name = null;
  private MethodParams params = new MethodParams();

  public Method(Scheme scheme, String ident) {

    super(scheme);

    String[] tmp = ident.split("\\(");
    if (tmp.length>0) {

      this.name = tmp[0];

      if (tmp.length>1) {

        String[] arr = tmp[1].split("\\)");
        if (arr.length>0) {

          tmp = arr[0].split(",");
          if (tmp.length>0) {
            for (int i=0;i<tmp.length;i++) {
              params.add(new MethodParam(scheme,tmp[i]));
            }
          } else {
            params.add(new MethodParam(scheme,arr[0]));
          }
        }
      }
    } else {
      this.name = ident;
    }
  }

  String getName() {
    return name;
  }

  MethodParams getParams() {
    return params;
  }
  
}
