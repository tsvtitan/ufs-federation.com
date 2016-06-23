package ufsic.scheme.handlers;

import java.util.Map;
import ufsic.contexts.IGlobalVarContext;

import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Cache;
import ufsic.scheme.Comm;
import ufsic.scheme.Page;
import ufsic.scheme.Pages;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Template;
import ufsic.utils.Utils;

public class PageHandler extends TemplateHandler {

  private Page page = null;
  
  public PageHandler(Path path) {
    super(path);
  }

  @Override
  public void process(IGlobalVarContext context) {
    
    super.process(context);
    
    context.setGlobalVar("page",page);
  }
  
  @Override
  public String getDefaultTemplateName() {

    String ret;
    Scheme scheme = getScheme();
    
    Value templateId = page.getTemplateId();
    if (templateId.isNotNull()) {

      Value templateType = page.getTemplateType();
      if (templateType.isNull()) {
        
        Template t = scheme.getTemplates().newTemplate(null);
        t.setTemplateId(templateId);
        t.setCss(page.getTemplateCss());
        t.setJs(page.getTemplateJs());
        t.setHtml(page.getTemplateHtml());

        t = scheme.getTemplates().newTemplate(null);
        t.setTemplateId(page.getSelfTemplateId());
        t.setHtml(page.getHtml());

        ret = templateId.asString();
      } else {

        String tempId = Utils.getUniqueId();
        
        Template t = scheme.getTemplates().newTemplate(null);
        t.setTemplateId(tempId);
        t.setHtml(String.format("<div th:replace=\"%s\"/>",templateId.asString()));

        t = scheme.getTemplates().newTemplate(null,templateType);
        t.setTemplateId(templateId);
        t.setTemplateType(templateType);
        t.setCss(page.getTemplateCss());
        t.setJs(page.getTemplateJs());
        t.setHtml(page.getTemplateHtml());

        t = scheme.getTemplates().newTemplate(null);
        t.setTemplateId(page.getSelfTemplateId());
        t.setHtml(page.getHtml());
        
        ret = tempId; 
      }
    } else {

      Template t = scheme.getTemplates().newTemplate(null);
      t.setTemplateId(page.getSelfTemplateId());
      t.setHtml(page.getHtml());
      
      ret = page.getSelfTemplateId();
    }
    return ret;
  }
  
  private Object getCssData() {
    
    Scheme scheme = getScheme();
    
    StringBuilder sb = new StringBuilder();
    for (Record r: scheme.getTemplates()) {
      Template t = (Template)r;
      String css = t.getCSS();
      if (!css.equals("")) {
        String s = String.format("/*%s*/",t.getTemplateId().asString());
        sb.append(Utils.getLineSeparator()).append(s).append(Utils.getLineSeparator()).append(css.trim());
      }
    }
    Object ret = null;
    if (sb.length()>0) {
      sb.insert(0,String.format("@charset \"%s\"",Utils.getCharset().name()));
      ret = scheme.getDictionary().replace(sb.toString()).getBytes(Utils.getCharset());
    }
    return ret;  
  }
  
  private Object getCssHeaders(int dataLength) {

    StringBuilder sb = new StringBuilder();
    sb.append(String.format("Content-Type: text/css;charset=%s",Utils.getCharset().name()));
    sb.append(Utils.getLineSeparator());
    sb.append(String.format("Content-Length: %d",dataLength));
    return sb.toString();
  } 
  
  private boolean saveCssToCache(Comm comm) {
    
    boolean ret = true;
    
    Scheme scheme = getScheme();
    
    Value cssCacheId = page.getCssCacheId();
    if (cssCacheId.isNotNull()) {
      
      Object data = getCssData();
      int dataLength = 0;
      if (isNotNull(data)) {
        dataLength = (int)((byte[])data).length;
      }
      Cache c = new Cache(scheme.getCaches(),null);
      c.setCacheId(cssCacheId);
      c.setPathId(null);
      c.setSessionId(scheme.getSessionId());
      c.setCommId((isNull(comm))?null:comm.getCommId());
      c.setExpired(null);  
      c.setData(data);
      c.setHeaders(getCssHeaders(dataLength));
      c.setLangId(scheme.getLang().getLangId());
      ret = scheme.getCaches().insert(c);
    }
    return ret;
  }

  private Object getJsData() {
    
    Scheme scheme = getScheme();
    StringBuilder sb = new StringBuilder();
    boolean first = true;
    for (Record r: scheme.getTemplates()) {
      Template t = (Template)r;
      String js = t.getJS();
      if (!js.equals("")) {
        String s = String.format("/*%s*/",t.getTemplateId().asString());
        sb.append((first)?"":Utils.getLineSeparator()).append(s).append(Utils.getLineSeparator()).append(js.trim());
        first = false;
      }
    }
    Object ret = null;
    if (sb.length()>0) {
      ret = scheme.getDictionary().replace(sb.toString()).getBytes(Utils.getCharset());
    }
    return ret;  
  }

  private Object getJsHeaders(int dataLength) {

    StringBuilder sb = new StringBuilder();
    sb.append(String.format("Content-Type: text/javascript;charset=%s",Utils.getCharset().name()));
    sb.append(Utils.getLineSeparator());
    sb.append(String.format("Content-Length: %d",dataLength));
    return sb.toString();
  }
  
  private boolean saveJsToCache(Comm comm) {
    
    boolean ret = true;
    Scheme scheme = getScheme();
    
    Value jsCacheId = page.getJsCacheId();
    if (jsCacheId.isNotNull()) {
      
      Object data = getJsData();
      int dataLength = 0;
      if (isNotNull(data)) {
        dataLength = (int)((byte[])data).length;
      }
      Cache c = new Cache(scheme.getCaches(),null);
      c.setCacheId(jsCacheId);
      c.setPathId(null);
      c.setSessionId(scheme.getSessionId());
      c.setCommId((isNull(comm))?null:comm.getCommId());
      c.setExpired(null);  
      c.setData(data);
      c.setHeaders(getJsHeaders(dataLength));
      c.setLangId(scheme.getLang().getLangId());
      ret = scheme.getCaches().insert(c);
    }
    return ret;
  }

  protected boolean needSetLastPageId() {
    return true;
  }
  
  private boolean trySend(Comm comm) {
    
    boolean ret = false;
    String html = getHtml();
    if (!html.equals("")) {
      ret = saveCssToCache(comm) && saveJsToCache(comm);
      if (ret) {
        ret = getEcho().write(html);
        if (ret && needSetLastPageId()) {
          getPath().setLangLastPageId(getScheme().getLang().getLangId().asString(),getPath().getPathId().asString());
        }
      }
    }
    return ret;
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = false;
    Scheme scheme = getScheme();
    Record r = getProvider().first(scheme.getPages().getViewName(),new Filter(Pages.PageId,getPath().getPathId()));
    if (isNotNull(r)) {
      page = new Page(scheme.getPages(),r);
      scheme.setPage(page);
      ret = trySend(comm);
      if (ret) {
        setHeaders();
      }
    }
    return ret;
  }

}