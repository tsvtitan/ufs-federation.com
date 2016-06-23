package ufsic.scheme.patterns;

import java.sql.Timestamp;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Locale;
import java.util.Map;
import java.util.Set;
import org.thymeleaf.TemplateEngine;
import org.thymeleaf.context.Context;
import org.thymeleaf.processor.IProcessor;
import ufsic.contexts.IGlobalVarContext;
import ufsic.providers.Value;
import ufsic.scheme.Path;
import ufsic.scheme.Pattern;
import ufsic.scheme.Scheme;
import ufsic.scheme.thymeleaf.SchemeContext;
import ufsic.scheme.thymeleaf.SchemeMessageResolver;
import ufsic.scheme.thymeleaf.SchemePatternResourceResolver;
import ufsic.scheme.thymeleaf.SchemeStandardDialect;
import ufsic.scheme.thymeleaf.SchemeTemplateResolver;
import ufsic.scheme.thymeleaf.SchemeVariablesProcessor;
import ufsic.utils.Utils;

public class HtmlPattern extends Pattern implements IGlobalVarContext {

  public HtmlPattern(Scheme scheme, boolean autoLoad) {
    super(scheme.getPatterns(),autoLoad);
  }
  
  public HtmlPattern(Scheme scheme) {
    this(scheme,true);
  }
  
  @Override
  public String getContentType() {
    return "text/html";
  }

  @Override
  public Map<String, Object> getGlobalVars() {
    return super.getVars();
  }

  @Override
  public void setGlobalVar(String name, Object value) {
    super.getVars().put(name,value);
  }

  @Override
  public Object getGlobalVar(String name) {
    return super.getVars().get(name);
  }

  @Override
  public boolean globalExists(String name) {
    return super.getVars().containsKey(name);
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
  
  @Override
  public void process(IGlobalVarContext context) {
    
    super.process(context);
    
    Scheme scheme = getScheme();
    
    context.setGlobalVar("timestamp",new Stamp());
    context.setGlobalVar("scheme",scheme);
    context.setGlobalVar("account",scheme.getAccount());
    context.setGlobalVar("client",scheme.getClient());
    context.setGlobalVar("lang",scheme.getLang());
    context.setGlobalVar("path",scheme.getPath());
    context.setGlobalVar("utils",new Utils());
  }
  
  private Context getContext() {
  
    Context ret = null;
    try {
      Path path = getScheme().getPath();
      ret = new SchemeContext();
      if (isNotNull(ret)) {
        process(this);
        ret.setVariables(getVars());
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  @Override
  public String parseBody() {
    
    String ret = null;
    Context ctx = getContext();
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
          
          resolver.setResourceResolver(new SchemePatternResourceResolver(scheme,processor,ctx));
          resolver.setCacheable(true);
          resolver.setCacheTTLMs(60000L);
          resolver.setTemplateMode("HTML5");
          resolver.setCharacterEncoding(Utils.getCharset().name());

          TemplateEngine engine = new TemplateEngine();
          if (isNotNull(engine)) {

            engine.setDialect(dialect);
            engine.setTemplateResolver(resolver); 
            engine.setMessageResolver(new SchemeMessageResolver(scheme));

            String html = engine.process(getPatternId().asString(),ctx);
            if (!html.trim().equals("")) {
              ret = html.trim();
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
