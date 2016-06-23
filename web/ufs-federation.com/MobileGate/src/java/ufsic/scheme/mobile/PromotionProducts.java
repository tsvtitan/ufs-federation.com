package ufsic.scheme.mobile;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;

public class PromotionProducts extends SchemeTable<PromotionProduct> {

  public final static String TableName = "PROMOTION_PRODUCTS";
  
  public final static String PromotionProductId = "PROMOTION_PRODUCT_ID";
  public final static String PromotionTypeId = "PROMOTION_TYPE_ID";
  public final static String PromotionCompanyId = "PROMOTION_COMPANY_ID";
  public final static String Created = "CREATED";
  public final static String Begin = "BEGIN";
  public final static String End = "END";
  public final static String Locked = "LOCKED";
  public final static String Timeout = "TIMEOUT";
  public final static String Priority = "PRIORITY";
  
  public final static String TypeName = "TYPE_NAME";
  public final static String TypeDescription = "TYPE_DESCRIPTION";
  public final static String TypeAgreement = "TYPE_AGREEMENT";
  public final static String TypeImage = "TYPE_IMAGE";
  public final static String TypeImageExtension = "TYPE_IMAGE_EXTENSION";
  public final static String CompanyName = "COMPANY_NAME";
  
  public PromotionProducts(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public PromotionProducts(Scheme scheme) {
    
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return PromotionProduct.class;
  }
  
}
