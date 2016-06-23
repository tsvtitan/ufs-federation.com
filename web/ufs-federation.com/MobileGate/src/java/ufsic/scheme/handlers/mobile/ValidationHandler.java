package ufsic.scheme.handlers.mobile;

import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Token;
import ufsic.scheme.messages.EmailMessage;
import ufsic.scheme.messages.SmsMessage;
import ufsic.scheme.patterns.EmailValidationPattern;
import ufsic.scheme.patterns.PhoneValidationPattern;
import ufsic.utils.PasswordBuilder;
import ufsic.utils.Utils;

public class ValidationHandler extends TokenHandler {

  public ValidationHandler(Path path) {
    super(path);
  }
  
  protected class ValidationResponse extends BaseResponse {
   
    private Result result = null;
    
    protected class Result {
      
      private String code = "";
      
      public String getCode() {
        return code;
      }

      public void setCode(String code) {
        this.code = code;
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
    builder.append(String.format("<input name=\"text\" placeholder=\"email or phone\" value=\"%s\"/><br>",""));
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    ValidationResponse response = new ValidationResponse();
 
    Token token = getToken(response);
    if (isNotNull(token)) {
      
      String text = getPath().getParameterValue("text");
      if (!isEmpty(text)) {
        
        boolean success = false;
        Scheme scheme = getScheme();
        String code = new PasswordBuilder().digits(6).shuffle().build();
        
        if (Utils.isEmail(text)) {

          EmailMessage message = new EmailMessage(new EmailValidationPattern(scheme,code));
          success = message.send(text);
        
        } else if (Utils.isPhone(text)) {
          
          SmsMessage message = new SmsMessage(new PhoneValidationPattern(scheme,code));
          success = message.send(text);
        }
        
        if (success) {
          
          response.getResult().setCode(code);
          
        } else throw new BaseResponseException(response,ErrorCodeInternalError);
        
      } else throw new BaseResponseException(response,ErrorCodeLackOfParameters);
    }
    return response;
  }
  
}
