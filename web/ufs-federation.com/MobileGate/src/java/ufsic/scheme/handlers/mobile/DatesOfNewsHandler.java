package ufsic.scheme.handlers.mobile;

import java.util.ArrayList;
import ufsic.providers.Dataset;
import ufsic.providers.Filter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.mobile.MobileMenu;
import ufsic.scheme.mobile.MobileMenus;
import ufsic.scheme.Path;
import ufsic.scheme.Token;
import ufsic.utils.Utils;

public class DatesOfNewsHandler extends MobileMenuHandler {

  public DatesOfNewsHandler(Path path) {
    super(path);
  }
  
  protected class DatesOfNewsResponse extends BaseResponse {
   
    private ArrayList<Long> result = null;
    
    public ArrayList<Long> getResult() {
      
      if (isNull(result)) {
        result = new ArrayList<>();
      }
      return result;
    }
  
    public void setResult(ArrayList<Long> result) {
      
      this.result = result; 
    }   
  }
  
  private void setResponseBySqlFromWWW(DatesOfNewsResponse reply, String sql) {
    
    if (!isEmpty(sql)) {
      
      Provider p = getWWWProvider();
      
      sql = String.format("select cast(t.date as date) as date, count(*) as cnt "+
                            "from (%s) t "+
                           "group by 1 " +
                           "order by t.date desc",
                          sql); 
      Dataset<Record> ds = p.querySelect(sql);
      if (isNotNull(ds)) {
        
        for (Record r: ds) {
          
          Value date = r.getValue("date");
          if (date.isNotNull()) {
            
            Long temp = date.asDate().getTime() / 1000L;
            reply.getResult().add(temp);
          }
        }
      }
    }
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    DatesOfNewsResponse reply = new DatesOfNewsResponse();
 
    Token token = getToken(reply);
    
    if (isNotNull(token)) {
      
      Filter filter = new Filter();
      filter.Add(MobileMenus.NewsSql).IsNotNull();
      
      MobileMenus menus = getMobileMenus(reply,filter);

      if (isNotNull(menus)) {

        StringBuilder sqlBuilder = new StringBuilder();
        
        for (Record r: menus) {

          MobileMenu m = (MobileMenu)r;
          
          String lang = m.getLangId().asString().toLowerCase();
          String mmid = m.getMobileMenuId().asString();
          
          Value pid = m.getParentId();
          String pids = (pid.isNotNull() && !pid.same(""))?pid.asString():"null";
          
          String sql = m.getNewsSql().asString().replace("$LANG",lang).replace("$MOBILE_MENU_ID",mmid).replace("$PARENT_ID",pids);
          if (!isEmpty(sql)) {
            
            if (sqlBuilder.length()>0) {
              sqlBuilder.append(Utils.getLineSeparator()).append("union all").append(Utils.getLineSeparator());
            }
            sqlBuilder.append(sql);
          }
        }
        
        setResponseBySqlFromWWW(reply,sqlBuilder.toString().trim());
        
      } else {
        throw new BaseResponseException(reply,ErrorCodeCategoryNotFound);
      }
    }

    return reply;
  }
  
}
