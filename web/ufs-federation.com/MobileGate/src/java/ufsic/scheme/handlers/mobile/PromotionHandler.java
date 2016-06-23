package ufsic.scheme.handlers.mobile;

import java.util.Arrays;
import java.util.List;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;

import ufsic.scheme.Path;
import ufsic.scheme.mobile.Promotion;
import ufsic.scheme.mobile.Promotions;
import ufsic.scheme.Scheme;
import ufsic.scheme.Token;
import ufsic.scheme.handlers.mobile.QRCodeHandler.StatusType;
import ufsic.scheme.messages.EmailMessage;
import ufsic.scheme.patterns.EmailPromotionPattern;
import ufsic.utils.Utils;

public class PromotionHandler extends TokenHandler {

  final static protected Integer ErrorCodePromotionNotFound = 601;
  
  public PromotionHandler(Path path) {
    super(path);
  }
  
  protected class PromotionResponse extends BaseResponse {
   
    private Result result = null;
    
    protected class Result {
      
      private String status = "";
      private String publisher = "";
      
      public String getStatus() {
        return status;
      }

      public void setStatus(String status) {
        this.status = isNotNull(status)?status:"";
      }
      
      public String getPublisher() {
        return publisher;
      }

      public void setPublisher(String publisher) {
        this.publisher = isNotNull(publisher)?publisher:"";
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
    
    super.setTestHtml(builder);
    builder.append(String.format("<input name=\"promotionID\" placeholder=\"promotionID\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"accepted\" placeholder=\"accepted\" value=\"%s\"/><br>","false"));
    builder.append(String.format("<input name=\"name\" placeholder=\"name\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"phone\" placeholder=\"phone\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"email\" placeholder=\"email\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"brokerage\" placeholder=\"brokerage\" value=\"%s\"/><br>","false"));
    builder.append(String.format("<input name=\"yield\" placeholder=\"yield\" value=\"%s\"/><br>","false"));
  }
  
  private boolean notifyTo(Scheme scheme, String name, String phone, String email, boolean brokerage, boolean yield) {
    
    boolean ret = false;
    
    String to = scheme.getOption("PromotionHandler.Email");
    if (Utils.isEmail(to)) {
      
      EmailPromotionPattern pattern = new EmailPromotionPattern(getScheme());
      pattern.setName(name);
      pattern.setPhone(phone);
      pattern.setEmail(email);
      pattern.setBrokerage(brokerage);
      pattern.setYield(yield);
      
      EmailMessage message = new EmailMessage(pattern);
      
      ret = message.send(to);
    }
    return ret;
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    PromotionResponse response = new PromotionResponse();
 
    Token token = getToken(response);
    
    if (isNotNull(token)) {
      
      Path path = getPath();
      
      String promotionId = path.getParameterValue("promotionID",(String)null);
      String accepted = path.getParameterValue("accepted",(String)null);
      String name = path.getParameterValue("name",(String)null);
      String phone = path.getParameterValue("phone",(String)null);
      String email = path.getParameterValue("email",(String)null);
      String brokerage = path.getParameterValue("brokerage",(String)null);
      String yield = path.getParameterValue("yield",(String)null);
      
      if (!isEmpty(promotionId) && !isEmpty(accepted) && !isEmpty(name) && !isEmpty(phone) && !isEmpty(email)) {
        
        Scheme scheme = getScheme();
        
        Promotions promotions = new Promotions(scheme);
        
        GroupFilter filter = new GroupFilter();
        filter.And(Promotions.PromotionId,promotionId);
        filter.And(Promotions.Accepted).IsNull();
        filter.And(Promotions.Rejected).IsNull();
        
        GroupFilter gf = new GroupFilter();
        gf.Or(new Filter().Add(Promotions.Expired).IsNull());
        gf.Or(new Filter().And(Promotions.Expired).IsNotNull().And(Promotions.Expired).GreaterOrEqual(scheme.getStamp()));
        filter.And(gf);
        
        Promotion promotion = promotions.first(filter,new Orders().AddDesc(Promotions.Created));
        if (isNotNull(promotion)) {
          
          PromotionResponse.Result result = response.getResult();
          
          List<String> booleanList = Arrays.asList(new String[] {"1","yes","ok","true"});
          boolean acceptedOrRejected = booleanList.contains(accepted.toLowerCase());
          
          Promotion p = new Promotion(promotions);
          p.setAccepted(acceptedOrRejected?scheme.getStamp():null);
          p.setRejected(acceptedOrRejected?null:scheme.getStamp());
          p.setName(name);
          p.setPhone(phone);
          p.setEmail(email);
          
          boolean bBrokerage = booleanList.contains(brokerage.toLowerCase());
          p.setBrokerage(bBrokerage);
          
          boolean bYield = booleanList.contains(yield.toLowerCase());
          p.setYield(bYield);
          
          if (p.update(new Filter(Promotions.PromotionId,promotion.getPromotionId()))) {
            
            notifyTo(scheme,name,phone,email,bBrokerage,bYield);
            
            StatusType status = acceptedOrRejected?StatusType.ACCEPTED:StatusType.REJECTED;
            result.setStatus(status.name().toLowerCase());
            result.setPublisher(String.format("%s [%s. %s]",
                                              status.name().toLowerCase(),
                                              promotion.getCompanyName().asString(),
                                              promotion.getTypeName().asString()));
            
            
          }
          
        } else throw new BaseResponseException(response,ErrorCodePromotionNotFound);
        
      } else throw new BaseResponseException(response,ErrorCodeLackOfParameters);
    }
    return response;
  }
  
}
