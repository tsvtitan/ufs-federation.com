package ufsic.providers;

import ufsic.utils.Utils;

public class Field {

  private String name;
  private Value value;
  private boolean needInsert = true;
  private boolean needUpdate = true;


  public Field(String name, Value value) {

    this.name = name;
    this.value = value;
  }

  public String getName() {
    return name;
  }

  public void setName(String name) {
    this.name = name;
  }
  
  public Value getValue() {
    
    if (Utils.isNull(value)) {
      value = new Value(null);
    }
    return value;
  }

  public void setValue(Value value) {
    if (value!=null) {
      this.value = value;
    } else {
      this.value = new Value(null);
    }
  }

  public boolean isNeedInsert() {
    return needInsert;
  }

  public void setNeedInsert(boolean needInsert) {
    this.needInsert = needInsert;
  }

  public boolean isNeedUpdate() {
    return needUpdate;
  }

  public void setNeedUpdate(boolean needUpdate) {
    this.needUpdate = needUpdate;
  }

}
