package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class File extends SchemeRecord {

  public File() {
    super(null,null);
  }
  
  public File(SchemeTable table, Record record) {
    super(table,record);
  }

  public Value getFileId() {
    
    return getValue(Files.FileId);
  }
  
  public Value getName() {
    
    return getValue(Files.Name);
  }
  
  public Value getExtension() {
    
    return getValue(Files.Extension);
  }

  public Value getLocation() {
    
    return getValue(Files.Location);
  }

  public Value getData() {
    
    return getValue(Files.Data);
  }

  public void setData(Object data) {
    
    if (!setValue(Files.Data,data)) {
      add(Files.Data,data);
    }
  }
  
}
