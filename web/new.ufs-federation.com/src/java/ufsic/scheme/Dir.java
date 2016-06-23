package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Dir extends SchemeRecord {

  public Dir(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getDirId() {
    
    return getValue(Dirs.DirId);
  }

  public Value getLocation() {
    
    return getValue(Dirs.Location);
  }

  public void setLocation(Object location) {

    if (!setValue(Dirs.Location,location)) {
      add(Dirs.Location,location);
    }
  }
  
  public Value getDescription() {
    
    return getValue(Dirs.Description);
  }
  
}
