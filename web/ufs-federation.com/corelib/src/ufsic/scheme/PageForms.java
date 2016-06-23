package ufsic.scheme;

public class PageForms extends SchemeTable<PageForm> {
  
  public final static String TableName = "PAGE_FORMS";
  
  public final static String PageFormId = "PAGE_FORM_ID";
  protected final static String PageId = "PAGE_ID";
  protected final static String LangId = "LANG_ID";
  public final static String TemplateId = "TEMPLATE_ID";
  protected final static String Name = "NAME";
  protected final static String Description = "DESCRIPTION";
  public final static String ProcName = "PROC_NAME";
  public final static String Fields = "FIELDS";
  protected final static String Async = "ASYNC";
  protected final static String Priority = "PRIORITY";
  public final static String Defaults = "DEFAULTS";
  public final static String Requirements = "REQUIREMENTS";
  public final static String PlaceHolders = "PLACEHOLDERS";
  public final static String Buttons = "BUTTONS";
  public final static String MaxLengths = "MAX_LENGTHS";
  public final static String Lists = "LISTS";
  public final static String Styles = "STYLES";
  public final static  String Transforms = "TRANSFORMS";
  public final static  String CaptchaType = "CAPTCHA_TYPE";
  protected final static  String ParentId = "PARENT_ID";
  public final static String LastPageFormId = "LAST_PAGE_FORM_ID";

  //protected final static String Path = "PATH";
  
  public PageForms(Scheme scheme, String name) {
    super(scheme, name);
  }

  public PageForms(Scheme scheme) {
    super(scheme);
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {

    return PageForm.class;
  }
  
}
