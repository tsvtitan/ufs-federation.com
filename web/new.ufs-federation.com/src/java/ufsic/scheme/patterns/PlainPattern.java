package ufsic.scheme.patterns;

import ufsic.scheme.Scheme;
import ufsic.utils.Utils;

public class PlainPattern extends HtmlPattern {

  public PlainPattern(Scheme scheme, boolean autoLoad) {
    super(scheme, autoLoad);
  }

  public PlainPattern(Scheme scheme) {
    super(scheme);
  }

  @Override
  public String getContentType() {
    return "text/plain";
  }
  
  @Override
  protected byte[] getBodyByLocation() {

    byte[] ret = super.getBodyByLocation();
    if (ret.length>0) {
      StringBuilder sb = new StringBuilder();
      sb.append("<!--/*/<th:block th:inline=\"text\">/*/-->");
      sb.append(Utils.getLineSeparator());
      sb.append(new String(ret));
      sb.append(Utils.getLineSeparator());
      sb.append("<!--/*/</th:block>/*/-->");
      String s = sb.toString();
      ret = s.getBytes();
    }
    return ret;
  }

  
}
