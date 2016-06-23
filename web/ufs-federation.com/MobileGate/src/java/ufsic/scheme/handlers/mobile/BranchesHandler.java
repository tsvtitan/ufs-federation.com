package ufsic.scheme.handlers.mobile;

import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.Properties;
import ufsic.providers.Record;
import ufsic.scheme.Branches;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Token;

public class BranchesHandler extends TokenHandler {

  final private Integer ErrorCodeBranchesNotFound = 501;
  
  public BranchesHandler(Path path) {
    super(path);
  }
  
  protected class BranchesResponse extends BaseResponse {
    
    private ArrayList<Branch> result = null;
    
    protected class Branch {
    
      private String id = "";
      private String name = "";
      private String region = "";
      private String city = "";
      private String address = "";
      private String latitude = "";
      private String longitude = "";
      private String expired = "";
      private ArrayList<Contact> contacts = null;
      
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
      
      public String getRegion() {
        return region;
      }

      public void setRegion(String region) {
        this.region = isNotNull(region)?region:"";
      }
      
      public String getCity() {
        return city;
      }

      public void setCity(String city) {
        this.city = isNotNull(city)?city:"";
      }
      
      public String getAddress() {
        return address;
      }

      public void setAddress(String address) {
        this.address = isNotNull(address)?address:"";
      }
      
      public String getLongitude() {
        return longitude;
      }

      public void setLongitude(String longitude) {
        this.longitude = isNotNull(longitude)?longitude:"";
      }
      
      public String getLatitude() {
        return latitude;
      }

      public void setLatitude(String latitude) {
        this.latitude = isNotNull(latitude)?latitude:"";
      }
      
      public String getExpired() {
        return expired;
      }

      public void setExpired(Timestamp expired) {
        
        Long temp = expired.getTime() / 1000L;
        this.expired = temp.toString();
      }
      
      protected class Contact {
    
        private String title = "";
        private String value = "";
        
        public String getTitle() {
          return title;
        }

        public void setTitle(String title) {
          this.title = isNotNull(title)?title:"";
        }
        
        public String getValue() {
          return value;
        }

        public void setValue(String value) {
          this.value = isNotNull(value)?value:"";
        }
      } 
      
      public ArrayList<Contact> getContacts() {
      
      if (isNull(contacts)) {
          contacts = new ArrayList<>();
        }
        return contacts;
      }

      public void setContacts(ArrayList<Contact> contacts) {

        this.contacts = contacts; 
      }
    }
    
    public ArrayList<Branch> getResult() {
      
      if (isNull(result)) {
        result = new ArrayList<>();
      }
      return result;
    }
  
    public void setResult(ArrayList<Branch> result) {
      
      this.result = result; 
    } 
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    BranchesResponse response = new BranchesResponse();
 
    Token token = getToken(response);
    
    if (isNotNull(token)) {
      
      Scheme scheme = getScheme();
      Branches branches = scheme.getBranches();
      
      if (!branches.isEmpty()) {
        
        for (Record r: branches) {

          ufsic.scheme.Branch b = (ufsic.scheme.Branch)r;
          
          BranchesResponse.Branch branch;
          
          branch = response.new Branch();
          
          branch.setId(b.getId().asString());
          branch.setName(b.getName().asString());
          branch.setRegion(b.getRegion().asString());
          branch.setCity(b.getCity().asString());
          branch.setAddress(b.getAddress().asString());
          branch.setLatitude(b.getLatitude().asString());
          branch.setLongitude(b.getLongitude().asString());
          branch.setExpired(scheme.getStamp().addMonths(1));
          
          Properties contacts = b.getContatcts().asProperties();
          Enumeration list = contacts.propertyNames();
          while (list.hasMoreElements()) {
            
            String name = (String)list.nextElement();
            BranchesResponse.Branch.Contact contact = branch.new Contact(); 
            contact.setTitle(name);
            contact.setValue(contacts.getProperty(name));
            
            branch.getContacts().add(contact);
          }
          
          response.getResult().add(branch);
        }
        
      } else {
        throw new BaseResponseException(response,ErrorCodeBranchesNotFound);
      }
    }
    return response;
  }
  
}