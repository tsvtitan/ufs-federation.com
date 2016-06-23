package ufsic.scheme.handlers.mobile;

import java.sql.Date;
import java.util.ArrayList;
import ufsic.providers.Dataset;
import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.Device;
import ufsic.scheme.mobile.MobileMenu;
import ufsic.scheme.mobile.MobileMenus;
import ufsic.scheme.Path;
import ufsic.scheme.Token;

public class GroupsHandler extends MobileMenuHandler {

  public GroupsHandler(Path path) {
    super(path);
  }
  
  protected class GroupsResponse extends BaseResponse {
   
    private ArrayList<Group> result = null;
            
    protected class Group {
    
      private String id = "";
      private String name = "";
      
      private ArrayList<Item> items = new ArrayList<>();
      
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
      
      protected class Item {
        
        private String id = "";
        private String name = "";
        private String date = "";
        private String neww = "";
        private String type = "";
        private String linkID = "";
        private String actual = "";
        
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
        
        public String getDate() {
          return date;
        }

        public void setDate(Date date) {

          Long temp = date.getTime() / 1000L;
          this.date = temp.toString();
        }
        
        public String getNew() {
          return neww;
        }

        public void setNew(String neww) {
          this.neww = !isEmpty(neww)?neww:"0";
        }
        
        public String getType() {
          return type;
        }

        public void setType(String type) {
          this.type = !isEmpty(type)?type:"0";
        }
        
        public String getLinkID() {
          return linkID;
        }

        public void setLinkID(String linkID) {
          this.linkID = isNotNull(linkID)?linkID:"";
        }
        
        public String getActual() {
          return actual;
        }

        public void setActual(String actual) {
          this.actual = !isEmpty(actual)?actual:"0";
        }
        
      }
      
      public ArrayList<Item> getItems() {
      
        return items;
      }

      public void setItems(ArrayList<Item> items) {

        this.items = items; 
      }
      
    }
    
    public ArrayList<Group> getResult() {
      
      if (isNull(result)) {
        result = new ArrayList<>();
      }
      return result;
    }
  
    public void setResult(ArrayList<Group> result) {
      
      this.result = result; 
    } 
  }
  
  private GroupsResponse.Group getParentGroup(ArrayList<GroupsResponse.Group> list, String id) {
  
    GroupsResponse.Group ret = null;
    for (GroupsResponse.Group item: list) {
      if (item.getId().equals(id)) {
        ret = item;
        break;
      }
    }
    return ret;
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    GroupsResponse response = new GroupsResponse();
 
    Token token = getToken(response);
    
    if (isNotNull(token)) {
      
      Value company = new Value(MobileMenus.Ufs);
      Device device = getDevice(token);
      if (isNotNull(device)) {
        company = device.getCompany();
      }
      
      boolean exists = false;
      
      Filter filter = new Filter();
      filter.And(MobileMenus.GroupsSql).IsNotNull();

      MobileMenu m = getMobileMenu(response,filter);
      if (isNotNull(m)) {
        
        String sql = m.getGroupsSql().asString();
        if (!isEmpty(sql)) {

          String lang = m.getLangId().asString();

          sql = sql.replace("$LANG",lang.toLowerCase())
                   .replace("$COMPANY",company.asString());

          Dataset<Record> ds = getWWWProvider().querySelect(sql);
          if (isNotNull(ds)) {

            exists = ds.size()>0;

            for (Record r: ds) {

              String id = r.getValue("id").asString();
              String name = r.getValue("name").asString();
              String parentId = r.getValue("parent_id").asString();

              GroupsResponse.Group parent = getParentGroup(response.getResult(),parentId);
              if (isNotNull(parent)) {

                GroupsResponse.Group.Item item = parent.new Item();

                item.setId(id);
                item.setName(name);
                item.setDate(r.getValue("date").asDate());
                item.setNew(r.getValue("new").asString());
                item.setType(r.getValue("type").asString());
                item.setLinkID(r.getValue("link_id").asString());
                item.setActual(r.getValue("actual").asString());

                parent.getItems().add(item);

              } else {

                GroupsResponse.Group group = response.new Group();

                group.setId(id);
                group.setName(name);

                response.getResult().add(group);
              }

            }
          }
        }
        
        if (!exists) {
          throw new BaseResponseException(response,ErrorCodeCategoryNotFound);
        }
        
      } else {
        throw new BaseResponseException(response,ErrorCodeLackOfParameters);
      }
    }
    
    return response;
  }
      
}
