package ufsic.scheme.mobile;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeTable;

public class PromotionProduct extends SchemeRecord {

  public PromotionProduct(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getPromotionProductId() {
    
    return getValue(PromotionProducts.PromotionProductId);
  }
  
  public Value getPromotionCompanyId() {
    
    return getValue(PromotionProducts.PromotionCompanyId);
  }

  public Value getPromotionTypeId() {
    
    return getValue(PromotionProducts.PromotionTypeId);
  }
  
  public Value getCreated() {
    
    return getValue(PromotionProducts.Created);
  }

  public Value getBegin() {
    
    return getValue(PromotionProducts.Begin);
  }  
  
  public Value getEnd() {
    
    return getValue(PromotionProducts.End);
  }  
  
  public Value getLocked() {
    
    return getValue(PromotionProducts.Locked);
  } 
  
  public Value getTimeout() {
    
    return getValue(PromotionProducts.Timeout);
  } 
  
  public Value getPriority() {
    
    return getValue(PromotionProducts.Priority);
  }
  
  public Value getCompanyName() {
    
    return getValue(PromotionProducts.CompanyName);
  } 
  
  public Value getTypeName() {
    
    return getValue(PromotionProducts.TypeName);
  }

  public Value getTypeDescription() {
    
    return getValue(PromotionProducts.TypeDescription);
  }
  
  public Value getTypeAgreement() {
    
    return getValue(PromotionProducts.TypeAgreement);
  }
  
  public Value getTypeImage() {
    
    return getValue(PromotionProducts.TypeImage);
  }
  
  public Value getTypeImageExtension() {
    
    return getValue(PromotionProducts.TypeImageExtension);
  }
}
