package ufsic.scheme.mobile;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeTable;

public class PromotionCompany extends SchemeRecord {

  public PromotionCompany(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getPromotionCompanyId() {
    
    return getValue(PromotionCompanies.PromotionCompanyId);
  }

  public Value getParentId() {
    
    return getValue(PromotionCompanies.ParentId);
  }

  public Value getName() {
    
    return getValue(PromotionCompanies.Name);
  }

  public Value getDescription() {
    
    return getValue(PromotionCompanies.Description);
  }
  
  public Value getAppLink() {
    
    return getValue(PromotionCompanies.AppLink);
  }
  
  public Value getLocked() {
    
    return getValue(PromotionCompanies.Locked);
  }

  public Value getParentName() {
    
    return getValue(PromotionCompanies.ParentName);
  }
  
  public Value getLevel() {
    
    return getValue(PromotionCompanies.Level);
  }
  
  public Value getExpired() {
    
    return getValue(PromotionCompanies.Expired);
  }
}
