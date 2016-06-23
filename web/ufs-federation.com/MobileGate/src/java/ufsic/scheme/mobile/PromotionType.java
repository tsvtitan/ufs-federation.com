package ufsic.scheme.mobile;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeTable;

public class PromotionType extends SchemeRecord {

  public PromotionType(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getPromotionTypeId() {
    
    return getValue(PromotionTypes.PromotionTypeId);
  }
  
  public Value getName() {
    
    return getValue(PromotionTypes.Name);
  }
  
  public Value getDescription() {
    
    return getValue(PromotionTypes.Description);
  }

  public Value getAgreement() {
    
    return getValue(PromotionTypes.Agreement);
  }

  public Value getImage() {
    
    return getValue(PromotionTypes.Image);
  }

  public Value getImageExtension() {
    
    return getValue(PromotionTypes.ImageExtension);
  }
  
}
