package ufsic.scheme.handlers.mobile;

import java.sql.Timestamp;
import java.util.ArrayList;
import ufsic.providers.Filter;
import ufsic.providers.Orders;

import ufsic.providers.Value;
import ufsic.scheme.Device;
import ufsic.scheme.mobile.MobileActivities;
import ufsic.scheme.mobile.MobileActivity;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Token;

public class ActivitiesHandler extends TokenHandler {

  final private Integer ErrorCodeActivitiesNotFound = 401;
  
  public ActivitiesHandler(Path path) {
    super(path);
  }
  
  protected class ActivitiesResponse extends BaseResponse {
    
    private ArrayList<Activity> result = null;
    
    protected class Activity {
    
      private String id = "";
      private String name = "";
      private String expired = "";
      private String mainImg = "";
      private String url = "";
      private String text = "";
      
      public String getId() {
        return id;
      }

      public void setId(String id) {
        this.id = isNotNull(id)?id:"";
      }
      
      public String getName() {
        return name;
      }

      public void setName(String name) {
        this.name = isNotNull(name)?name:"";
      }
      
      public String getExpired() {
        return expired;
      }

      public void setExpired(Timestamp expired) {
        
        Long temp = expired.getTime() / 1000L;
        this.expired = temp.toString();
      }
      
      public String getMainImg() {
        return mainImg;
      }

      public void setMainImg(String mainImg) {
        this.mainImg = isNotNull(mainImg)?mainImg:"";
      }
      
      public String getUrl() {
        return url;
      }

      public void setUrl(String url) {
        this.url = isNotNull(url)?url:"";
      }
      
      public String getText() {
        return text;
      }

      public void setText(String text) {
        this.text = isNotNull(text)?text:"";
      }
      
    }
    
    public ArrayList<Activity> getResult() {
      
      if (isNull(result)) {
        result = new ArrayList<>();
      }
      return result;
    }
  
    public void setResult(ArrayList<Activity> result) {
      
      this.result = result; 
    } 
  }
  
  @Override
  protected void setTestHtml(StringBuilder builder) {
  
    super.setTestHtml(builder);
    builder.append(String.format("<input name=\"categoryID\" placeholder=\"categoryID\" value=\"%s\"/><br>","18"));
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
          
    ActivitiesResponse response = new ActivitiesResponse();
 
    Token token = getToken(response);
    
    if (isNotNull(token)) {
      
      Scheme scheme = getScheme();
      MobileActivities activities = new MobileActivities(scheme);
      
      Filter filter = new Filter();
      filter.Add(MobileActivities.Locked).IsNull();
      filter.And(MobileActivities.Image).IsNotNull();
      filter.And(MobileActivities.LangId,scheme.getLangId());
      
      Device device = getDevice(token);
      if (isNotNull(device)) {
        Value company = device.getCompany();
        if (company.same(MobileActivities.Ufs)) {
          filter.And(MobileActivities.Ufs,1);
        }
        if (company.same(MobileActivities.Premier)) {
          filter.And(MobileActivities.Premier,1);
        }
      }
      
      if (activities.open(filter,new Orders(MobileActivities.Created,MobileActivities.Priority))) {

        for (MobileActivity a: activities) {

          ActivitiesResponse.Activity activity;
          
          String activityId = a.getMobileActivityId().asString();
          
          activity = response.new Activity();
          activity.setId(activityId);
          activity.setName(a.getName().asString());
          activity.setExpired(scheme.getStamp().addMonths(1));
          
          Value image = a.getImage();
          if (image.isNotNull() && image.length()>0) {
            
            String location = FilesHandler.getLocation(MobileActivities.TableName,MobileActivities.MobileActivityId,activityId,MobileActivities.Image);
            activity.setMainImg(getFileUrl(token,location,null,"png",image));
          }
          
          activity.setUrl(a.getUrl().asString());
          activity.setText(a.getHtml().asString());
          
          response.getResult().add(activity);
        }
      } else {
        throw new BaseResponseException(response,ErrorCodeActivitiesNotFound);
      }
    }
    return response;
  }
  
}
