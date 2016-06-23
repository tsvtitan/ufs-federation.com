package ufsic.scheme.thymeleaf;

import java.util.Map;
import org.thymeleaf.context.IContext;
import org.thymeleaf.context.ProcessingContext;

public class SchemeProcessingContext extends ProcessingContext {

  public SchemeProcessingContext(IContext context) {
    super(context);
  }

  public SchemeProcessingContext(IContext context, Map<String, Object> localVariables) {
    super(context, localVariables);
  }
  
}
