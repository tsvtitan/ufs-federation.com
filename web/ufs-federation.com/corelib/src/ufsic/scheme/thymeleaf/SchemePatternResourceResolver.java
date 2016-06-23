package ufsic.scheme.thymeleaf;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.HashMap;
import java.util.Map;
import org.htmlcleaner.CleanerProperties;
import org.htmlcleaner.HtmlCleaner;
import org.htmlcleaner.PrettyHtmlSerializer;
import org.htmlcleaner.SimpleHtmlSerializer;
import org.htmlcleaner.TagNode;

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
  private final CleanerProperties props = new CleanerProperties();
  private final HtmlCleaner cleaner;
  
  public SchemePatternResourceResolver(Scheme scheme, SchemeVariablesProcessor processor, Context context) {
   
    super(scheme);
    
    this.scheme = scheme;
    this.provider = scheme.getProvider();
    this.processor = processor;
    this.context = context;
    
    props.setAdvancedXmlEscape(false);
    props.setTransResCharsToNCR(false);
    props.setTranslateSpecialEntities(false);
    props.setRecognizeUnicodeChars(false);
    props.setUseCdataForScriptAndStyle(false);
    props.setOmitUnknownTags(false);
    props.setTreatUnknownTagsAsContent(false);
    props.setOmitDeprecatedTags(false);
    props.setTreatDeprecatedTagsAsContent(false);
    props.setOmitComments(false);
    props.setOmitXmlDeclaration(true);
    props.setOmitDoctypeDeclaration(true);
    props.setOmitHtmlEnvelope(true);
    props.setUseEmptyElementTags(true);
    props.setAllowMultiWordAttributes(true);
    props.setAllowHtmlInsideAttributes(true);
    props.setNamespacesAware(true);
    props.setTransSpecialEntitiesToNCR(false);

    
    this.cleaner = new HtmlCleaner(props);
    
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
  
  private InputStream cleanTemplateStream(InputStream in) {
    
    InputStream ret = in;
    try {
      
      TagNode node = cleaner.clean(in);
      if (isNotNull(node)) {
        
        ByteArrayOutputStream out = new ByteArrayOutputStream(); 
        new SimpleHtmlSerializer(props).writeToStream(node,out);
        ret = new ByteArrayInputStream(out.toByteArray());
        
      }
      
    } catch(Exception e) {
      logException(e);
    }
    return ret;
  }
  
  @Override
  public InputStream getResourceAsStream(TemplateProcessingParameters templateProcessingParameters, String resourceName) {
    
    InputStream ret;
    Pattern p = scheme.getPatterns().findFirst(Patterns.PatternId,resourceName);
    if (isNotNull(p)) {
      Value locked = p.getLocked();
      if (locked.isNull()) {
        p.process(new PatternContext(resourceName,templateProcessingParameters));
        ret = cleanTemplateStream(p.getBodyStream());
      } else {
        ret = new ByteArrayInputStream("".getBytes());
      }
    } else {
      FieldNames fn = new FieldNames(Patterns.PatternId,Patterns.PatternType,Patterns.Body);
      Record r = provider.first(scheme.getPatterns().getViewName(),fn,new Filter(Patterns.PatternId,resourceName));
      if (isNotNull(r)) {
        Value locked = r.getValue(Patterns.Locked);
        if (locked.isNull()) {
          p = scheme.getPatterns().newPattern(r,r.getValue(Patterns.PatternType));
          p.process(new PatternContext(resourceName,templateProcessingParameters));
          ret = cleanTemplateStream(p.getBodyStream());
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
