package ufsic.scheme.mobile;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;

public class PromotionCompanies extends SchemeTable<PromotionCompany> {

  public final static String TableName = "PROMOTION_COMPANIES";
  
  public final static String PromotionCompanyId = "PROMOTION_COMPANY_ID";
  public final static String ParentId = "PARENT_ID";
  public final static String Name = "NAME";
  public final static String Description = "DESCRIPTION";
  public final static String AppLink = "APP_LINK";
  public final static String Locked = "LOCKED";
  
  public final static String ParentName = "PARENT_NAME";
  public final static String Level = "LEVEL";
  public final static String Expired = "EXPIRED";
  
  public PromotionCompanies(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public PromotionCompanies(Scheme scheme) {
    
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return PromotionCompany.class;
  }
  
}
