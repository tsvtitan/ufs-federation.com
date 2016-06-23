package ufsic.scheme.handlers.mobile;

import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Set;

import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.Accounts;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Subscription;
import ufsic.scheme.Subscriptions;

import ufsic.scheme.Token;
import ufsic.utils.Utils;

public class KeywordsHandler extends TokenHandler {

  private Subscriptions subscriptions = null;
  
  public KeywordsHandler(Path path) {
    super(path);
  }
  
  protected class KeywordsResponse extends BaseResponse {
   
    private Keywords result = null;
    
    protected class Keyword {
      
      /*private String started = "";
      private String finished = "";*/
      private String keyword = "";
      //private String kind = "";
      private Boolean email = false;
      private Boolean app= false;
      private Boolean sms = false;
      
      /*public String getStarted() {
        return started;
      }

      public void setStarted(Timestamp started) {
        
        Long temp = started.getTime() / 1000L;
        this.started = temp.toString();
      }
      
      public String getFinished() {
        return finished;
      }

      public void setFinished(Timestamp finished) {
        
        Long temp = finished.getTime() / 1000L;
        this.finished = temp.toString();
      }*/
      
      public String getKeyword() {
        return keyword;
      }

      public void setKeyword(String keyword) {
        this.keyword = isNotNull(keyword)?keyword:"";
      }
      
      /*public String getKind() {
        return kind;
      }

      public void setKind(String kind) {
        this.kind = isNotNull(kind)?kind:"";
      }*/
      
      public Boolean getEmail() {
        return email;
      }
      
      public void setEmail(Boolean email) {
        this.email = email;
      }
      
      public Boolean getApp() {
        return app;
      }
      
      public void setApp(Boolean app) {
        this.app = app;
      }
      
      public Boolean getSms() {
        return sms;
      }
      
      public void setSms(Boolean sms) {
        this.sms = sms;
      }
    }
    
    public class Keywords extends ArrayList<Keyword> {
      
      public Keyword findByKeyword(String word) {
        
        Keyword ret = null;
        for (Keyword keyword: this) {
          if (keyword.getKeyword().equalsIgnoreCase(word)) {
            ret = keyword;
            break;
          }
        }
        return ret;
      }
    }
            
    public Keywords getResult() {
      
      if (isNull(result)) {
        result = new Keywords();
      }
      return result;
    }
  
    public void setResult(Keywords result) {
      
      this.result = result; 
    }  
  }
  
  @Override
  protected void setTestHtml(StringBuilder builder) {
    
    super.setTestHtml(builder);
    builder.append(String.format("<input name=\"keyword\" placeholder=\"keyword #1\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"keyword\" placeholder=\"keyword #2\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"keyword\" placeholder=\"keyword #3\" value=\"%s\"/><br>",""));
    
    builder.append(String.format("<input type=\"checkbox\" name=\"app\" value=\"%s\"/>application<br>","on"));
    builder.append(String.format("<input name=\"email\" placeholder=\"email\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"phone\" placeholder=\"phone\" value=\"%s\"/><br>",""));
    
    builder.append(String.format("<input type=\"checkbox\" name=\"finish\" value=\"%s\"/>finish<br>","on"));
    builder.append(String.format("<input name=\"kind\" placeholder=\"kind #1\" value=\"%s\"/><br>","APP"));
    builder.append(String.format("<input name=\"kind\" placeholder=\"kind #2\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"kind\" placeholder=\"kind #3\" value=\"%s\"/><br>",""));

  }
  
  private Subscriptions getSubscriptions() {
    
    if (isNull(subscriptions)) {
      subscriptions = new Subscriptions(getScheme());
    }
    return subscriptions;
  }

  private void finishKeywords(Account account, String[] keywordArr, String[] typeArr) {
    
    Map<String,Set<Subscription.DeliveryType>> keywords = new HashMap<>();
    for (String keyword: keywordArr) {
      
      if (!isEmpty(keyword)) {
        
        Set<Subscription.DeliveryType> set = new HashSet<>();
        if (typeArr.length>0) {
          
          for (String type: typeArr) {
            
            if (!isEmpty(type)) {
              
              type = type.toUpperCase();
              try {
                Subscription.DeliveryType t = Subscription.DeliveryType.valueOf(type);
                if (!set.contains(t)) {
                  set.add(t);
                }
              } catch (Exception e) {
                logException(e);
              }  
            }
          }
        }  
        if (set.isEmpty()) {  
          set.addAll(Arrays.asList(Subscription.DeliveryType.values()));
        }
        keywords.put(keyword,set);
      }
    }
    
    if (!keywords.isEmpty()) {
      
      Scheme scheme = getScheme();
      Value stamp = scheme.getStamp();
      
      for (Entry<String,Set<Subscription.DeliveryType>> entry: keywords.entrySet()) {
        
        Subscription sub = new Subscription(getSubscriptions());
        sub.setFinished(stamp);
        
        for (Subscription.DeliveryType type: entry.getValue()) {
          
          GroupFilter filter = new GroupFilter();
          filter.And(Subscriptions.AccountId,account.getAccountId());
          filter.And(Subscriptions.LangId,scheme.getLangId());
          filter.And(Subscriptions.Keyword,entry.getKey());
          filter.And(Subscriptions.DeliveryType,type.name());

          GroupFilter f1 = new GroupFilter();
          f1.Or(new Filter().And(Subscriptions.Started).IsNull());
          f1.Or(new Filter().Add(Subscriptions.Started).IsNotNull().And(Subscriptions.Started).LessOrEqual(stamp));
          filter.And(f1);

          GroupFilter f2 = new GroupFilter();
          f2.Or(new Filter().And(Subscriptions.Finished).IsNull());
          f2.Or(new Filter().Add(Subscriptions.Finished).IsNotNull().And(Subscriptions.Finished).Greater(stamp));
          filter.And(f2);

          sub.update(filter);
        }        
      }
    }
  }
  
  private void createKeywords(Account account, String[] keywordArr) {
    
    Path path = getPath();
    
    String app = path.getParameterValue("app");
    boolean appOn = !isEmpty(app) && app.toLowerCase().equals("on");

    String email = path.getParameterValue("email");
    if (isNotNull(email)) email = email.trim();
    boolean emailValid = Utils.isEmail(email);

    String phone = path.getParameterValue("phone");
    if (isNotNull(phone)) phone = phone.trim();
    boolean phoneValid = Utils.isPhone(phone);
    
    if (appOn || emailValid || phoneValid) {

      Scheme scheme = getScheme();

      if (emailValid || phoneValid) {

        Account a = new Account(scheme.getAccounts());
        String oldEmail = account.getEmail().asString();
        if (emailValid && (!Utils.isEmail(oldEmail) || !email.equalsIgnoreCase(oldEmail))) {
          a.setEmail(email);
        }
        String oldPhone = account.getPhone().asString();
        if (phoneValid && (!Utils.isPhone(oldPhone) || !phone.equalsIgnoreCase(oldPhone))) {
          a.setPhone(phone);
        }
        if (!a.isEmpty()) {
          a.update(new Filter(Accounts.AccountId,account.getAccountId()));
        }
      }

      List<String> keywords = new ArrayList<>(); 
      for (String keyword: keywordArr) {
        if (!isEmpty(keyword)) {
          keywords.add(keyword);
        }
      }

      if (!keywords.isEmpty()) {
        
        Set<String> types = new HashSet<>();
        if (appOn) types.add(Subscription.DeliveryType.APP.name());
        if (emailValid) types.add(Subscription.DeliveryType.EMAIL.name());
        if (phoneValid) types.add(Subscription.DeliveryType.SMS.name());
        finishKeywords(account,keywords.toArray(new String[]{}),types.toArray(new String[]{}));

        Value stamp = scheme.getStamp();
        Subscriptions subs = getSubscriptions();

        for (String keyword: keywords) {

          for (Subscription.DeliveryType type: Subscription.DeliveryType.values()) {

            if ((appOn && type==Subscription.DeliveryType.APP) ||
                (emailValid && type==Subscription.DeliveryType.EMAIL) ||
                (phoneValid && type==Subscription.DeliveryType.EMAIL)) {    

              Value subscriptionId = getProvider().getUniqueId();

              Subscription sub = new Subscription(subs);
              sub.setSubscriptionId(subscriptionId);
              sub.setAccountId(account.getAccountId());
              sub.setLangId(scheme.getLangId());
              sub.setStarted(stamp);
              sub.setDeliveryType(type.name());
              sub.setKeyword(keyword);
              sub.insert();
            }
          }
        }
      }
    }
  }
  
  private void tryKeywords(Account account) {
    
    Path path = getPath();
    
    String[] arr = path.getParameterValues("keyword");
    if (arr.length>0) {
      
      String s = path.getParameterValue("finish");
      boolean finish = !isEmpty(s) && s.toLowerCase().equals("on");
      
      if (finish) {
        finishKeywords(account,arr,path.getParameterValues("kind"));
      } else {
        createKeywords(account,arr);
      }
    }
  } 
          
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    KeywordsResponse response = new KeywordsResponse();
 
    Token token = getToken(response);
    
    if (isNotNull(token)) {
      
      Account account = getAccount(token);
      if (isNotNull(account)) {
 
        tryKeywords(account);
        
        Subscriptions subs = getSubscriptions(account,null);
        if (isNotNull(subs)) {
         
          KeywordsResponse.Keywords keywords = response.getResult();
          
          for (Subscription sub: subs) {
            
            String word = sub.getKeyword().asString();
            KeywordsResponse.Keyword keyword = keywords.findByKeyword(word);
            
            if (isNull(keyword)) {
              keyword = response.new Keyword();
              keyword.setKeyword(word);
              keywords.add(keyword);
            }
                    
            Subscription.DeliveryType dt = Subscription.DeliveryType.valueOf(sub.getDeliveryType().asString());
            switch (dt) {
              case APP: {
                keyword.setApp(true);
                break;
              } 
              case EMAIL: {
                keyword.setEmail(true);
                break;
              }
              case SMS: {
                keyword.setSms(true);
                break;
              }
            } 
            
          }
        }       
      }
    }
    return response;
  }
}
