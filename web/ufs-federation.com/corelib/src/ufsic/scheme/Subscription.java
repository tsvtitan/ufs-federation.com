package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Subscription extends SchemeRecord {
  
  public enum DeliveryType {
    
    EMAIL, APP, SMS;
  }
  
  public Subscription(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Subscription(SchemeTable table) {
    super(table);
  }
  
  public Value getSubscriptionId() {
    
    return getValue(Subscriptions.SubscriptionId);
  }
  
  public void setSubscriptionId(Value subscriptionId) {
    
    if (!setValue(Subscriptions.SubscriptionId,subscriptionId)) {
      add(Subscriptions.SubscriptionId,subscriptionId);
    }
  }
  
  public Value getAccountId() {
    
    return getValue(Subscriptions.AccountId);
  }
  
  public void setAccountId(Value accountId) {
    
    if (!setValue(Subscriptions.AccountId,accountId)) {
      add(Subscriptions.AccountId,accountId);
    }
  }
  
  public Value getLangId() {
    
    return getValue(Subscriptions.LangId);
  }
  
  public void setLangId(Value langId) {
    
    if (!setValue(Subscriptions.LangId,langId)) {
      add(Subscriptions.LangId,langId);
    }
  }
  
  public Value getDeliveryType() {
    
    return getValue(Subscriptions.DeliveryType);
  }
  
  public void setDeliveryType(Value deliveryType) {
    
    if (!setValue(Subscriptions.DeliveryType,deliveryType)) {
      add(Subscriptions.DeliveryType,deliveryType);
    }
  }
  
  public void setDeliveryType(Object deliveryType) {
    
    if (!setValue(Subscriptions.DeliveryType,deliveryType)) {
      add(Subscriptions.DeliveryType,deliveryType);
    }
  }  
  
  public Value getCreated() {
    
    return getValue(Subscriptions.Created);
  }
  
  public Value getStarted() {
    
    return getValue(Subscriptions.Started);
  }
  
  public void setStarted(Value started) {
    
    if (!setValue(Subscriptions.Started,started)) {
      add(Subscriptions.Started,started);
    }
  }
  
  public Value getFinsihed() {
    
    return getValue(Subscriptions.Finished);
  }
  
  public void setFinished(Value finished) {
    
    if (!setValue(Subscriptions.Finished,finished)) {
      add(Subscriptions.Finished,finished);
    }
  }
  
  public Value getKeyword() {
    
    return getValue(Subscriptions.Keyword);
  }
  
  public void setKeyword(Value keyword) {
    
    if (!setValue(Subscriptions.Keyword,keyword)) {
      add(Subscriptions.Keyword,keyword);
    }
  }
  
  public void setKeyword(Object keyword) {
    
    if (!setValue(Subscriptions.Keyword,keyword)) {
      add(Subscriptions.Keyword,keyword);
    }
  }
  
  public Value getEmail() {
    
    return getValue(Subscriptions.Email);
  }
  
  public Value getPhone() {
    
    return getValue(Subscriptions.Phone);
  }
  
  public Value getName() {
    
    return getValue(Subscriptions.Name);
  }
}
