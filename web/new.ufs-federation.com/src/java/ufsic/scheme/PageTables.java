package ufsic.scheme;

public class PageTables extends SchemeTable {

  public final static String TableName = "PAGE_TABLES";
  
  public final static String PageTableId = "PAGE_TABLE_ID";
  protected final static String PageId = "PAGE_ID";
  protected final static String LangId = "LANG_ID";
  protected final static String TemplateId = "TEMPLATE_ID";
  protected final static String Name = "NAME";
  protected final static String Description = "DESCRIPTION";
  protected final static String Query = "QUERY";
  protected final static String Columns = "COLUMNS";
  protected final static String Alignments = "ALIGNMENTS";
  protected final static String Formats = "FORMATS";
  protected final static String MaxCount = "MAX_COUNT";
  protected final static String AutoLoad = "AUTO_LOAD";
  protected final static String Async = "ASYNC";
  protected final static String Priority = "PRIORITY";
  protected final static String Sums = "SUMS";

  protected final static String Path = "PATH";
  
  public PageTables(Scheme scheme, String name) {
    super(scheme, name);
  }

  public PageTables(Scheme scheme) {
    super(scheme);
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {

    return PageTable.class;
  }
  
}
