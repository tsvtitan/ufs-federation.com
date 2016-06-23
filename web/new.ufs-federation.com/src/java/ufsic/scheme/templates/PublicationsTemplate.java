package ufsic.scheme.templates;

import java.util.Map;
import ufsic.providers.DataSet;
import ufsic.providers.Filter;
import ufsic.providers.Order;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.scheme.Publication;
import ufsic.scheme.Publications;
import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

public class PublicationsTemplate extends Template {

  public PublicationsTemplate(SchemeTable table, Record record) {
    super(table, record);
  }

  public class TemplatePublications extends Publications {

    protected int count = 0;
    protected int next = 0;
    
    public TemplatePublications(Scheme scheme) {
      super(scheme);
    }
    
    public int getCount() {
      return count;
    }

    public void setCount(int count) {
      this.count = count;
    }

    public int getNext() {
      return next; 
    }

    public void setNext(int next) {
      this.next = next; 
    }
  }

  @Override
  public void process(Map<String, Object> vars) {
    
    super.process(vars);
    
    if (!vars.containsKey("publications")) {
      
      int from = 1;
      Object f = getScheme().getPath().getParameterValue("from");
      if (isNotNull(f)) {
        from = Integer.parseInt(f.toString());
      }
      int count = 1;
      Object c = getScheme().getPath().getParameterValue("count");
      if (isNotNull(c)) {
        count = Integer.parseInt(c.toString());
      }

      Filter fl = new Filter(Publications.LangId,getScheme().getLangId()).Or(Publications.LangId).IsNull();
      DataSet ds = getProvider().select(getScheme().getPublications().getViewName(),null,fl,new Orders(Publications.Posted,Order.Type.DESC),from,count,null);
      if (isNotNull(ds)) {

        TemplatePublications publications = new TemplatePublications(getScheme());
        for (Record r: ds) {
          publications.add(new Publication(publications,r));
        }
        publications.setCount(getProvider().count(getScheme().getPublications().getViewName(),fl).asInteger());
        publications.setNext(from + count);
        vars.put("publications",publications);
      }
    }
  }
  
}
