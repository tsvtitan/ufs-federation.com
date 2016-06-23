package ufsic.scheme.patterns;

import ufsic.scheme.Scheme;

public class EmailValidationPattern extends HtmlPattern {

  public EmailValidationPattern(Scheme scheme, String code) {
    super(scheme);
    setVar("code",code);
  }
 
}
