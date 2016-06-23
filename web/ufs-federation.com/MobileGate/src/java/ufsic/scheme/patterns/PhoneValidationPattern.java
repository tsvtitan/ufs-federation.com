package ufsic.scheme.patterns;

import ufsic.scheme.Scheme;

public class PhoneValidationPattern extends PlainPattern {

  public PhoneValidationPattern(Scheme scheme, String code) {
    super(scheme);
    setVar("code",code);
  }

}
