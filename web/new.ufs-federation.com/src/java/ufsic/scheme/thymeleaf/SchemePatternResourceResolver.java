package ufsic.scheme.thymeleaf;

import java.io.ByteArrayInputStream;
import java.io.InputStream;
import java.util.HashMap;
import java.util.Map;

import org.thymeleaf.TemplateProcessingParameters;
import org.thymeleaf.context.Context;
import org.thymeleaf.context.IProcessingContext;
import org.thymeleaf.context.VariablesMap;
import org.thymeleaf.resourceresolver.IResourceResolver;
import ufsic.contexts.IVarContext;

import ufsic.providers.FieldNames;
import ufsic.providers.Filter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Pattern;
import ufsic.scheme.Patterns;
import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeObject;
import ufsic.utils.Utils;

public class SchemePatternResourceResolver extends SchemeObject implements IResourceResolver {

  private final Scheme scheme;
  private final Context context;
  private final Provider provider;
  private final SchemeVariablesProcessor processor;
  
  public SchemePatternResourceResolver(Scheme scheme, SchemeVariablesProcessor processor, Context context) {
   
    super(scheme);
    
    this.scheme = scheme;
    this.provider = scheme.getProvider();
    this.processor = processor;
    this.context = context;
    
  }
  
  @Override
  public String getName() {
    return toString();
  }

  class PatternContext implements IVarContext, IVariableExtractor {

    Map<String,Object> globals = null;
    Map<String,Object> locals = new HashMap<>();
    IProcessingContext processContext = null;
            
    PatternContext(String resourceName, TemplateProcessingParameters templateProcessingParameters) {
      
      this.processContext = templateProcessingParameters.getProcessingContext();
              
      Object expressions;
      expressions = processContext.getExpressionEvaluationRoot();
      if (isNotNull(expressions)) {
        if (Utils.isClass(expressions,VariablesMap.class)) {
          this.globals = (VariablesMap)expressions;
        }
      }
      
      processor.setExtractor(resourceName,this);
    }

    @Override
    public Map<String,Object> getGlobalVars() {
      return globals;
    }
    
    @Override
    public void setGlobalVar(String name, Object value) {
      
      if (isNotNull(globals)) {
        globals.put(name,value);
      }
      context.setVariable(name,value);
    }

    @Override
    public void setLocalVar(String name, Object value) {
      
      locals.put(name,value);
    }
    
    @Override
    public Object getLocalVar(String name) {
      
      return locals.get(name);
    }
    
    @Override
    public Object getGlobalVar(String name) {
      
      Object ret = null;
      if (isNotNull(globals)) {
        ret = globals.get(name);
      }
      return ret;
    }
    
    @Override
    public boolean globalExists(String name) {
      
      boolean ret = false;
      if (isNotNull(globals)) {
        ret = globals.containsKey(name);
      }
      return ret;
    }

    @Override
    public boolean localExists(String name) {
      
      return locals.containsKey(name);
    }
    
    @Override
    public Map<String,Object> getVariables() {
      return locals;
    }

  }
  
  @Override
  public InputStream getResourceAsStream(TemplateProcessingParameters templateProcessingParameters, String resourceName) {
    
    InputStream ret;
    Record r = scheme.getPatterns().findFirst(Patterns.PatternId,resourceName);
    if (isNotNull(r)) {
      Value locked = r.getValue(Patterns.Locked);
      if (locked.isNull()) {
        Pattern p = (Pattern)r;
        p.process(new PatternContext(resourceName,templateProcessingParameters));
        ret = p.getBodyStream();
      } else {
        ret = new ByteArrayInputStream("".getBytes());
      }
    } else {
      FieldNames fn = new FieldNames(Patterns.PatternId,Patterns.PatternType,Patterns.Body);
      r = provider.first(scheme.getPatterns().getViewName(),fn,new Filter(Patterns.PatternId,resourceName));
      if (isNotNull(r)) {
        Value locked = r.getValue(Patterns.Locked);
        if (locked.isNull()) {
          Pattern p = scheme.getPatterns().newPattern(r,r.getValue(Patterns.PatternType));
          p.process(new PatternContext(resourceName,templateProcessingParameters));
          ret = p.getBodyStream();
        } else {
          ret = new ByteArrayInputStream("".getBytes());
        }
      } else {
        ret = new ByteArrayInputStream(String.format("<!-- Pattern %s is not found -->",resourceName).getBytes());
      }
    }
    return ret;
  }
  
}
