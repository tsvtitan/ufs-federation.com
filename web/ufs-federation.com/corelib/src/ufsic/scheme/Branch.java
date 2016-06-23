package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Branch extends SchemeRecord {

  public Branch(SchemeTable table, Record record) {
    super(table, record);
  }

  public Branch(SchemeTable table) {
    super(table);
  }
  
  public Value getBranchId() {
    
    return getValue(Branches.BranchId);
  }

  public Value getLangId() {
    
    return getValue(Branches.LangId);
  }
  
  public Value getName() {
    
    return getValue(Branches.Name);
  }
  
  public Value getCreated() {
    
    return getValue(Branches.Created);
  }

  public Value getLocked() {
    
    return getValue(Branches.Locked);
  }

  public Value getRegion() {
    
    return getValue(Branches.Region);
  }

  public Value getCity() {
    
    return getValue(Branches.City);
  }  

  public Value getAddress() {
    
    return getValue(Branches.Address);
  }  

  public Value getLatitude() {
    
    return getValue(Branches.Latitude);
  }  

  public Value getLongitude() {
    
    return getValue(Branches.Logitude);
  }  

  public Value getPriority() {
    
    return getValue(Branches.Priority);
  }  

  public Value getContatcts() {
    
    return getValue(Branches.Contacts);
  }  

  public Value getMap() {
    
    return getValue(Branches.Map);
  }  

  public Value getId() {
    
    return getValue(Branches.Id);
  }  
  
}
