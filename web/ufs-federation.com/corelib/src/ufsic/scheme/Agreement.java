package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Agreement extends SchemeRecord {

  public Agreement() {
  }

  public Agreement(SchemeTable table) {
    super(table);
  }

  public Agreement(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getAgreementId() {
    
    return getValue(Agreements.AgreementId);
  }

  public Value getAccountId() {
    
    return getValue(Agreements.AccountId);
  }

  public Value getCreated() {
    
    return getValue(Agreements.Created);
  }

  public Value getLocked() {
    
    return getValue(Agreements.Locked);
  }

  public Value getName() {
    
    return getValue(Agreements.Name);
  }

  public Value getDescription() {
    
    return getValue(Agreements.Description);
  }

  public String getDESCRIPTION() {
    
    return getDescription().asString();
  }
  
  public Value getPriority() {
    
    return getValue(Agreements.Priority);
  }

  public Value getSystemId() {
    
    return getValue(Agreements.SystemId);
  }

  public Value getIdInSystem() {
    
    return getValue(Agreements.IdInSystem);
  }
  
}
