package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class ParentPath extends Path {

  public ParentPath(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getLastPathId() {
    
    return getValue(ParentPaths.LastPathId);
  }

  public Value getTitle() {
    
    return getValue(ParentPaths.Title);
  }
  
  public String getTITLE() {
    
    return getScheme().getDictionary().replace(getTitle().asString());
  }
  
}
