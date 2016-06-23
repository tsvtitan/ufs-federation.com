package ufsic.scheme.handlers;

import java.sql.Timestamp;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashSet;
import java.util.Locale;

import java.util.Set;
import org.thymeleaf.TemplateEngine;
import org.thymeleaf.context.WebContext;
import org.thymeleaf.processor.IProcessor;

import ufsic.contexts.IVarContext;
import ufsic.providers.Value;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Template;
import ufsic.scheme.thymeleaf.SchemeMessageResolver;
import ufsic.scheme.thymeleaf.SchemeStandardDialect;
import ufsic.scheme.thymeleaf.SchemeTemplateResolver;
import ufsic.scheme.thymeleaf.SchemeTemplateResourceResolver;
import ufsic.scheme.thymeleaf.SchemeVariablesProcessor;
import ufsic.scheme.thymeleaf.SchemeWebContext;
import ufsic.utils.Utils;

public class TemplateHandler extends HtmlHandler {

  public TemplateHandler(Path path) {
    super(path);
  }

  public static boolean isPathRestricted() {
    return false;
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

  public void process(IVarContext context) {
    
    context.setLocalVar("handler",this);
  }
  
  protected WebContext getWebContext() {
  
    WebContext ret = null;
    try {
      Path path = getPath();
      ret = new SchemeWebContext(path.getRequest(),path.getResponse(),path.getRequest().getServletContext());
      if (isNotNull(ret)) {
        
        Scheme scheme = getScheme();
        
        ret.setVariable("timestamp",new Stamp());
        ret.setVariable("scheme",scheme);
        ret.setVariable("account",scheme.getAccount());
        ret.setVariable("client",scheme.getClient());
        ret.setVariable("lang",scheme.getLang());
        ret.setVariable("path",getPath());
        ret.setVariable("utils",new Utils());
        ret.setVariable("uri",scheme.getURI());
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
  
  @Override
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
  
}
