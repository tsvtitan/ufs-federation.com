package ufsic.scheme.handlers.mobile;

import com.fasterxml.jackson.annotation.JsonInclude;
import com.fasterxml.jackson.annotation.JsonInclude.Include;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Set;
import org.apache.commons.lang3.StringUtils;
import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Provider;

import ufsic.providers.Value;
import ufsic.scheme.Device;

import ufsic.scheme.mobile.MobileActivities;
import ufsic.scheme.mobile.MobileMenu;
import ufsic.scheme.mobile.MobileMenus;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Token;

public class CategoriesHandler extends TokenHandler {

  public CategoriesHandler(Path path) {
    super(path);
  }
  
  protected class CategoriesResponse extends BaseResponse {
   
    private ArrayList<Category> result = null;
      
    @JsonInclude(Include.NON_NULL)
    protected class Category {
    
      private String id = "";
      private String title = "";
      private String type = "";
      private String expired = "";
      private String imgURL = null;
      private String h_imgURL = null;
      private String allNewsCount = null;
      private String actualNewsCount = null;
      private String allActivityCount = null;
      private ArrayList<Category> subcategories = null;
      
      public String getId() {
        return id;
      }

      public void setId(String id) {
        this.id = isNotNull(id)?id:"";
      }

      public String getTitle() {
        return title;
      }

      public void setTitle(String title) {
        this.title = isNotNull(title)?title:"";
      }
      
      public String getType() {
        return type;
      }

      public void setType(String type) {
        this.type = isNotNull(type)?type:"";
      }
      
      public String getExpired() {
        return expired;
      }

      public void setExpired(Timestamp expired) {
        
        Long temp = expired.getTime() / 1000L;
        this.expired = temp.toString();
      }
      
      public String getImgURL() {
        return imgURL;
      }

      public void setImgURL(String imgURL) {
        this.imgURL = isNotNull(imgURL)?imgURL:"";
      }
      
      public String getH_imgURL() {
        return h_imgURL;
      }

      public void setH_imgURL(String h_imgURL) {
        this.h_imgURL = isNotNull(h_imgURL)?h_imgURL:"";
      }
      
      public String getAllNewsCount() {
        return allNewsCount;
      }

      public void setAllNewsCount(String allNewsCount) {
        this.allNewsCount = isNotNull(allNewsCount)?allNewsCount:"";
      }
      
      public String getActualNewsCount() {
        return actualNewsCount;
      }

      public void setActualNewsCount(String actualNewsCount) {
        this.actualNewsCount = isNotNull(actualNewsCount)?actualNewsCount:"";
      }
      
      public String getAllActivityCount() {
        return allActivityCount;
      }

      public void setAllActivityCount(String allActivityCount) {
        this.allActivityCount = isNotNull(allActivityCount)?allActivityCount:"";
      }
      
      public ArrayList<Category> getSubcategories() {
        
        if (isNull(subcategories)) {
          subcategories = new ArrayList<>();
        }
        return subcategories;
      }
      
      public void setSubcategories(ArrayList<Category> subcategories) {
      
        this.subcategories = subcategories; 
      }
      
      public boolean subcategoriesExist() {
        
        return isNotNull(subcategories);
      }
      
    }
    
    public ArrayList<Category> getResult() {
      
      if (isNull(result)) {
        result = new ArrayList<>();
      }
      return result;
    }
  
    public void setResult(ArrayList<Category> result) {
      
      this.result = result; 
    }   
  }
  
  private CategoriesResponse.Category getParentCategory(ArrayList<CategoriesResponse.Category> list, String mobileMenuId) {
  
    CategoriesResponse.Category ret = null;
    for (CategoriesResponse.Category item: list) {
      if (item.getId().equals(mobileMenuId)) {
        ret = item;
        break;
      }
      if (isNull(ret) && item.subcategoriesExist()) {
        ret = getParentCategory(item.getSubcategories(),mobileMenuId);
        if (isNotNull(ret)) {
          break;
        }
      }
    }
    return ret;
  }
  
  private String getMobileMenuLocation(String menuId, String fieldData) {
    
    return FilesHandler.getLocation(MobileMenus.TableName,MobileMenus.MobileMenuId,menuId,fieldData);
  }
  
  private Integer getNewsCountBySqlFromWWW(String newsSql, String newsCountSql, String mobileMenuId, 
                                             String parentId, String lang, String keywords, 
                                             String likeKeywords, String company) {
    
    Integer ret = 0;
    if (!isEmpty(newsSql) && !isEmpty(newsCountSql)) {
      
      Provider p = getWWWProvider();
      Scheme scheme = getScheme();
      
      parentId = (isNotNull(parentId) && !parentId.equals(""))?parentId:"null";
      
      String sql = newsSql.replace("$LANG",lang.toLowerCase())
                          .replace("$MOBILE_MENU_ID",mobileMenuId)
                          .replace("$PARENT_ID",parentId)
                          .replace("$KEYWORDS",keywords)
                          .replace("$LIKE_KEYWORDS",likeKeywords)
                          .replace("$COMPANY",company);
      
      String bs = p.quote(formatForWWWDateTime(new Timestamp(scheme.getStamp().addYears(-1).getTime())));
      String es = p.quote(formatForWWWDateTime(scheme.getStamp().asTimestamp()));
      
      sql = String.format("select t.* from (%s) t "+
                           "where t.date>=%s and t.date<=%s",
                          sql,bs,es); 
      sql = newsCountSql.replace("$SQL",sql);
      
      Value v = p.getFirstValue(p.getFirstRecord(p.querySelect(sql)));
      ret = v.asInteger();
    }
    return ret;
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    CategoriesResponse reply = new CategoriesResponse();
 
    Token token = getToken(reply);
    
    if (isNotNull(token)) {
      
      Scheme scheme = getScheme();
      MobileMenus menus = new MobileMenus(scheme);
      
      Filter filter = new Filter();
      filter.And(MobileMenus.Locked).IsNull();
      filter.And(MobileMenus.LangId,scheme.getLangId());

      Value company = new Value(MobileMenus.Ufs);
      Device device = getDevice(token);
      if (isNotNull(device)) {
        company = device.getCompany();
        if (company.same(MobileMenus.Ufs)) {
          filter.And(MobileMenus.Ufs,1);
        }
        if (company.same(MobileMenus.Premier)) {
          filter.And(MobileMenus.Premier,1);
        }
      }
      
      if (menus.open(filter,new Orders(MobileMenus.Level,MobileMenus.Priority))) {

        Set<String> kwords = getKeywords(token);
        String keywords = StringUtils.join(getSet(kwords,"'%s'"),",");
        String likeKeywords = StringUtils.join(getSet(kwords,"text like '%","%s","%'")," or ");
        
        for (MobileMenu m: menus) {

          CategoriesResponse.Category category;

          Timestamp exp;
          Value expired = m.getExpired();
          if (expired.isNotNull()) {
            exp = expired.asTimestamp();
          } else {
            exp = scheme.getStamp().addMonths(1);
          }
          
          String menuId = m.getMobileMenuId().asString();
          
          category = reply.new Category();
          category.setId(menuId);
          category.setTitle(m.getName().asString());
          category.setType(m.getMenuType().asString());
          category.setExpired(exp);

          Value level = m.getLevel();
          if (level.asInteger()>1) {

            Value defaultImage = m.getDefaultImage();
            if (defaultImage.isNotNull() && defaultImage.length()>0) {
              
              category.setImgURL(getFileUrl(token,getMobileMenuLocation(menuId,MobileMenus.DefaultImage),null,"png",defaultImage));
            }
            
            Value highlightImage = m.getHighlightImage();
            if (highlightImage.isNotNull() && highlightImage.length()>0) {
              
              category.setH_imgURL(getFileUrl(token,getMobileMenuLocation(menuId,MobileMenus.HighlightImage),null,"png",highlightImage));
            }
          }

          String lang = m.getLangId().asString();
          Value menuType = m.getMenuType();
          
          switch (menuType.asInteger()) {
            case 1: {

              String newsSql = m.getNewsSql().asString();
              String parentId = m.getParentId().asString();

              Integer count; 
                      
              count = getNewsCountBySqlFromWWW(newsSql,m.getNewsAllCountSql().asString(),menuId,parentId,lang,keywords,likeKeywords,company.asString());
              category.setAllNewsCount(count.toString());
              
              count = getNewsCountBySqlFromWWW(newsSql,m.getNewsActualCountSql().asString(),menuId,parentId,lang,keywords,likeKeywords,company.asString());
              category.setActualNewsCount(count.toString());
              
              break;
            }
            case 2: {

              Filter fl = new Filter();
              fl.Add(MobileActivities.Locked).IsNull();
              fl.And(MobileActivities.Image).IsNotNull();
              fl.And(MobileActivities.LangId,lang);
              
              Value count = getProvider().count(getMobileActivities().getViewName(),fl);
              category.setActualNewsCount(count.asString());
              
              break;
            }
          }

          CategoriesResponse.Category parent = getParentCategory(reply.getResult(),m.getParentId().asString());
          if (isNotNull(parent)) {
            parent.getSubcategories().add(category);
          } else {
            reply.getResult().add(category);
          }
        }

      } else throw new BaseResponseException(reply,ErrorCodeCategoryNotFound);
    }
    return reply;
  }

}
