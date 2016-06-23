package ufsic.scheme;

import ufsic.providers.Orders;
import ufsic.providers.Record;

public class Langs extends SchemeTable {

  public final static String TableName = "LANGS";
  
  protected final static String LangId = "LANG_ID";
  protected final static String Name = "NAME";
  protected final static String Priority = "PRIORITY";
  
  public Langs(Scheme scheme, String Name) {
    
    super(scheme,Name);
    
    open(new Orders(Priority));
  }

  public Langs(Scheme scheme) {
    this(scheme, TableName);
  }
  

  @Override
  public Class getRecordClass() {
    return Lang.class;
  }

  public Lang getLang(String langId) {
    
    Lang ret = null;
    for (Record r: this) {
      Lang lr = (Lang)r;
      if (lr.getLangId().same(langId)) {
        ret = lr;
        break;
      }
    }    
    return ret;
  }
  
}
