package ufsic.scheme.mobile;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;

public class MobileTables extends SchemeTable<MobileTable> {

  public final static String TableName = "MOBILE_TABLES";
  
  public final static String MobileTableId = "MOBILE_TABLE_ID";
  public final static String MobileMenuId = "MOBILE_MENU_ID";
  public final static String Locked = "LOCKED";
  public final static String Name = "NAME";
  public final static String Description = "DESCRIPTION";
  public final static String Columns = "COLUMNS";
  public final static String Alignments = "ALIGNMENTS";
  public final static String Sql = "SQL";
  public final static String Priority = "PRIORITY";
  
  public final static String MenuDescription = "MENU_DESCRIPTION";
  public final static String LangId = "LANG_ID";
  
  public MobileTables(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public MobileTables(Scheme scheme) {
    
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return MobileTable.class;
  }
}
