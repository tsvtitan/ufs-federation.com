package ufsic.scheme.handlers.mobile;

import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import ufsic.providers.Dataset;
import ufsic.providers.Field;
import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Provider;
import ufsic.providers.Record;

import ufsic.scheme.mobile.MobileTable;
import ufsic.scheme.mobile.MobileTables;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Token;
import ufsic.utils.Utils;

public class TableViewHandler extends TokenHandler {

  public TableViewHandler(Path path) {
    super(path);
  }
  
  protected class TableViewResponse extends BaseResponse {
   
    private Result result = null;
            
    protected class Result {
    
      private String subcategoryID = "";
      private About about = new About();
      
      private ArrayList<Table> tables = new ArrayList<>();
      
      public String getSubcategoryID() {
        return subcategoryID;
      }

      public void setSubcategoryID(String subcategoryID) {
        this.subcategoryID = isNotNull(subcategoryID)?subcategoryID:"";
      }

      protected class About {
        
        private String description = "";
        
        public String getDescription() {
          return description;
        }

        public void setDescription(String description) {
          this.description = isNotNull(description)?description:"";
        }
      }
      
      public About getAbout() {
        return about;
      }
      
      public void setAbout(About about) {
        this.about = about;
      }
  
      protected class Table {
        
        private String name = "";
        private String about = "";
        private String expired = "";
        
        private ArrayList<String> columns = new ArrayList<>();
        private ArrayList<String> alignments = new ArrayList<>();
        private ArrayList<String> buyUrls = new ArrayList<>();
        private ArrayList<Value> values = new ArrayList<>();
        
        public String getName() {
          return name;
        }

        public void setName(String name) {
          this.name = isNotNull(name)?name:"";
        }
        
        public String getAbout() {
          return about;
        }

        public void setAbout(String about) {
          this.about = isNotNull(about)?about:"";
        }
        
        public String getExpired() {
          return expired;
        }

        public void setExpired(Timestamp expired) {

          Long temp = expired.getTime() / 1000L;
          this.expired = temp.toString();
        }
        
        public ArrayList<String> getColumns() {
          return columns;
        }
        
        public void setColumns(ArrayList<String> columns) {
          this.columns = columns;
        }
       
        public ArrayList<String> getAlignments() {
          return alignments;
        }
        
        public void setAlignments(ArrayList<String> alignments) {
          this.alignments = alignments;
        }
        
        public ArrayList<String> getBuyUrls() {
          return buyUrls;
        }
        
        public void setBuyUrls(ArrayList<String> buyUrls) {
          this.buyUrls = buyUrls;
        }
        
        protected class Value extends ArrayList<String> {
          
        }
        
        public ArrayList<Value> getValues() {
          return values;
        }
        
        public void setValues(ArrayList<Value> values) {
          this.values = values;
        }
      }
      
      public ArrayList<Table> getTables() {
        return tables;
      }

      public void setTables(ArrayList<Table> tables) {
        this.tables = tables; 
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
    builder.append(String.format("<input name=\"subcategoryID\" placeholder=\"subcategoryID\" value=\"%s\"/><br>",""));
  }
  
  private void setTableValuesBySqlFromWWW(TableViewResponse.Result.Table table, String sql) {
    
    Provider p = getWWWProvider();
    
    Dataset<Record> ds = p.querySelect(sql);
    if (isNotNull(ds)) {
      
      List<String> avoids = Arrays.asList(new String[]{"url"});
      for (Record r: ds) {
        
        TableViewResponse.Result.Table.Value value = table.new Value();
        String buyUrl = "";
        
        for (Field f: r) {
          
          String n = f.getName();
          String v = f.getValue().asString();
          
          if (!avoids.contains(n)) {
            value.add(v);
          } else {
            if ("url".equals(n)) {
              buyUrl = v;
            }
          }
          
        }
        table.getBuyUrls().add(buyUrl);
        table.getValues().add(value);
      }
    }
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    TableViewResponse response = new TableViewResponse();
 
    Token token = getToken(response);
    
    if (isNotNull(token)) {
      
      Integer mobileMenuId = getPath().getParameterValue("subcategoryID",(Integer)null);
      if (isNotNull(mobileMenuId)) {
        
        Scheme scheme = getScheme();
        
        MobileTables tables = new MobileTables(scheme); 
        
        Filter filter = new Filter();
        filter.And(MobileTables.MobileMenuId,mobileMenuId);
        filter.And(MobileTables.Locked).IsNull();
        filter.And(MobileTables.Sql).IsNotNull();
        
        if (tables.open(filter,new Orders(MobileTables.Priority))) {
          
          boolean first = true;
          TableViewResponse.Result result = response.getResult();
          String description = "";
          
          for (Record r: tables) {
            
            MobileTable mt = (MobileTable)r;
            
            if (first) {
              description = mt.getMenuDescription().asString();
              first = false;
            }
            
            TableViewResponse.Result.Table table = result.new Table();
        
            table.setName(mt.getName().asString());
            table.setAbout(mt.getDescription().asString());
            table.setExpired(scheme.getStamp().addMonths(1));
        
            String[] columns = mt.getColumns().asString().split(Utils.getLineSeparator());
            table.getColumns().addAll(Arrays.asList(columns));
                
            String[] alignments = mt.getAlignments().asString().split(Utils.getLineSeparator());
            table.getAlignments().addAll(Arrays.asList(alignments));
        
            String sql = mt.getSql().asString();
            if (!isEmpty(sql)) {
              
              sql = sql.replace("$LANG",mt.getLangId().asString().toLowerCase());
              
              setTableValuesBySqlFromWWW(table,sql);
            }
            
            result.getTables().add(table);
          }
          result.getAbout().setDescription(description);
          result.setSubcategoryID(mobileMenuId.toString());
        }
      } else {
        throw new BaseResponseException(response,ErrorCodeLackOfParameters);
      }
    }
    return response;
  }
}
