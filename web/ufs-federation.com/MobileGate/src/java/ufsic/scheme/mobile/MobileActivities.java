package ufsic.scheme.mobile;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;

public class MobileActivities extends SchemeTable<MobileActivity> {
  
  public final static String TableName = "MOBILE_ACTIVITIES";
  
  public final static String MobileActivityId = "MOBILE_ACTIVITY_ID";
  public final static String LangId = "LANG_ID";
  public final static String Created = "CREATED";
  public final static String Locked = "LOCKED";
  public final static String Name = "NAME";
  public final static String Image = "IMAGE";
  public final static String ImageExtension = "IMAGE_EXTENSION";
  public final static String Url = "URL";
  public final static String Html = "HTML";
  public final static String Priority = "PRIORITY";
  public final static String Ufs = "UFS";
  public final static String Premier = "PREMIER";
  
  public MobileActivities(Scheme scheme, String viewName) {
    super(scheme, viewName);
  }

  public MobileActivities(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    return MobileActivity.class;
  }
  
}
