package ufsic.scheme.handlers;

import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.Confirm;
import ufsic.scheme.Confirms;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;

public class ConfirmHandler extends PageHandler {

  public ConfirmHandler(Path path) {
    super(path);
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = false; 
    
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {

      Path path = getPath();
      if (isNotNull(path)) {
      
        String id = path.getRestPathValue();
        if (isNotNull(id)) {
          
          Value stamp = getProvider().getNow();

          GroupFilter gf = new GroupFilter();
          gf.And(new Filter(Confirms.ConfirmId,id));
          gf.And(new Filter().And(Confirms.Locked).IsNull());
          gf.And(new Filter().Add(Confirms.Begin).LessOrEqual(stamp).Or(Confirms.Begin).IsNull());
          gf.And(new Filter().Add(Confirms.End).GreaterOrEqual(stamp).Or(Confirms.End).IsNull());
          gf.And(new Filter().Add(Confirms.Confirmed).IsNull());
          
          Record r = scheme.getProvider().first(scheme.getConfirms().getViewName(),gf);
          if (isNotNull(r)) {
            
            Value code = r.getValue(Confirms.Code);
            if (code.isNotNull()) {
              ret = super.process(comm);
            } else {
              Confirms confirms = getScheme().getConfirms();
              
              Confirm c = confirms.newConfirm(r);
              if (isNotNull(c)) {
                ret = c.process(true);
              }
            }
          }
        }
      }
    }
    return ret;
  }
  
  
}
