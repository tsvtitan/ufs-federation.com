package ufsic.scheme;

public class AccountDevices extends SchemeTable<AccountDevice> {
  
  public final static String TableName = "ACCOUNT_DEVICES";
  
  public final static String AccountId = "ACCOUNT_ID";
  public final static String DeviceId = "DEVICE_ID";
  public final static String Created = "CREATED";
  
  public final static String Login = "LOGIN";
  public final static String Id = "ID";
  
  public AccountDevices(Scheme scheme, String viewName) {
    super(scheme, viewName);
  }

  public AccountDevices(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    return AccountDevice.class;
  }
  
}
