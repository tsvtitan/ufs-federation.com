package ufsic.scheme.mobile;

import java.sql.Timestamp;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeTable;

public class Promotion extends SchemeRecord {

  public Promotion(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Promotion(SchemeTable table) {
    super(table);
  }
  
  public Value getPromotionId() {
    
    return getValue(Promotions.PromotionId);
  }

  public void setPromotionId(Value promotionId) {
    
    if (!setValue(Promotions.PromotionId,promotionId)) {
      add(Promotions.PromotionId,promotionId);
    }
  }
  
  public Value getPromotionProductId() {
    
    return getValue(Promotions.PromotionProductId);
  }
  
  public void setPromotionProductId(Value promotionProductId) {
    
    if (!setValue(Promotions.PromotionProductId,promotionProductId)) {
      add(Promotions.PromotionProductId,promotionProductId);
    }
  }

  public Value getDeviceId() {
    
    return getValue(Promotions.DeviceId);
  }
  
  public void setDeviceId(Value deviceId) {
    
    if (!setValue(Promotions.DeviceId,deviceId)) {
      add(Promotions.DeviceId,deviceId);
    }
  }
  
  public Value getCreated() {
    
    return getValue(Promotions.Created);
  }
  
  public void setCreated(Value created) {
    
    if (!setValue(Promotions.Created,created)) {
      add(Promotions.Created,created);
    }
  }
  
  public Value getExpired() {
    
    return getValue(Promotions.Expired);
  }
  
  public void setExpired(Value expired) {
    
    if (!setValue(Promotions.Expired,expired)) {
      add(Promotions.Expired,expired);
    }
  }
  
  public void setExpired(Timestamp expired) {
    
    if (!setValue(Promotions.Expired,expired)) {
      add(Promotions.Expired,expired);
    }
  }
  
  public Value getAccepted() {
    
    return getValue(Promotions.Accepted);
  }
  
  public void setAccepted(Value accepted) {
    
    if (!setValue(Promotions.Accepted,accepted)) {
      add(Promotions.Accepted,accepted);
    }
  }
  
  public Value getRejected() {
    
    return getValue(Promotions.Rejected);
  } 
  
  public void setRejected(Value rejected) {
    
    if (!setValue(Promotions.Rejected,rejected)) {
      add(Promotions.Rejected,rejected);
    }
  }
  
  public Value getName() {
    
    return getValue(Promotions.Name);
  } 
  
  public void setName(String name) {
    
    if (!setValue(Promotions.Name,name)) {
      add(Promotions.Name,name);
    }
  }
  
  public Value getPhone() {
    
    return getValue(Promotions.Phone);
  } 
  
  public void setPhone(String phone) {
    
    if (!setValue(Promotions.Phone,phone)) {
      add(Promotions.Phone,phone);
    }
  }
  
  public Value getEmail() {
    
    return getValue(Promotions.Email);
  } 
  
  public void setEmail(String email) {
    
    if (!setValue(Promotions.Email,email)) {
      add(Promotions.Email,email);
    }
  }
  
  public Value getBrokerage() {
    
    return getValue(Promotions.Brokerage);
  } 
  
  public void setBrokerage(Object brokerage) {
    
    if (!setValue(Promotions.Brokerage,brokerage)) {
      add(Promotions.Brokerage,brokerage);
    }
  }
  
  public Value getYield() {
    
    return getValue(Promotions.Yield);
  } 
  
  public void setYield(Object yield) {
    
    if (!setValue(Promotions.Yield,yield)) {
      add(Promotions.Yield,yield);
    }
  }
  
  public Value getTypeName() {
    
    return getValue(Promotions.TypeName);
  }
  
  public Value getCompanyName() {
    
    return getValue(Promotions.CompanyName);
  }
  
}
