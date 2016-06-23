package ufsic.scheme;

import ufsic.contexts.IVarContext;
import com.yahoo.platform.yui.compressor.CssCompressor;
import com.yahoo.platform.yui.compressor.JavaScriptCompressor;
import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.InputStream;
import java.io.PrintStream;
import java.io.PrintWriter;
import java.io.StringReader;
import java.io.StringWriter;
import java.util.Map;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.utils.Location;
import ufsic.utils.Utils;


public class Template extends SchemeRecord {

  private InputStream stream = null;
  
  public Template(SchemeTable table, Record record) {
    super(table, record);
  }

  
  public Value getTemplateId() {
    
    return getValue(Templates.TemplateId);
  }
  
  public void setTemplateId(Value templateId) {
    
    if (!setValue(Templates.TemplateId,templateId)) {
      add(Templates.TemplateId,templateId);
    }
  }
  
  public void setTemplateId(String templateId) {
    
    if (!setValue(Templates.TemplateId,templateId)) {
      add(Templates.TemplateId,templateId);
    }
  }

  public Value getName() {
    
    return getValue(Templates.Name);
  }

  public void setName(Value name) {
    
    if (!setValue(Templates.Name,name)) {
      add(Templates.Name,name);
    }
  }
  
  public Value getDescription() {
    
    return getValue(Templates.Description);
  }
  
  public Value getTemplateType() {
    
    return getValue(Templates.TemplateType);
  }
  
  public void setTemplateType(Value templateType) {
    
    if (!setValue(Templates.TemplateType,templateType)) {
      add(Templates.TemplateType,templateType);
    }
  }

  public Value getCss() {
    
    return getValue(Templates.Css);
  }

  public void setCss(Value css) {
    
    if (!setValue(Templates.Css,css)) {
      add(Templates.Css,css);
    }
  }
  
  public Value getJs() {
    
    return getValue(Templates.Js);
  }

  public void setJs(Value js) {
    
    if (!setValue(Templates.Js,js)) {
      add(Templates.Js,js);
    }
  }
  
  public Value getHtml() {
    
    return getValue(Templates.Html);
  }

  public void setHtml(Value html) {
    
    if (!setValue(Templates.Html,html)) {
      add(Templates.Html,html);
    }
  }

  public void setHtml(Object html) {

    setHtml(new Value(html));
  }

  public Value getLocked() {
    
    return getValue(Templates.Locked);
  }
  
  private String getRootPath(String name) {
    
    String ret = "";
    switch (name) {
      case Templates.Html: {
        ret = getScheme().getTemplateDir();
        break;
      }
      case Templates.Css: {
        ret = getScheme().getCssDir();
        break;
      }
      case Templates.Js: {
        ret = getScheme().getJsDir();
        break;
      }
    }
    return ret;
  }
  
  private byte[] getValueByLocation(String name) {
    
    byte[] ret = new byte[] {};
    try {
      byte[] bytes = getValue(name).asBytes();
      if (bytes.length>0) {
        Location loc = new Location(new String(bytes),getRootPath(name));
        if (isNotNull(loc)) {
          if (loc.exists()) {
            ret = loc.getBytes();
          } else {
            ret = bytes;
          }
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  public InputStream getStream(String name) {
  
    ByteArrayInputStream ret;
    if (isNotNull(stream)) {
      ret = (ByteArrayInputStream)stream;
    } else {
      ret = new ByteArrayInputStream(getValueByLocation(name));
      stream = ret;
    }
    return ret;
  }
  
  public String getActualCss() {
    
    String ret = "";
    byte[] bytes = getValueByLocation(Templates.Css);
    if (bytes.length>0) {
      ret = new String(bytes);
    }
    return ret;
  }
  
  public String getCompressedCss() {

    String ret = getActualCss();
   /* if (!ret.equals("")) {
      try {
        CssCompressor cmp = new CssCompressor(new StringReader(ret));
        StringWriter writer = new StringWriter();
        cmp.compress(writer,-1);
        ret = writer.toString(); 
      } catch (Exception e) {
        ret = Utils.getExceptionStack(e);
      }
    }*/
    return ret;
  }
  
  public String getCSS() {
    
    String ret = new String(getCompressedCss().getBytes(),Utils.getCharset());
    return getScheme().getDictionary().replace(ret);
  }

  public String getActualJs() {
  
    String ret = "";
    byte[] bytes = getValueByLocation(Templates.Js);
    if (bytes.length>0) {
      ret = new String(bytes);
    }
    return ret;
  }
  
  public String getCompressedJs() {
    
    String ret = getActualJs();
   /* if (!ret.equals("")) {
      try {
        JavaScriptCompressor cmp = new JavaScriptCompressor(new StringReader(ret),null);
        StringWriter writer = new StringWriter();
        cmp.compress(writer,-1,false,false,false,false);
        ret = writer.toString(); 
      } catch (Exception e) {
        ret = Utils.getExceptionStack(e);
      }
    }*/
    return ret;
  }
  
  public String getJS() {
  
    String ret = new String(getCompressedJs().getBytes(),Utils.getCharset());
    return getScheme().getDictionary().replace(ret);
  }

  public void process(IVarContext context) {
    
    context.setGlobalVar("template",this);
    process(context.getGlobalVars());
  }
  
  protected void process(Map<String,Object> vars) {
    //  
  }
  
}
