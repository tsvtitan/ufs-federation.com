package ufsic.scheme.thymeleaf;

import java.util.Locale;
import java.util.Map;
import org.thymeleaf.context.Context;

public class SchemeContext extends Context {

  public SchemeContext() {
  }

  public SchemeContext(Locale locale) {
    super(locale);
  }

  public SchemeContext(Locale locale, Map<String, ?> variables) {
    super(locale, variables);
  }
   
}
