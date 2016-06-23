package ufsic.scheme.handlers.mobile;

import java.util.ArrayList;
import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.scheme.Comm;
import ufsic.scheme.mobile.MobileMenu;
import ufsic.scheme.mobile.MobileMenus;
import ufsic.scheme.Path;
import ufsic.scheme.Token;

public class HtmlHandler extends MobileMenuHandler {

  public HtmlHandler(Path path) {
    super(path);
  }
  
  protected class HtmlsResponse extends BaseResponse {
    
    private ArrayList<Html> result = null;
    
    protected class Html {
    
      private String categoryID = "";
      private String subcategoryID = "";
      private String html = "";
      
      public String getCategoryID() {
        return categoryID;
      }

      public void setCategoryID(String categoryID) {
        this.categoryID = isNotNull(categoryID)?categoryID:"";
      }
      
      public String getSubcategoryID() {
        return subcategoryID;
      }

      public void setSubcategoryID(String subcategoryID) {
        this.subcategoryID = isNotNull(subcategoryID)?subcategoryID:"";
      }
      
      public String getHtml() {
        return html;
      }

      public void setHtml(String html) {
        this.html = isNotNull(html)?html:"";
      }
      
    }
    
    public ArrayList<Html> getResult() {
      
      if (isNull(result)) {
        result = new ArrayList<>();
      }
      return result;
    }
  
    public void setResult(ArrayList<Html> result) {
      
      this.result = result; 
    } 
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    HtmlsResponse response = new HtmlsResponse();
 
    Token token = getToken(response);
    
    if (isNotNull(token)) {
      
      Filter filter = new Filter();
      filter.Add(MobileMenus.Html).IsNotNull();
        
      MobileMenus menus = getMobileMenus(response,filter);

      if (isNotNull(menus)) {

        for (Record r: menus) {

          MobileMenu m = (MobileMenu)r;

          HtmlsResponse.Html html = response.new Html();

          if (m.getParentId().isNull()) {
            html.setCategoryID(m.getMobileMenuId().asString());
            html.setSubcategoryID("");
          } else {
            html.setCategoryID(m.getParentId().asString());
            html.setSubcategoryID(m.getMobileMenuId().asString());
          }
          html.setHtml(m.getHtml().asString());

          response.getResult().add(html);
        }

      } else {
        throw new BaseResponseException(response,ErrorCodeCategoryNotFound);
      }
    }
    return response;
  }
  
}
