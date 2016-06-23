package ufsic.scheme;

public class PageTableForms extends SchemeTable<PageTableForm> {
  
  public final static String TableName = "PAGE_TABLE_FORMS";
  
  protected final static String PageTableId = "PAGE_TABLE_ID";
  protected final static String PageFormId = "PAGE_FORM_ID";
  protected final static String Filters = "FILTERS";
  protected final static String Params = "PARAMS";
  protected final static String Priority = "PRIORITY";
  
  public PageTableForms(Scheme scheme, String name) {
    super(scheme, name);
  }

  public PageTableForms(Scheme scheme) {
    super(scheme);
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {

    return PageTableForm.class;
  }
  
}
