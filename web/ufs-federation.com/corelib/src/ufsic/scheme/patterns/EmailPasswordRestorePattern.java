package ufsic.scheme.patterns;

import ufsic.scheme.Scheme;

public class EmailPasswordRestorePattern extends HtmlPattern {

  public EmailPasswordRestorePattern(Scheme scheme) {
    super(scheme);
  }
  
  public void setUrl(String url) {
    setVar("url",url);
  }
  
  public void setCode(String code) {
    setVar("code",code);
  }

  public void setPassword(String password) {
    setVar("password",password);
  }
  
}
