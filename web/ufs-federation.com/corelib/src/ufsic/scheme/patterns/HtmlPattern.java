package ufsic.scheme.patterns;

import java.sql.Timestamp;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashSet;
import java.util.Locale;

import java.util.Set;
import org.thymeleaf.TemplateEngine;
import org.thymeleaf.context.Context;
import org.thymeleaf.processor.IProcessor;

import ufsic.providers.Value;
import ufsic.scheme.Pattern;
import ufsic.scheme.Scheme;
import ufsic.scheme.thymeleaf.SchemeContext;
import ufsic.scheme.thymeleaf.SchemeMessageResolver;
import ufsic.scheme.thymeleaf.SchemePatternResourceResolver;
import ufsic.scheme.thymeleaf.SchemeStandardDialect;
import ufsic.scheme.thymeleaf.SchemeTemplateResolver;
import ufsic.scheme.thymeleaf.SchemeVariablesProcessor;
import ufsic.utils.Utils;

public class HtmlPattern extends Pattern {

  public HtmlPattern(Scheme scheme, boolean autoLoad) {
    super(scheme.getPatterns(),autoLoad);
  }
  
  public HtmlPattern(Scheme scheme) {
    this(scheme,true);
  }
  
  @Override
  public String getContentType() {
    return String.format("text/html; charset=%s",Utils.getCharset().name());
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
  
  protected Context getContext() {
  
    Context ret = null;
    try {
      ret = new SchemeContext();
      if (isNotNull(ret)) {
        
        Scheme scheme = getScheme();
        
        ret.setVariable("timestamp",new Stamp());
        ret.setVariable("scheme",scheme);
        ret.setVariable("account",scheme.getAccount());
        ret.setVariable("client",scheme.getClient());
        ret.setVariable("lang",scheme.getLang());
        ret.setVariable("path",scheme.getPath());
        ret.setVariable("utils",new Utils());
        ret.setVariable("uri",scheme.getURI());

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
          //resolver.setTemplateMode("LEGACYHTML5");
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
