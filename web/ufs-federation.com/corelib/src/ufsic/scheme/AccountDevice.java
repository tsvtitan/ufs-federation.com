package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class AccountDevice extends SchemeRecord {
  
  public AccountDevice(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public AccountDevice(SchemeTable table) {
    super(table);
  }
  
  public Value getDeviceId() {
  
    return getValue(AccountDevices.DeviceId);
  }

  public void setDeviceId(Value deviceId) {
    
    if (!setValue(AccountDevices.DeviceId,deviceId)) {
      add(AccountDevices.DeviceId,deviceId);
    }
  }
  
  public Value getAccountId() {
  
    return getValue(AccountDevices.AccountId);
  }

  public void setAccountId(Value accountId) {
    
    if (!setValue(AccountDevices.AccountId,accountId)) {
      add(AccountDevices.AccountId,accountId);
    }
  }
  
  public Value getCreated() {
  
    return getValue(AccountDevices.Created);
  }
  
}
