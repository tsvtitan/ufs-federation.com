package ufsic.scheme.mobile;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;

public class Promotions extends SchemeTable<Promotion> {

  public final static String TableName = "PROMOTIONS";
  
  public final static String PromotionId = "PROMOTION_ID";
  public final static String PromotionProductId = "PROMOTION_PRODUCT_ID";
  public final static String DeviceId = "DEVICE_ID";
  public final static String Created = "CREATED";
  public final static String Expired = "EXPIRED";
  public final static String Accepted = "ACCEPTED";
  public final static String Rejected = "REJECTED";
  public final static String Name = "NAME";
  public final static String Phone = "PHONE";
  public final static String Email = "EMAIL";
  public final static String Brokerage = "BROKERAGE";
  public final static String Yield = "YIELD";
  
  public final static String CompanyName = "COMPANY_NAME";
  public final static String TypeName = "TYPE_NAME";
  
  public Promotions(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public Promotions(Scheme scheme) {
    
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return Promotion.class;
  }
  
}
