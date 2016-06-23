package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Devices extends SchemeTable<Device> {
  
  public final static String TableName = "DEVICES";
  
  public final static String DeviceId = "DEVICE_ID";
  public final static String Created = "CREATED";
  public final static String Locked = "LOCKED";
  public final static String Manufacturer = "MANUFACTURER";
  public final static String Model = "MODEL";
  public final static String OS = "OS";
  public final static String ScreenSize = "SCREEN_SIZE";
  public final static String Id = "ID";
  public final static String Version = "VERSION";
  public final static String Company = "COMPANY";
  
  public Devices(Scheme scheme, String viewName) {
    super(scheme, viewName);
  }

  public Devices(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    return Device.class;
  }
  
  public Device getDevice(Value deviceId) {
    
    Device ret = null;
    Record r = getProvider().first(getViewName(),new Filter(DeviceId,deviceId));
    if (isNotNull(r)) {
      ret = new Device(this,r);
    }
    return ret;
  }
  
}
