package ufsic.scheme.handlers;

import java.sql.Timestamp;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Locale;
import java.util.Map;
import java.util.Set;

import org.thymeleaf.TemplateEngine;
import org.thymeleaf.context.WebContext;
import org.thymeleaf.processor.IProcessor;
import ufsic.contexts.IGlobalVarContext;
import ufsic.contexts.IVarContext;

import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Template;
import ufsic.scheme.thymeleaf.SchemeMessageResolver;
import ufsic.scheme.thymeleaf.SchemeVariablesProcessor;
import ufsic.scheme.thymeleaf.SchemeStandardDialect;
import ufsic.utils.Utils;
import ufsic.scheme.thymeleaf.SchemeTemplateResourceResolver;
import ufsic.scheme.thymeleaf.SchemeTemplateResolver;
import ufsic.scheme.thymeleaf.SchemeWebContext;

public class TemplateHandler extends Handler implements IGlobalVarContext {

  private final Map<String,Object> globals = new HashMap<>();
  
  public TemplateHandler(Path path) {
    super(path);
  }

  @Override
  public Map<String, Object> getGlobalVars() {
    return globals;
  }

  @Override
  public void setGlobalVar(String name, Object value) {
    globals.put(name,value);
  }

  @Override
  public Object getGlobalVar(String name) {
    return globals.get(name);
  }

  @Override
  public boolean globalExists(String name) {
    return globals.containsKey(name);
  }


  public class Stamp {
    
    private Timestamp stamp = null;
    
    private Calendar getCalendar(boolean isNew) {
      
      Calendar ret = Calendar.getInstance();
      if (isNull(stamp) || isNew) {
        Value ts = getProvider().getNow();
        if (ts.isNotNull()) {
          stamp = ts.asTimestamp();
        }
      }
      if (isNotNull(stamp)) {
        ret.setTimeInMillis(stamp.getTime());
      }
      return ret;
    }
    
    public Integer getYear() {
      
      return getCalendar(false).get(Calendar.YEAR);
    }
    
    public String format(String format) {
      
      String lng = getScheme().getLang().getLangId().asString();
      SimpleDateFormat sdf = new SimpleDateFormat(format,new Locale(lng.toLowerCase(),lng.toUpperCase()));
      return sdf.format(getCalendar(false).getTime());
    }
    
  }

  protected void process(Map<String,Object> vars) {
    //
  }
  
  public void process(IGlobalVarContext context) {
    
    Scheme scheme = getScheme();
    
    context.setGlobalVar("timestamp",new Stamp());
    context.setGlobalVar("scheme",scheme);
    context.setGlobalVar("account",scheme.getAccount());
    context.setGlobalVar("client",scheme.getClient());
    context.setGlobalVar("lang",scheme.getLang());
    context.setGlobalVar("path",getPath());
    context.setGlobalVar("utils",new Utils());
    
    process(context.getGlobalVars());
  }
  
  private WebContext getWebContext() {
  
    WebContext ret = null;
    try {
      Path path = getPath();
      ret = new SchemeWebContext(path.getRequest(),path.getResponse(),path.getRequest().getServletContext());
      if (isNotNull(ret)) {
        process(this);
        ret.setVariables(globals);
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  public String getDefaultTemplateName() {
    
    String ret = "";
    String rest = getPath().getRestPath().replace("/","");
    if (isNotNull(rest)) {

      ret = rest;
      if (!ret.equals("")) {
        String tempId = Utils.getUniqueId();
        
        Template t = getScheme().getTemplates().newTemplate(null);
        t.setTemplateId(tempId);
        t.setHtml(String.format("<div th:replace=\"%s\"/>",ret));
        
        ret = tempId;
      }
    }
    return ret;
  }
  
  public String getHtml() {
    
    String ret = "";
    
    WebContext ctx = getWebContext();
    if (isNotNull(ctx)) {

      Scheme scheme = getScheme();
      try {
        SchemeTemplateResolver resolver = new SchemeTemplateResolver(scheme);      
        if (isNotNull(resolver)) {

          SchemeStandardDialect dialect = new SchemeStandardDialect(scheme);
          
          final Set<IProcessor> processors = new HashSet<>();
          SchemeVariablesProcessor processor = new SchemeVariablesProcessor(scheme);
          processors.add(processor);
          dialect.setAdditionalProcessors(processors);
          
          resolver.setResourceResolver(new SchemeTemplateResourceResolver(scheme,processor,ctx));
          
          resolver.setCacheable(true);
          resolver.setCacheTTLMs(60000L);
          resolver.setTemplateMode("HTML5");
          resolver.setCharacterEncoding(Utils.getCharset().name());
          
          TemplateEngine engine = new TemplateEngine();
          if (isNotNull(engine)) {

            engine.setDialect(dialect);
            engine.setTemplateResolver(resolver); 
            engine.setMessageResolver(new SchemeMessageResolver(scheme));
            
            ret = engine.process(getDefaultTemplateName(),ctx);
            if (!ret.trim().equals("")) {
              ret = ret.trim();
            }
          }
        }
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
    
  public void setHeaders() {
    
    getPath().setContentHeaders("text/html",getEcho().getBufStream().size());
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = false;
    String html = getHtml();
    if (!html.equals("")) {
      ret = getEcho().write(html);
      if (ret) {
        setHeaders();
      }
    }
    return ret;
  }
  
  
}
