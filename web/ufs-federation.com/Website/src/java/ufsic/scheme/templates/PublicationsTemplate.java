package ufsic.scheme.templates;

import java.util.Map;
import ufsic.contexts.IVarContext;
import ufsic.providers.Dataset;
import ufsic.providers.Filter;
import ufsic.providers.Order;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.scheme.Path;
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
  public void process(IVarContext context) {
    
    super.process(context);
    
    if (!context.localExists("publications")) {
      
      Scheme scheme = getScheme();
      Path path = scheme.getPath();
      
      int from = path.getParameterValue("from",1);
      int count = path.getParameterValue("count",1);

      Filter fl = new Filter(Publications.LangId,scheme.getLangId()).Or(Publications.LangId).IsNull();
      Dataset<Record> ds = getProvider().select(scheme.getPublications().getViewName(),null,fl,new Orders(Publications.Posted,Order.Type.DESC),from,count,null);
      if (isNotNull(ds)) {

        TemplatePublications publications = new TemplatePublications(getScheme());
        for (Record r: ds) {
          publications.add(new Publication(publications,r));
        }
        publications.setCount(getProvider().count(scheme.getPublications().getViewName(),fl).asInteger());
        publications.setNext(from + count);
        context.setLocalVar("publications",publications);
      }
    }
  }
  
}
