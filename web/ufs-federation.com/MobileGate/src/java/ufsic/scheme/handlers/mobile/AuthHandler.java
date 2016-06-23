package ufsic.scheme.handlers.mobile;

import java.sql.Timestamp;
import ufsic.providers.Filter;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.AccountDevice;
import ufsic.scheme.AccountDevices;
import ufsic.scheme.Accounts;
import ufsic.scheme.Device;
import ufsic.scheme.Devices;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Session;
import ufsic.scheme.Token;
import ufsic.scheme.mobile.MobileMenus;

public class AuthHandler extends BaseHandler {

  final private String DefaultAuthMadeBy = "Apple";
  final private String DefaultAuthDeviceModel = "iPhohe 5";
  final private String DefaultAuthOS = "iOS";
  final private String DefaultAuthScreenSize = "960x480";
  final private String DefaultAuthId = "UKNOWN";
  final private String DefaultAuthCompany = "UFS";
  
  final private Integer ErrorCodeDeviceNotFound = 201;
  
  private Devices devices = null;
  
  public AuthHandler(Path path) {
    super(path);
  }
  
  protected class AuthResponse extends BaseResponse {
   
    private Result result = null;
            
    protected class Result {
    
      private String token = "";
      private String expired = "";
      private String email = "";
      private String phone = "";
      private String categoryId = "";
      private String categoryDelay = "";

      public String getToken() {
        return token;
      }

      public void setToken(String token) {
        this.token = isNotNull(token)?token:"";
      }

      public String getExpired() {
        return expired;
      }

      public void setExpired(String expired) {
        this.expired = expired;
      }
      
      public void setExpired(Timestamp expired) {
        
        Long temp = expired.getTime() / 1000L;
        this.expired = temp.toString();
      }
      
      public String getEmail() {
        return email;
      }

      public void setEmail(String email) {
        this.email = isNotNull(email)?email:"";
      }
      
      public String getPhone() {
        return phone;
      }

      public void setPhone(String phone) {
        this.phone = isNotNull(phone)?phone:"";
      }
      
      public String getCategoryId() {
        return categoryId;
      }

      public void setCategoryId(String categoryId) {
        this.categoryId = isNotNull(categoryId)?categoryId:"";
      }
      
      public String getCategoryDelay() {
        return categoryDelay;
      }

      public void setCategoryDelay(String categoryDelay) {
        this.categoryDelay = isNotNull(categoryDelay)?categoryDelay:"";
      }
      
    }
    
    public Result getResult() {
      
      if (isNull(result)) {
        result = new Result();
      }
      return result;
    }
  
    public void setResult(Result result) {
      
      this.result = result; 
    }
  }
  
  @Override
  protected void setTestHtml(StringBuilder builder) {
  
    builder.append(String.format("<input name=\"madeBy\" placeholder=\"madeBy\" value=\"%s\"/><br>",DefaultAuthMadeBy));
    builder.append(String.format("<input name=\"deviceModel\" placeholder=\"deviceModel\" value=\"%s\"/><br>",DefaultAuthDeviceModel));
    builder.append(String.format("<input name=\"os\" placeholder=\"os\" value=\"%s\"/><br>",DefaultAuthOS));
    builder.append(String.format("<input name=\"screenSize\" placeholder=\"960x480\" value=\"%s\"/><br>",DefaultAuthScreenSize));
    builder.append(String.format("<input name=\"id\" placeholder=\"unique ID\" value=\"%s\"/><br>",DefaultAuthId));
    builder.append(String.format("<input name=\"company\" placeholder=\"company\" value=\"%s\"/><br>",DefaultAuthCompany));
    builder.append(String.format("<input name=\"version\" placeholder=\"version\" value=\"%s\"/><br>","1.2"));
  }

  private Devices getDevices() {
    
    if (isNull(devices)) {
      devices = new Devices(getScheme());
    }
    return devices;
  }
  
  private Device tryDevice() {
    
    Path path = getPath();

    String manufacturer = path.getParameterValue("madeBy",(String)null);
    String model = path.getParameterValue("deviceModel",(String)null);
    String os = path.getParameterValue("os",(String)null);
    String id = path.getParameterValue("id",(String)null);
    String screenSize = path.getParameterValue("screenSize",(String)null);
    String version = path.getParameterValue("version",(String)null);
    String company = path.getParameterValue("company",(String)null);
    if (isEmpty(company)) {
      company = MobileMenus.Ufs;
    }
    
    Filter filter = new Filter();
    if (isNull(id)) {
      filter.And(Devices.Manufacturer,manufacturer)
            .And(Devices.Model,model)
            .And(Devices.OS,os)
            .And(Devices.Company,company);
    } else {
      filter.And(Devices.Id,id);
    }
    
    Device ret = getDevices().first(filter);
    if (isNull(ret)) {
      
      Device d = new Device(getDevices());
      d.setDeviceId(getProvider().getUniqueId());
      d.setManufacturer(manufacturer);
      d.setModel(model);
      d.setOS(os);
      d.setScreenSize(screenSize);
      d.setId(id);
      d.setVersion(version);
      d.setCompany(company);
      
      if (d.insert()) {
        ret = d;
      }
      
    } else {
      
      Device d = new Device(getDevices());
      d.setManufacturer(manufacturer);
      d.setModel(model);
      d.setOS(os);
      d.setScreenSize(screenSize);
      d.setVersion(version);
      d.setCompany(company);
      
      d.update(new Filter(Devices.DeviceId,ret.getDeviceId()));
    } 
            
    return ret;
  }
  
  private Account getAccount(Token token) {
   
    Account account;
    
    Scheme scheme = getScheme();
    Value deviceId = token.getDeviceId();
    
    AccountDevices accountDevices = new AccountDevices(scheme);
    
    AccountDevice device = accountDevices.first(new Filter(AccountDevices.DeviceId,deviceId));
    if (isNull(device)) {

      boolean needDevice = true;
      
      account = new Account(scheme.getAccounts());
      if (!account.select(new Filter(Accounts.Login,deviceId))) {
        
        account.setAccountId(getProvider().getUniqueId());
        account.setLogin(deviceId);
        account.setLocked(scheme.getStamp());
        
        needDevice = account.insert();
      }
      
      if (needDevice) {
        
        device = new AccountDevice(accountDevices);
        device.setAccountId(account.getAccountId());
        device.setDeviceId(deviceId);
        device.insert();
      }
    } else {
      account = scheme.getAccounts().first(new Filter(Accounts.AccountId,device.getAccountId()));
    }
    
    return account;
  }
          
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    AuthResponse response = new AuthResponse();
 
    Scheme scheme = getScheme();
    Session session = scheme.getSession();

    Device device = tryDevice();
    if (isNotNull(device)) {

      Value tokenId = getProvider().getUniqueId();
      Value created = getProvider().getNow();

      Token token = new Token(getTokens());
      token.setTokenId(tokenId);
      token.setDeviceId(device.getDeviceId());
      token.setSessionId(isNotNull(session)?session.getSessionId():null);
      token.setCreated(created);

      Timestamp expired;
      String to = scheme.getOption("Token.Timeout");
      if (isNotNull(to)) {
        expired = created.addSeconds(Integer.parseInt(to));
      } else {
        expired = created.addHours(24);
      }
      token.setExpired(expired);

      if (token.insert()) {

        AuthResponse.Result result = response.getResult();
        result.setToken(tokenId.asString());
        result.setExpired(expired);

        Account account = getAccount(token);
        if (isNotNull(account)) {
          result.setEmail(account.getEmail().asString());
          result.setPhone(account.getPhone().asString());
        }
        
        String categoryId = scheme.getOption("AuthHandler.DefaultMenuId");
        
        Value company = device.getCompany();
        if (company.same(MobileMenus.Ufs)) {
          categoryId = scheme.getOption("AuthHandler.UfsMenuId",categoryId);
        } else if (company.same(MobileMenus.Premier)) {
          categoryId = scheme.getOption("AuthHandler.PremierMenuId",categoryId);
        }
        result.setCategoryId(categoryId);
        
        result.setCategoryDelay(scheme.getOption("AuthHandler.DefaultMenuDelay"));
        
      } else throw new BaseResponseException(response,ErrorCodeInternalError);
      
    } else throw new BaseResponseException(response,ErrorCodeDeviceNotFound);

    return response;
  }

}
