package ufsic.scheme.patterns;

import ufsic.scheme.Scheme;

public class SmsPasswordRestorePattern extends PlainPattern {

  public SmsPasswordRestorePattern(Scheme scheme) {
    super(scheme);
  }
  
  public void setCode(String code) {
    setVar("code",code);
  }

  public void setPassword(String password) {
    setVar("password",password);
  }
  
}
