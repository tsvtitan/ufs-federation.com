package ufsic.scheme.thymeleaf;

import java.io.ByteArrayInputStream;
import java.io.InputStream;
import java.util.HashMap;
import java.util.Map;

import org.thymeleaf.TemplateProcessingParameters;
import org.thymeleaf.context.IProcessingContext;
import org.thymeleaf.context.VariablesMap;
import org.thymeleaf.context.WebContext;
import org.thymeleaf.resourceresolver.IResourceResolver;

import ufsic.providers.FieldNames;
import ufsic.providers.Filter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.scheme.Scheme;
import ufsic.contexts.IVarContext;
import ufsic.providers.Value;
import ufsic.scheme.SchemeObject;
import ufsic.scheme.Templates;
import ufsic.scheme.Template;
import ufsic.utils.Utils;

public class SchemeTemplateResourceResolver extends SchemeObject implements IResourceResolver {

  private final Scheme scheme;
  private final WebContext context;
  private final Provider provider;
  private final SchemeVariablesProcessor processor;
  
  public SchemeTemplateResourceResolver(Scheme scheme, SchemeVariablesProcessor processor, WebContext context) {
   
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
  
  class TemplateContext implements IVarContext, IVariableExtractor {

    Map<String,Object> globals = null;
    Map<String,Object> locals = new HashMap<>();
    Map<String,Object> parentLocals = null;
    IProcessingContext processContext = null;
            
    TemplateContext(String resourceName, TemplateProcessingParameters templateProcessingParameters) {
      
      this.processContext = templateProcessingParameters.getProcessingContext();
              
      Object expressions;
      expressions = processContext.getExpressionEvaluationRoot();
      if (isNotNull(expressions)) {
        if (Utils.isClass(expressions,VariablesMap.class)) {
          this.globals = (VariablesMap)expressions;
        }
      }
      
      this.parentLocals = processContext.getLocalVariables();
      
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
      
      Object ret = null;
      if (isNotNull(parentLocals)) {
        ret = parentLocals.get(name);
      }
      if (isNull(ret)) {
        ret = locals.get(name); 
      }
      return ret;
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
      
      Object obj = getGlobalVar(name);
      return isNotNull(obj);
    }

    @Override
    public boolean localExists(String name) {
      
      Object obj = getLocalVar(name);
      return isNotNull(obj);
    }
    
    @Override
    public Map<String,Object> getVariables() {
      return locals;
    }

  }
  
  @Override
  public InputStream getResourceAsStream(TemplateProcessingParameters templateProcessingParameters, String resourceName) {
    
    InputStream ret;
    Record r = scheme.getTemplates().findFirst(Templates.TemplateId,resourceName);
    if (isNotNull(r)) {
      Value locked = r.getValue(Templates.Locked);
      if (locked.isNull()) {
        Template t = ((Template)r);
        t.process(new TemplateContext(resourceName,templateProcessingParameters));
        ret = t.getStream(Templates.Html);
      } else {
        ret = new ByteArrayInputStream("".getBytes());
      }
    } else {
      FieldNames fn = new FieldNames(Templates.TemplateId,Templates.TemplateType,Templates.Css,Templates.Js,Templates.Html,Templates.Locked);
      r = provider.first(scheme.getTemplates().getViewName(),fn,new Filter(Templates.TemplateId,resourceName));
      if (isNotNull(r)) {
        Value locked = r.getValue(Templates.Locked);
        if (locked.isNull()) {
          Template t = scheme.getTemplates().newTemplate(r,r.getValue(Templates.TemplateType));
          t.process(new TemplateContext(resourceName,templateProcessingParameters));
          ret = t.getStream(Templates.Html);
        } else {
          ret = new ByteArrayInputStream("".getBytes());
        }
      } else {
        ret = new ByteArrayInputStream(String.format("<!-- Template %s is not found -->",resourceName).getBytes());
      }
    }
    return ret;
  }
  
}
