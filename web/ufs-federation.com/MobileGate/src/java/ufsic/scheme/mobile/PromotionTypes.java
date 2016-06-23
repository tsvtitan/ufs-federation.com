package ufsic.scheme.mobile;

import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;

public class PromotionTypes extends SchemeTable<PromotionType> {

  public final static String TableName = "PROMOTION_TYPES";
  
  public final static String PromotionTypeId = "PROMOTION_TYPE_ID";
  public final static String Name = "NAME";
  public final static String Description = "DESCRIPTION";
  public final static String Agreement = "AGREEMENT";
  public final static String Image = "IMAGE";
  public final static String ImageExtension = "IMAGE_EXTENSION";

  
  public PromotionTypes(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public PromotionTypes(Scheme scheme) {
    
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return PromotionType.class;
  }
  
}
