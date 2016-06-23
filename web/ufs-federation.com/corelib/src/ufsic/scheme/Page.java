package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.utils.Utils;

public class Page extends SchemeRecord {

  private Value cssCacheId = new Value(null);
  private Value jsCacheId = new Value(null);
  
  final private PromoBanners promoBanners;
  final private PromoMenus promoMenus;
  final private PageMenus pageMenus;
  final private PageTables pageTables;
  final private PageForms pageForms;
  
  final private String htmlTemplateName = Utils.md5("HtmlTemplate").toUpperCase();
  
  public Page(SchemeTable table, Record record) {
    
    super(table, record);
    
    this.promoBanners = new PromoBanners(getScheme());
    this.promoMenus = new PromoMenus(getScheme());
    this.pageMenus = new PageMenus(getScheme());
    this.pageTables = new PageTables(getScheme());
    this.pageForms = new PageForms(getScheme());
  }
  
  public Value getPageId() {
    
    return getValue(Pages.PageId);
  }
  
  public Value getTemplateId() {
    
    return getValue(Pages.TemplateId);
  }

  public Value getTitle() {
    
    return getValue(Pages.Title);
  }
  
  public Value getDescription() {
    
    return getValue(Pages.Description);
  }

  public Value getTags() {
    
    return getValue(Pages.Tags);
  }

  public Value getHtml() {
    
    return getValue(Pages.Html);
  }

  public Value getPath() {
    
    return getValue(Pages.Path);
  }
  
  public Value getTemplateType() {
    
    return getValue(Pages.TemplateType);
  }

  public Value getTemplateCss() {
    
    return getValue(Pages.TemplateCss);
  }

  public Value getTemplateJs() {
    
    return getValue(Pages.TemplateJs);
  }

  public Value getTemplateHtml() {
    
    return getValue(Pages.TemplateHtml);
  }

  public String getHtmlTemplate() {
  
    return htmlTemplateName;
  }
  
  public String getTITLE() {
    
    return getScheme().getDictionary().replace(getTitle().asString());
  }

  public String getDESCRIPTION() {
    
    return getScheme().getDictionary().replace(getDescription().asString());
  }
  
  public String getHTML() {
    
    return getHtml().asString();
  }
  
  public Value getCssCacheId() {

    return cssCacheId;
  }

  public Value getJsCacheId() {

    return jsCacheId;
  }
  
  public String getCssPath() {
    
    if (cssCacheId.isNull()) {
      cssCacheId = getProvider().getUniqueId();
    }
    return getScheme().getCssPath().buildParamURI("id",cssCacheId.asString(),false);
  }

  public String getJsPath() {
    
    if (jsCacheId.isNull()) {
      jsCacheId = getProvider().getUniqueId();
    }
    return getScheme().getJsPath().buildParamURI("id",jsCacheId.asString(),false);
  }
  
  public String getCharset() {
    
    return Utils.getCharset().name();
  }

  public PromoBanners getPromoBanners(boolean refresh) {
    
    if (refresh) {
      
      Path path = getScheme().getPath();
      if (isNotNull(path)) {
      
        GroupFilter gf = new GroupFilter(new Filter(PromoBanners.LangId,getScheme().getLang().getLangId()).Or(PromoBanners.LangId).IsNull());
        GroupFilter gf2 = new GroupFilter().Or(new Filter(PromoBanners.PageId,getPageId()));
        
        ParentPaths parents = path.getParentPaths();
        if (isNotNull(parents) && !parents.isEmpty()) {
          Filter f = new Filter();
          for (Record r: parents) {
            f.Or(PromoBanners.PageId,((ParentPath)r).getPathId());
          }
          gf2.Or(new GroupFilter(f).And(new Filter().Add(PromoBanners.OnlyCurrent).IsNull()));
        }
        gf.And(gf2);
        
        promoBanners.open(null,gf,new Orders(PromoBanners.Priority));
      }
    }
    return promoBanners;
  }
  
  public PromoBanners getPromoBanners() {
    
    return getPromoBanners(promoBanners.isEmpty());
  }
  
  public PromoMenus getPromoMenus(boolean refresh) {
    
    if (refresh) {
      
      Path path = getScheme().getPath();
      if (isNotNull(path)) {
      
        GroupFilter gf = new GroupFilter(new Filter(PromoMenus.LangId,getScheme().getLang().getLangId()).Or(PromoMenus.LangId).IsNull());
        GroupFilter gf2 = new GroupFilter().Or(new Filter(PromoMenus.PageId,getPageId()));
        
        ParentPaths parents = path.getParentPaths();
        if (isNotNull(parents) && !parents.isEmpty()) {
          Filter f = new Filter();
          for (Record r: parents) {
            f.Or(PromoMenus.PageId,((ParentPath)r).getPathId());
          }
          gf2.Or(new GroupFilter(f).And(new Filter().Add(PromoMenus.OnlyCurrent).IsNull()));
        }
        gf.And(gf2);

        promoMenus.open(null,gf,new Orders(PromoMenus.Priority));
      }
    }
    return promoMenus;
  }
  
  public PromoMenus getPromoMenus() {
    
    return getPromoMenus(promoMenus.isEmpty());
  }
  
  public PageMenus getPageMenus(boolean refresh) {
  
    if (refresh) {
      
      Path path = getScheme().getPath();
      if (isNotNull(path)) {
      
        GroupFilter gf = new GroupFilter(new Filter(PageMenus.LangId,getScheme().getLang().getLangId()).Or(PageMenus.LangId).IsNull());

        ParentPaths parents = path.getParentPaths();
        if (isNotNull(parents) && !parents.isEmpty()) {
          GroupFilter gf2 = new GroupFilter().Or(new Filter(PageMenus.PageId,getPageId()));
          Filter f = new Filter();
          for (Record r: parents) {
            f.Or(PageMenus.PageId,((ParentPath)r).getPathId());
          }
          gf2.Or(new GroupFilter(f).And(new Filter().Add(PageMenus.OnlyCurrent).IsNull()));
          gf.And(gf2);
        }

        Filter f2 = new Filter();
        Account account = getScheme().getAccount();
        if (isNull(account)) {
          f2.Add(PageMenus.AccountId).IsNull();
        } else {
          f2.Add(PageMenus.AccountId).Equal(account.getAccountId());  
          for (Record r: account.getRoles()) {
            f2.Or(PageMenus.AccountId).Equal(((AccountRole)r).getRoleId());
          }
        }
        gf.And(f2);

        pageMenus.open(gf,new Orders(PageMenus.Level,PageMenus.Priority));
      }
    }
    return pageMenus;
  }
  
  public PageMenus getPageMenus() {
    
    return getPageMenus(pageMenus.isEmpty());  
  }
  
  public PageTables getPageTables(boolean refresh) {
  
    if (refresh) {
      
      GroupFilter gf = new GroupFilter(new Filter(PageTables.LangId,getScheme().getLang().getLangId()).Or(PageTables.LangId).IsNull());
      gf.And(new Filter(PageTables.PageId,getPageId()));
      pageTables.open(gf,new Orders(PageTables.Priority));
    }
    return pageTables;
  }
  
  public PageTables getPageTables() {
  
    return getPageTables(pageTables.isEmpty());
  }

  public PageForms getPageForms(boolean refresh) {
  
    if (refresh) {
      
      GroupFilter gf = new GroupFilter(new Filter(PageForms.LangId,getScheme().getLang().getLangId()).Or(PageForms.LangId).IsNull());
      gf.And(new Filter(PageForms.PageId,getPageId()));
      pageForms.open(gf,new Orders(PageForms.Priority));
    }
    return pageForms;
  }
  
  public PageForms getPageForms() {
  
    return getPageForms(pageForms.isEmpty());
  }
  
}