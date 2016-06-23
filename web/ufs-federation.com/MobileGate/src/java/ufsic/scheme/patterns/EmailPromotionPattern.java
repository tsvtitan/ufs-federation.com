package ufsic.scheme.patterns;

import ufsic.scheme.Scheme;

public class EmailPromotionPattern extends HtmlPattern {

  public EmailPromotionPattern(Scheme scheme) {
    super(scheme);
  }
  
  public void setName(String name) {
    setVar("name",name);
  }
  
  public void setPhone(String phone) {
    setVar("phone",phone);
  }
  
  public void setEmail(String email) {
    setVar("email",email);
  }
  
  public void setBrokerage(boolean brokerage) {
    setVar("brokerage",brokerage);
  }
  
  public void setYield(boolean yield) {
    setVar("yield",yield);
  }
  
}