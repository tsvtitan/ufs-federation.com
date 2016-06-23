package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Device extends SchemeRecord {
  
  public Device(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Device(SchemeTable table) {
    super(table);
  }
  
  public Value getDeviceId() {
  
    return getValue(Devices.DeviceId);
  }
  
  public void setDeviceId(Value deviceId) {

    if (!setValue(Devices.DeviceId,deviceId)) {
      add(Devices.DeviceId,deviceId);
    }
  }
  
  public Value getCreated() {
  
    return getValue(Devices.Created);
  }
  
  public void setCreated(Value created) {

    if (!setValue(Devices.Created,created)) {
      add(Devices.Created,created);
    }
  }
  
  public Value getLocked() {
  
    return getValue(Devices.Locked);
  }
  
  public Value getManufacturer() {
  
    return getValue(Devices.Manufacturer);
  }
  
  public void setManufacturer(Value manufacturer) {

    if (!setValue(Devices.Manufacturer,manufacturer)) {
      add(Devices.Manufacturer,manufacturer);
    }
  }

  public void setManufacturer(String manufacturer) {

    setManufacturer(new Value(manufacturer));
  }
  
  public Value getModel() {
  
    return getValue(Devices.Model);
  }
  
  public void setModel(Value model) {

    if (!setValue(Devices.Model,model)) {
      add(Devices.Model,model);
    }
  }
  
  public void setModel(String model) {

    setModel(new Value(model));
  }
  
  public Value getOS() {
  
    return getValue(Devices.OS);
  }
  
  public void setOS(Value os) {

    if (!setValue(Devices.OS,os)) {
      add(Devices.OS,os);
    }
  }
  
   public void setOS(String os) {

    setOS(new Value(os));
  }
   
  public Value getScreenSize() {
  
    return getValue(Devices.ScreenSize);
  }
  
  public void setScreenSize(Value screenSize) {

    if (!setValue(Devices.ScreenSize,screenSize)) {
      add(Devices.ScreenSize,screenSize);
    }
  }
  
   public void setScreenSize(String screenSize) {

    setScreenSize(new Value(screenSize));
  }   
  
  public Value getId() {
  
    return getValue(Devices.Id);
  }
  
  public void setId(Value id) {

    if (!setValue(Devices.Id,id)) {
      add(Devices.Id,id);
    }
  }
  
  public void setId(String id) {

    setId(new Value(id));
  }
  
  public Value getVersion() {
  
    return getValue(Devices.Version);
  }
  
  public void setVersion(Value version) {

    if (!setValue(Devices.Version,version)) {
      add(Devices.Version,version);
    }
  }
  
  public void setVersion(String version) {

    if (!setValue(Devices.Version,version)) {
      add(Devices.Version,version);
    }
  }
  
  public Value getCompany() {
  
    return getValue(Devices.Company);
  }
  
  public void setCompany(Value company) {

    if (!setValue(Devices.Company,company)) {
      add(Devices.Company,company);
    }
  }
  
  public void setCompany(String company) {

    if (!setValue(Devices.Company,company)) {
      add(Devices.Company,company);
    }
  }
  
}
