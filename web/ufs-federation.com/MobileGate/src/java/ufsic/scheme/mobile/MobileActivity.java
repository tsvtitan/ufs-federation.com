package ufsic.scheme.mobile;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeTable;

public class MobileActivity extends SchemeRecord {

  public MobileActivity(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public MobileActivity(SchemeTable table) {
    super(table, null);
  }
  
  public Value getMobileActivityId() {
    
    return getValue(MobileActivities.MobileActivityId);
  }

  public Value getLangId() {
    
    return getValue(MobileActivities.LangId);
  }
  
  public Value getCreated() {
  
    return getValue(MobileActivities.Created);
  }
  
  public Value getLocked() {
    
    return getValue(MobileActivities.Locked);
  }
    
  public Value getName() {
    
    return getValue(MobileActivities.Name);
  }
    
  public Value getImage() {
    
    return getValue(MobileActivities.Image);
  }
  
  public Value getImageExtension() {
    
    return getValue(MobileActivities.ImageExtension);
  }

  public Value getUrl() {
    
    return getValue(MobileActivities.Url);
  }

  public Value getHtml() {
    
    return getValue(MobileActivities.Html);
  }

  public Value getPriority() {
    
    return getValue(MobileActivities.Priority);
  }
  
  public Value getUfs() {
    
    return getValue(MobileMenus.Ufs);
  }
  
  public Value getPremier() {
    
    return getValue(MobileMenus.Premier);
  }
  
}
