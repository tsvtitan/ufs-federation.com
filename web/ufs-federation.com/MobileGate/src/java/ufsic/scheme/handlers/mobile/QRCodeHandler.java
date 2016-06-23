package ufsic.scheme.handlers.mobile;

import java.util.ArrayList;
import java.util.concurrent.TimeUnit;

import com.fasterxml.jackson.annotation.JsonIgnore;
import java.util.Arrays;
import java.util.List;

import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Account;

import ufsic.scheme.Path;
import ufsic.scheme.mobile.Promotion;
import ufsic.scheme.mobile.PromotionCompanies;
import ufsic.scheme.mobile.PromotionCompany;
import ufsic.scheme.mobile.PromotionProduct;
import ufsic.scheme.mobile.PromotionProducts;
import ufsic.scheme.mobile.PromotionTypes;
import ufsic.scheme.mobile.Promotions;
import ufsic.scheme.Scheme;
import ufsic.scheme.Token;
import ufsic.utils.Utils;

public class QRCodeHandler extends TokenHandler {

  final static private String UnknownQRCode = "Неизвестный QR-код";
  
  public QRCodeHandler(Path path) {
    super(path);
  }
  
  protected enum StatusType {
  
    UNKNOWN, ACCEPTED, REJECTED, EXPIRED, DISABLED, FINISHED, PREPARED, STARTED;
  }
  
  protected class QRCodeResponse extends BaseResponse {
   
    private Result result = null;
    
    protected class Result {
    
      private String kind = "";
      
      public String getKind() {
        
        String ret = kind;
        if (this instanceof RedirectionResult) {
          ret = "redirection";
        } else if (this instanceof MessageResult) {
          ret = "message";
        } else if (this instanceof PromotionResult) {
          ret = "promotion";
        }
        return ret;
      }
      
      public void setKind(String kind) {
        this.kind = kind;
      }
    }
    
    protected class MessageResult extends Result {
      
      private Message message = null;
      
      protected class Message {
        
        private String text = "";
        
        public String getText() {
          return text;
        }

        public void setText(String text) {
          this.text = isNotNull(text)?text:"";
        }
      }
      
      public Message getMessage() {
        
        if (isNull(message)) {
          message = new Message();
        }
        return message;
      }
      
      public void setMessage(Message message) {
        this.message = message;
      }
    }
    
    protected class RedirectionResult extends Result {
      
      private Redirection redirection = null;
      
      protected class Redirection {
      
        private String url = "";
        
        public String getUrl() {
          return url;
        }

        public void setUrl(String url) {
          this.url = isNotNull(url)?url:"";
        }
      }
      
      public Redirection getRedirection() {
        
        if (isNull(redirection)) {
          redirection = new Redirection();
        }
        return redirection;
      }
      
      public void setRedirection(Redirection redirection) {
        this.redirection = redirection;
      }
    }
    
    protected class PromotionResult extends Result {
      
      private Promotion promotion = null;
      
      protected class Promotion {
        
        private String title = "";
        private boolean registered = false;
        private Registration registration = null;
        private ArrayList<Product> products = new ArrayList<>();
        
        protected class Registration {
          
          private String name = "";
          private String phone = "";
          private String email = "";
          private boolean brokerage = false;
          private boolean yield = false;
          
          public String getName() {
            return name;
          }
          
          public void setName(String name) {
            this.name = name;
          }
          
          public String getPhone() {
            return phone;
          }
          
          public void setPhone(String phone) {
            this.phone = phone;
          }
          
          public String getEmail() {
            return email;
          }
          
          public void setEmail(String email) {
            this.email = email;
          }
          
          public boolean getBrokerage() {
            return brokerage;
          }
          
          public void setBrokerage(boolean brokerage) {
            this.brokerage = brokerage;
          }
          
          public boolean getYield() {
            return yield;
          }
          
          public void setYield(boolean yield) {
            this.yield = yield;
          }
        }
        
        public String getTitle() {
          return title;
        }

        public void setTitle(String title) {
          this.title = isNotNull(title)?title:"";
        }
        
        public boolean getRegistered() {
          return registered;
        }
        
        public void setRegistered(boolean registered) {
          this.registered = registered;
        }
        
        public Registration getRegistration() {
          
          if (isNull(registration)) {
            registration = new Registration();
          }
          return registration;
        }
        
        public void setRegistration(Registration registration) {
          this.registration = registration;
        }
        
        public boolean registrationExists() {
          return isNotNull(registration);
        }
        
        protected class Product {
          
          private String name = "";
          private String description = "";
          private String agreement = null;
          private String imageURL = "";
          private String promotionID = "";
          private String status = "";
          private Long countdown = null;
          
          public String getName() {
            return name;
          }

          public void setName(String name) {
            this.name = isNotNull(name)?name:"";
          }
          
          public String getDescription() {
            return description;
          }

          public void setDescription(String description) {
            this.description = isNotNull(description)?description:"";
          }
          
          public String getAgreement() {
            return agreement;
          }

          public void setAgreement(String agreement) {
            this.agreement = isEmpty(agreement)?null:agreement;
          }
          
          public String getImageURL() {
            return imageURL;
          }

          public void setImageURL(String imageURL) {
            this.imageURL = imageURL;
          }
          
          public String getPromotionID() {
            return promotionID;
          }

          public void setPromotionID(String promotionID) {
            this.promotionID = promotionID;
          }
          
          public String getStatus() {
            return status;
          }

          public void setStatus(String status) {
            this.status = isNotNull(status)?status:"";
          }
          
          public Long getCountdown() {
            return countdown;
          }

          public void setCountdown(Long countdown) {
            this.countdown = countdown;
          }
        }
        
        public ArrayList<Product> getProducts() {
          return products;
        }
        
        public void setProducts(ArrayList<Product> products) {
          this.products = products;
        }
      }
      
      public Promotion getPromotion() {
        
        if (isNull(promotion)) {
          promotion = new Promotion();
        }
        return promotion;
      }
      
      public void setPromotion(Promotion promotion) {
        this.promotion = promotion;
      }
    }
    
    public Result getResult() {
      
      return result;
    }
  
    public void setResult(Result result) {
      
      this.result = result; 
    } 
    
    @JsonIgnore
    public MessageResult.Message getMessage() {
      
      MessageResult.Message ret = null;
      if (isNull(result)) {
        result = new MessageResult();
      }
      if (result instanceof MessageResult) {
        ret = ((MessageResult)result).getMessage();
      }
      return ret;
    }
    
    @JsonIgnore
    public RedirectionResult.Redirection getRedirection() {
      
      RedirectionResult.Redirection ret = null;
      if (isNull(result)) {
        result = new RedirectionResult();
      }
      if (result instanceof RedirectionResult) {
        ret = ((RedirectionResult)result).getRedirection();
      }
      return ret;
    }
    
    @JsonIgnore
    public PromotionResult.Promotion getPromotion() {
      
      PromotionResult.Promotion ret = null;
      if (isNull(result)) {
        result = new PromotionResult();
      }
      if (result instanceof PromotionResult) {
        ret = ((PromotionResult)result).getPromotion();
      }
      return ret;
    }
    
  }
  
  @Override
  protected void setTestHtml(StringBuilder builder) {
    
    super.setTestHtml(builder);
    builder.append(String.format("<input name=\"text\" placeholder=\"text\" value=\"%s\"/><br>",""));
  }
  
  private PromotionCompany getCompany(String url) {
    
    PromotionCompany ret = null;
    if (!isEmpty(url)) {
      
      PromotionCompanies companies = new PromotionCompanies(getScheme());
      
      Filter filter = new Filter();
      filter.And(PromotionCompanies.Locked).IsNull();
      filter.And(PromotionCompanies.AppLink,url);
      
      ret = companies.first(filter);
    }
    return ret;
  }
  
  private PromotionProducts getProducts(PromotionCompany company) {
    
    PromotionProducts ret = null;
    if (isNotNull(company)) {
      
      Scheme scheme = getScheme();
      PromotionProducts pp = new PromotionProducts(scheme);
      
      GroupFilter filter = new GroupFilter();
      filter.And(PromotionProducts.PromotionCompanyId,company.getPromotionCompanyId());
      filter.And(PromotionProducts.Locked).IsNull();
      
      GroupFilter begin = new GroupFilter();
      begin.Or(PromotionProducts.Begin).IsNull();
      begin.Or(new Filter().Add(PromotionProducts.Begin).IsNotNull().And(PromotionProducts.Begin).GreaterOrEqual(scheme.getStamp()));
      filter.And(begin);
      
      GroupFilter end = new GroupFilter();
      end.Or(PromotionProducts.End).IsNull();
      end.Or(new Filter().Add(PromotionProducts.End).IsNotNull().And(PromotionProducts.End).LessOrEqual(scheme.getStamp()));
      filter.And(end);
              
      if (pp.open(filter,new Orders(PromotionProducts.Priority))) {
        ret = pp;
      }
    }
    return ret;
  }
      
  private Promotions getPromotions(Token token, PromotionProducts products) {
    
    Promotions ret = new Promotions(getScheme());
    if (!products.isEmpty()) {
      
      GroupFilter filter = new GroupFilter();
      filter.And(Promotions.DeviceId,token.getDeviceId());

      Filter f = new Filter();
      for (Record r: products) {

        PromotionProduct p = (PromotionProduct)r;
        f.Or(Promotions.PromotionProductId,p.getPromotionProductId());
      }
      filter.And(f);
      ret.open(filter);
    }
    return ret;
  }
  
  private Promotion getPromotion(Promotions promotions, Value promotionProductId) {
    
    Promotion ret = null;
    Record r = promotions.findFirst(Promotions.PromotionProductId,promotionProductId);
    if (isNotNull(r) && r instanceof Promotion) {
      ret = (Promotion)r;
    }
    return ret;
  }
  
  private boolean getRegistered(Token token) {
    
    boolean ret = false;
    
    Account account = getAccount(token);
    if (isNotNull(account)) {
      //ret = account.get
    }
    return ret;
  }
  
  private boolean setPromotion(Token token, QRCodeResponse response, String url) {
    
    boolean ret = false;
    
    PromotionCompany company = getCompany(url);
            
    PromotionProducts products = getProducts(company);
    if (isNotNull(products) && products.size()>0) {
      
      QRCodeResponse.PromotionResult.Promotion promotion = response.getPromotion();
      promotion.setTitle(company.getName().asString());
      
      Promotions promotions = getPromotions(token,products);
        
      Value stamp = getScheme().getStamp();

      boolean registered = true;
      String regName = "";
      String regPhone = "";
      String regEmail = "";
      boolean regBrokerage = false;
      boolean regYield = false;

      for (Record r: products) {

        PromotionProduct pp = (PromotionProduct)r;

        QRCodeResponse.PromotionResult.Promotion.Product product = promotion.new Product();

        product.setName(pp.getTypeName().asString());
        product.setDescription(pp.getTypeDescription().asString());
        product.setAgreement(pp.getTypeAgreement().asString());

        Value image = pp.getTypeImage();
        if (image.isNotNull() && image.length()>0) {

          String location = FilesHandler.getLocation(PromotionTypes.TableName,PromotionTypes.PromotionTypeId,
                                                     pp.getPromotionTypeId().asString(),PromotionTypes.Image);
          product.setImageURL(getFileUrl(token,location,null,pp.getTypeImageExtension().asString(),image));
        }


        Promotion p = getPromotion(promotions,pp.getPromotionProductId());
        if (isNull(p)) {

          Value promotionId = getProvider().getUniqueId();
          Value created = stamp;

          p = new Promotion(promotions);
          p.setPromotionId(promotionId);
          p.setPromotionProductId(pp.getPromotionProductId());
          p.setDeviceId(token.getDeviceId());
          p.setCreated(created);

          Value timeout = pp.getTimeout();
          if (timeout.isNotNull() && timeout.asInteger()>0) {
            p.setExpired(created.addSeconds(timeout.asInteger()));
          }

          if (p.insert()) {
            product.setPromotionID(promotionId.asString());
          }

        } else {
          product.setPromotionID(p.getPromotionId().asString());
        }

        Value accepted = p.getAccepted();
        Value rejected = p.getRejected();
        Value expired = p.getExpired();

        if (expired.isNotNull()) {

          if (accepted.isNull() && rejected.isNull()) {

            long t1 = stamp.asTimestamp().getTime();
            long t2 = expired.asTimestamp().getTime();
            product.setCountdown(TimeUnit.MILLISECONDS.toSeconds(t2-t1));
          }
        }

        Value locked = pp.getLocked();
        Value begin = pp.getBegin();
        Value end = pp.getEnd();

        StatusType status;

        StatusType[] regStatuses = {StatusType.STARTED};

        List<StatusType> activeStatuses = Arrays.asList(regStatuses);

        if (accepted.isNull()) {

          if (rejected.isNull()) {

            if (expired.isNull() || (expired.isNotNull() && expired.asTimestamp().getTime()>=stamp.asTimestamp().getTime())) {

              if (locked.isNull()) {

                if ((begin.isNull() || (begin.isNotNull() && begin.asTimestamp().getTime()<=stamp.asTimestamp().getTime())) &&
                    (end.isNull() || (end.isNotNull() && end.asTimestamp().getTime()>=stamp.asTimestamp().getTime()))) {

                  status = StatusType.STARTED;

                } else if (begin.isNotNull() && begin.asTimestamp().getTime()>=stamp.asTimestamp().getTime()) {  

                  status = StatusType.PREPARED;

                } else status = StatusType.FINISHED;

              } else status = StatusType.DISABLED;

            } else status = StatusType.EXPIRED;

          } else status = StatusType.REJECTED;

        } else status = StatusType.ACCEPTED; 

        product.setStatus(status.name().toLowerCase());

        promotion.getProducts().add(product);

        regName = isEmpty(regName)?p.getName().asString():regName;
        regPhone = isEmpty(regPhone)?p.getPhone().asString():regPhone;
        regEmail = isEmpty(regEmail)?p.getEmail().asString():regEmail;
        regBrokerage = (!regBrokerage)?p.getBrokerage().asBoolean():regBrokerage;
        regYield = (!regYield)?p.getYield().asBoolean():regYield;

        if (activeStatuses.contains(status)) {

          registered = registered && !p.getName().isEmpty() && 
                                     !p.getPhone().isEmpty() &&
                                     !p.getEmail().isEmpty() &&
                                     p.getBrokerage().isNull() &&
                                     p.getYield().isNull();
        }
      }

      promotion.setRegistered(registered);

      if (!registered) {
        QRCodeResponse.PromotionResult.Promotion.Registration registration = promotion.getRegistration();
        registration.setName(regName);
        registration.setPhone(regPhone);
        registration.setEmail(regEmail);
        registration.setBrokerage(regBrokerage);
        registration.setYield(regYield);
      }

      ret = true;
    }      
    return ret;
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    QRCodeResponse response = new QRCodeResponse();
 
    Token token = getToken(response);
    
    if (isNotNull(token)) {
      
      String text = getPath().getParameterValue("text",(String)null);
      if (!isEmpty(text)) {
        
        if (Utils.isHttpUrl(text)) {
          
          if (!setPromotion(token,response,text)) {
            
            response.getRedirection().setUrl(text);
          }
        } else response.getMessage().setText(UnknownQRCode);
       
      } else throw new BaseResponseException(response,ErrorCodeLackOfParameters);
    }
    return response;
  }
}
