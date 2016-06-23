package ufsic.scheme.tables;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Properties;
import ufsic.providers.Dataset;
import ufsic.providers.FieldNames;
import ufsic.providers.GroupFilter;
import ufsic.providers.IProviderSelector;
import ufsic.providers.Provider;
import ufsic.providers.Value;
import ufsic.scheme.PageTable;
import ufsic.scheme.PageTableForm;
import ufsic.scheme.PageTableForms;
import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeObject;
import ufsic.scheme.forms.Transform;
import ufsic.utils.Utils;

public class Table extends SchemeObject implements IProviderSelector {

  public class Column {

    private String title = null;
    private String name = null;
    private String alignment = null;
    private String format = null;
    private Columns childs = new Columns();
    private Header header = null;
    
    public Column(String ident, Header header) {
      
      String[] tmp = ident.split(":");
      if (tmp.length>0) {
        
        if (tmp.length>1) {
          this.title = tmp[0].trim();
          this.name = tmp[1].trim();  
        } else {
          this.title = ident;
        }
      }
      this.header = header;
    }
    
    public String getName() {
      
      return this.name;
    }
    
    public String getTitle() {
      
      return getScheme().getDictionary().replace(this.title);
    }
    
    public String getAlignment() {
      
      return getScheme().getDictionary().replace(this.alignment);
    }
    
    private void setAlignment(String alignment) {
      
      this.alignment = alignment;
    }

    public String getFormat() {
      
      return getScheme().getDictionary().replace(this.format);
    }
    
    private void setFormat(String format) {
      
      this.format = format;
    }
    
    public String[] getTitles() {
      
      return title.split("\\|");
    }
    
    private Columns getChilds() {
      
      return this.childs;
    }
    
    public int getRowSpan() {
      
      int ret = 1;
      int count = childs.getLastCount();
      if (isNotNull(header) && (count==0)) {
        int index = header.getHeaders().indexOf(header);
        if (index>=0) {
          ret = header.getHeaders().size() - index;
        }
      }
      return ret;
    }
    
    public int getColSpan() {
    
      int count = childs.getLastCount();
      return (count==0)?1:count;
    }
    
  }

  public class Columns extends ArrayList<Column> {
  
    public Columns() {
      super();
    }
    
    public Column findByTitle(String title) {
      
      Column ret = null;
      
      for (Column column: this) {
        
        String t = column.getTitle();
        if (t.equals(title)) {
          ret = column;
          break;
        }
      }
      return ret;
    }

    public Column findByName(String name) {
      
      Column ret = null;
      
      for (Column column: this) {
        
        String n = column.getName();
        if (n.equals(name)) {
          ret = column;
          break;
        }
      }
      return ret;
    }
    
    public int getLastCount() {
      
      int ret = 0;
      for (Column column: this) {
        int size = column.getChilds().size();
        if (size==0) {
          ret++;
        } else {
          ret = ret + column.getChilds().getLastCount();
        }
      }
      return ret;
    }
    
  }
  
  
  public class Header {

    private final Columns columns = new Columns();
    private ArrayList<Header> headers = null;
    
    public Header(ArrayList<Header> headers) {
      
      this.headers = headers;
    }

    public Columns getColumns() {
      
      return this.columns;
    }
    
    private ArrayList<Header> getHeaders() {
      
      return this.headers;
    }
  }
  
  public class FilterItem {

    private String name = null;
    private ufsic.providers.Filter.Condition condition = ufsic.providers.Filter.Condition.DISABLED;
    private String formName = null;
    private Transform transform = null;

    public FilterItem(String ident) {
      
      String[] tmp = ident.split(":");
      if (tmp.length>0) {
        
        this.name = tmp[0];
        this.formName = tmp[0];
        this.condition = ufsic.providers.Filter.Condition.EQUAL;
        if (tmp.length>1) {
          
          String s = null;
          this.condition = ufsic.providers.Filter.Condition.asCondition(tmp[1]);
          if (tmp.length>2) {
            this.formName = tmp[2];
            s = tmp[2];
          }
          
          if (isNotNull(s)) {

            String[] nt = s.split("=");
            if (nt.length>1) {

              this.formName = (nt[0].trim().equals(""))?this.name:nt[0].trim();
              this.transform = new Transform(getScheme(),s.trim());

            } else {
              this.formName = s;
            }
          }
          
        }
        
      } else {
        this.name = ident;
        this.condition = ufsic.providers.Filter.Condition.EQUAL;
        this.formName = ident;
      }
    }

    public Object convert(Object obj) {
      
      Object ret = obj;
      if (isNotNull(transform)) {
        ret = transform.convert(obj);
      }
      return ret;
    }
    
  }
  
  public class Filter {

    private ArrayList<FilterItem> list = new ArrayList<>();
    
    public Filter(String ident) {

      String[] tmp = ident.split("\\|");
      if (tmp.length>0) {
        
        for (String s: tmp) {
          list.add(new FilterItem(s));
        }
      } else {
        list.add(new FilterItem(ident));
      }
    }
    
  }

  public class Filters extends ArrayList<Filter> {

    public Filters(Value filters) {
      
      super();

      if (filters.isNotNull()) {

        String[] fls = filters.asString().split(Utils.getLineSeparator());
        if (fls.length>0) {

          for (String s: fls) {
            this.add(new Filter(s.trim()));
          }
        }
      }
    }
    
    private void setGroupFilter(GroupFilter groupFilter, FieldNames fieldNames) {
      
      for (Filter f: this) {
        
        ufsic.providers.Filter filter = new ufsic.providers.Filter();
        for (FilterItem i: f.list) {
          
          if (fieldNames.contains(i.name)) {

            boolean exists = getScheme().getPath().parameterExists(i.formName);
            if (exists) {
              
              Object v = getScheme().getPath().getParameterValue(i.formName);
              if (isNotNull(v) && !v.toString().equals("")) {

                if (v.getClass().isArray()) {

                  String[] arr = (String[])v;
                  if (arr.length>0) {
                    for (String s: arr) {
                      if (isNotNull(s) && !s.equals("")) {
                        filter.Or(i.name,i.convert(s));
                      } else {
                        filter.Or(i.name).IsNull();
                      }
                    }
                  }
                } else {
                  filter.Or(i.name,i.condition,i.convert(v));
                }
              } else {
                // filter.Or(i.name).IsNull(); don't need contstructions like (NAME IS NULL)
              }
            }
          }
        }
        if (!filter.isEmpty()) {
          groupFilter.And(filter);
        }
      }
    }
  }
  
  public class Param {

    private String name = null;
    private String formName = null;
    private Transform transform = null;
            
    public Param(String ident) {
      
      String[] tmp = ident.split(":");
      if (tmp.length>0) {

        String s = null;
        if (tmp.length>1) {
          this.name = tmp[0].trim();
          s = tmp[1];  
        } else {
          this.name = tmp[0].trim();
          this.formName = tmp[0].trim();
        }

        if (isNotNull(s)) {

          String[] nt = s.split("=");
          if (nt.length>1) {

            this.formName = (nt[0].trim().equals(""))?this.name:nt[0].trim();
            this.transform = new Transform(getScheme(),s.trim());

          } else {
            this.formName = s;
          }
        }
      } else {
        this.name = ident;
        this.formName = ident;
      }
    }
    
    public Object convert(Object obj) {
      
      Object ret = obj;
      if (isNotNull(transform)) {
        ret = transform.convert(obj);
      }
      return ret;
    }
    
  }
  
  public class Params extends ArrayList<Param> {

    public Params(Value params) {
      
      super();

      if (params.isNotNull()) {

        String[] fls = params.asString().split(Utils.getLineSeparator());
        if (fls.length>0) {

          for (String s: fls) {
            this.add(new Param(s.trim()));
          }
        }
      }
    }

    private void setParams(ufsic.providers.Params params) {
      
      for (Param p: this) {
        
        boolean exists = getScheme().getPath().parameterExists(p.formName);
        if (exists) {

          Object v = getScheme().getPath().getParameterValue(p.formName);
          if (isNotNull(v) && !v.toString().equals("")) {

            if (v.getClass().isArray()) {

              String[] arr = (String[])v;
              if (arr.length>0) {
                for (String s: arr) {
                  if (isNotNull(s) && !s.equals("")) {
                    params.AddIn(p.name,p.convert(s));
                  } else {
                    params.AddInNull(p.name);
                  }
                }
              }
            } else {
              params.AddIn(p.name,p.convert(v));
            }
          } else {
            params.AddInNull(p.name);
          }
        } else {
          params.AddInNull(p.name);
        }
      }
    }
    
  }
  
  public class Field extends ufsic.providers.Field {

    public Field(String name, Value value) {
      super(name, value);
    }

    public Field(String name) {
      super(name,null);
    }
    
    public String getAlignment() {
      
      String ret = null;
      Column column = columns.findByName(getName());
      if (isNotNull(column)) {
        ret = column.getAlignment();
      }
      return ret;
    }

    public String getText(Value value) {
      
      String ret = null;
      if (isNotNull(value)) {
        
        ret = value.asString();

        if (value.isNotNull()) {

          Column column = columns.findByName(getName());
          if (isNotNull(column)) {

            String fmt = column.getFormat();
            if (isNotNull(fmt) && (!fmt.trim().equals(""))) {

              ret = getScheme().valueFormat(value,fmt);
            }
          }
        }
      }
      return ret;
    }
    
    public String getText() {

      return getText(getValue());
    }
    
  }
  
  public class Record extends ufsic.providers.Record {

    @Override
    protected Field newField(String name, Value value, Class cls) {
      
      return new Field(name,value);
    }
  }
  
  public class FooterField extends Field {

    private Column column = null;
    private boolean exists = false;
    private Footer footer = null;
    
    public FooterField(Footer footer, Column column, boolean exists) {
      
      super(column.getName());
      
      this.footer = footer;
      this.column = column;
      this.exists = exists;
    }
    
    @Override
    public String getText() {
      
      String ret = null;
      if (isNotNull(dataset) && isNotNull(column)) {

        if (exists) {
          
          Double d = 0.0;
          for (ufsic.providers.Record r: dataset) {
            Value v = r.getValue(getName());
            if (v.isDouble()) {
              d+= v.asDouble();
            }
          }
          ret = super.getText(new Value(d));
          
        } else if (footer.indexOf(this)==0) {
          
          ret = footer.getName();
        }
      }
      return ret;
    }
  }
  
  public class Footer extends Record {

    private String name = null;

    public Footer(String ident) {

      super();
      
      String[] tmp = ident.split("=");
      if (tmp.length>0) {

        this.name = tmp[0].trim();
        
        if (tmp.length>1) {
        
          tmp = tmp[1].trim().split("\\|");
          if (tmp.length>0) {
            
            List l = Arrays.asList(tmp);
            
            for (Column cl: columns) {
              add(new FooterField(this,cl,l.contains(cl.getName()) || l.contains("*")));  
            }
          }
        }

      } else {
        this.name = ident;
      }
    }
    
    public String getName() {
      return getScheme().getDictionary().replace(name);
    }
    
  }
  
  public class Footers extends ArrayList<Footer> {

    public void setSums(Value value) {
      
      if (isNotNull(value)) {
        String[] tmp = value.asString().split(Utils.getLineSeparator());

        for (String s: tmp) {
          add(new Footer(s));
        }
      }
    }
  }
  
  final private ArrayList<Header> headers = new ArrayList<>();
  final private Columns columns = new Columns();
  final private Footers footers = new Footers();
  private Dataset<Record> dataset = null;
  private boolean isTemplate = false;
  private int from = -1;
  private int count = -1;
  private Value allCount = new Value(null);
  
  private PageTable table = null;
  private Scheme scheme = null;
  private Provider provider = null;
  
  public Table(PageTable table) {

    super(table.getScheme());
    this.table = table;
    this.scheme = table.getScheme();
    this.provider = table.getProvider();
    this.isTemplate = isNull(getScheme().getPage());
  }
  
  public String getName() {
    return getScheme().getDictionary().replace(table.getName().asString());
  }

  public String getDescription() {
    return getScheme().getDictionary().replace(table.getDescription().asString());
  }

  public ArrayList<Header> getHeaders() {
    return headers;
  }

  public Columns getColumns() {
    return columns;
  }

  public Footers getFooters() {
    return footers;
  }

  public Dataset getRecords() {
    
    Dataset ret = dataset; 
    if (isNull(ret)) {
      ret = new Dataset();
    }
    return ret;
  }
  
  public int getFrom() {
    return (from>0)?from:1;
  }

  public String getPageTableId() {
    return table.getPageTableId().asString();
  }

  public boolean getAutoLoad() {
    return table.getAutoLoad().asBoolean(); 
  }

  public boolean getAsync() {
    return table.getAsync().asBoolean(); 
  }

  public int getCount() {

    int ret = 0;
    if (isNotNull(dataset)) {
      ret = dataset.size();
    }
    return ret;
  }

  public int getAllCount() {
    return allCount.asInteger();
  }

  public int getMaxCount() {
    return table.getMaxCount().asInteger();
  }

  public int getSelectedCount() {
    return count;
  }

  public Object getTemplate() {
    return table.getTemplateId().asString();
  }

  public Object[] getCountOnPage() {

    Object[] ret = null;

    int all = getAllCount();
    int count = getCount();

    if (all>count) {

      ArrayList<Integer> arr = new ArrayList<>();

      Value maxCount = table.getMaxCount();
      if (maxCount.isNotNull()) {

        int v = maxCount.asInteger();
        if (!arr.contains(v)) {
          arr.add(v);
        }
      }

      Integer[] def = { 5, 10, 25, 50, 100 };

      for (Integer v: def) {
        if (!arr.contains(v) && v.intValue()<=all) {
          arr.add(v);
        }
      }

      ret = arr.toArray();
    }

    if (isNotNull(ret)) {
      Arrays.sort(ret);
    }

    return ret;
  }

  public ArrayList<String> getFormIds() {

    ArrayList<String> ret = new ArrayList<>();
    PageTableForms forms = table.getPageTableForms();
    for (ufsic.providers.Record r: forms) {
      PageTableForm form = (PageTableForm)r; 
      ret.add(form.getPageFormId().asString());
    }
    return ret;
  }

  @Override
  public Record newRecord() {
    
    return new Record();
  }
  
  public boolean process() {

    boolean ret = false;

    Object f = getScheme().getPath().getParameterValue("from");
    if (Utils.isInteger(f)) {
      from = Utils.toInt(f.toString());
    }

    Object c = getScheme().getPath().getParameterValue("count");
    if (Utils.isInteger(c)) {
      count = Utils.toInt(c.toString());
    }
    
    
    if (isNotNull(table)) {
   
      Value cs = table.getColumns();
      Value sums = table.getSums();
      
      Properties alignments = table.getAlignments().asProperties();
      Properties formats = table.getFormats().asProperties();

      headers.clear();
      columns.clear();
      footers.clear();

      if (cs.isNotNull()) {

        String[] cols = cs.asString().split(Utils.getLineSeparator());
        if (cols.length>0) {

          headers.add(new Header(headers));

          for (String s: cols) {

            String s1 = s.trim();
            if (!s1.startsWith("//")) {

              Column column = new Column(s.trim(),headers.get(0));
              String name = column.getName();
              if (isNotNull(name) && !name.equals("")) {
                column.setAlignment(alignments.getProperty(name));
                column.setFormat(formats.getProperty(name));
                columns.add(column);
              }

              String[] titles = column.getTitles();
              if (titles.length<=1) {
                headers.get(0).getColumns().add(column);
              } else {

                Column parent = null;

                for (int i=0; i<titles.length; i++) {

                  int index = i;

                  if (index>(headers.size()-1)) {

                    Header header = new Header(headers);
                    Column col = new Column(titles[i],header);
                    header.getColumns().add(col);
                    headers.add(header);
                    if (isNotNull(parent)) {
                      parent.getChilds().add(col);
                    }

                  } else {

                    Header header = headers.get(index);
                    if (isNotNull(header)) {

                      Column col = header.getColumns().findByTitle(titles[i]);
                      if (isNull(col)) {
                        col = new Column(titles[i],header);
                        header.getColumns().add(col);
                        if (isNotNull(parent)) {
                          parent.getChilds().add(col);
                        }
                      }
                      parent = col;
                    }
                  }
                }
              }
            }
          }
        }
      }

      allCount.clear();

      if (!columns.isEmpty()) {

        Value query = table.getQuery();
        if (query.isNotNull()) {

          String sql = query.asString();

          Filters filters = null;
          Params params = null;

          boolean formExists = getScheme().getPath().parameterExists("pageFormId");
          if (formExists) {

            Object pageFormId = getScheme().getPath().getParameterValue("pageFormId");
            if (isNotNull(pageFormId)) {

              formExists = getScheme().getPath().getFormSuccess(pageFormId.toString());
              if (formExists) {

                PageTableForms forms = table.getPageTableForms();
                if (!forms.isEmpty()) {

                  for (ufsic.providers.Record r: forms) {
                    PageTableForm form = (PageTableForm)r;
                    Value formId = form.getPageFormId();
                    if (formId.same(pageFormId)) {
                      filters = new Filters(form.getFilters());
                      params = new Params(form.getParams());
                      break;
                    }
                  }
                }
              }
            }
          }

          FieldNames fn = new FieldNames();
          for (Column column: columns) {
            fn.add(column.getName());
          }

          GroupFilter gf = new GroupFilter();
          if (isNotNull(filters)) {
            filters.setGroupFilter(gf,fn); 
          }

          ufsic.providers.Params ps = new ufsic.providers.Params();
          if (isNotNull(params)) {
            params.setParams(ps); 
          }

          Value maxCount = table.getMaxCount();
          if (maxCount.isNotNull() && (count<=0)) {
            count = maxCount.asInteger();
          }

          String dSql = provider.getWrappedSelectSql(sql,fn,gf,null,from,count,ps);

          boolean needSelect = isTemplate;
          if (!isTemplate) {
            needSelect = formExists || (table.getAutoLoad().asBoolean() && !table.getAsync().asBoolean());
          }

          boolean noErrors = true;
          if (needSelect) {
            dataset = provider.querySelect(dSql,ps,this);
            footers.setSums(sums);
            noErrors = isNotNull(dataset) && isNull(provider.getLastException());
          }

          boolean needCount = isNull(dataset) || (isNotNull(dataset) && maxCount.isNotNull());
          if (!isTemplate) {
            needCount = needCount && (formExists || table.getAutoLoad().asBoolean());  
          }

          if (needCount) {

            if (isNotNull(dataset)) {
              if (maxCount.isNull()) {
                allCount.setObject(dataset.size());
                needCount = false;
              }
            }

            if (needCount && noErrors) {
              String cSql = provider.getWrappedSelectSql(sql,fn,gf,null,ps);
              allCount = provider.count(cSql,ps);
            }
          }
        }
      } 
      ret = true;
    }
    return ret;
  }
  
}
