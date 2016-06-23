package ufsic.scheme.thymeleaf;

import org.thymeleaf.Arguments;
import org.thymeleaf.messageresolver.AbstractMessageResolver;
import org.thymeleaf.messageresolver.MessageResolution;
import ufsic.scheme.Dictionary;
import ufsic.scheme.Scheme;
import ufsic.utils.Utils;

public class SchemeMessageResolver extends AbstractMessageResolver {

  private Scheme scheme = null;
  private Dictionary dic = null;
  private String prefix = null;
  
  public SchemeMessageResolver(Scheme scheme) {
    
    this.scheme = scheme;
    this.dic = scheme.getDictionary();
    this.prefix = dic.getPrefix();
  }
  
  @Override
  public MessageResolution resolveMessage(Arguments arguments, String key, Object[] messageParameters) {
    
    String k = key;
    String s = dic.get(prefix+k);
    if (Utils.isNull(s)) {
      s = prefix+k;
    } else {
      /*Object[] arr = messageParameters;
      for (int i=0; i<arr.length; i++) {
        if ((Utils.isNotNull(arr[i])) && (arr[i] instanceof String)) {
          arr[i] = dic.replace((String)arr[i]);
        }
      }*/
      if (messageParameters.length>0) {
        s = String.format(s,messageParameters);
      }
    }
    return new MessageResolution(s);
  }

}
