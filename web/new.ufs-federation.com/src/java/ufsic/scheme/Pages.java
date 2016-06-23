package ufsic.scheme;

public class Pages extends SchemeTable {
  
  public final static String TableName = "PAGES";
  
  public final static String PageId = "PAGE_ID";
  public final static String TemplateId = "TEMPLATE_ID";
  public final static String Title = "TITLE";
  public final static String Description = "DESCRIPTION";
  public final static String Tags = "TAGS";
  public final static String Html = "HTML";
  public final static String Path = "PATH";
  
  public final static String TemplateType = "TEMPLATE_TYPE";
  public final static String TemplateCss = "TEMPLATE_CSS";
  public final static String TemplateJs = "TEMPLATE_JS";
  public final static String TemplateHtml = "TEMPLATE_HTML";

  public Pages(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Pages(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    
    return Page.class;
  }
  
}
