package ufsic.scheme.handlers;

import ufsic.gates.MessageGate;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;

public class NotifyHandler extends Handler {

  public NotifyHandler(Path path) {
    super(path);
  }
  
  public static boolean isPathRestricted() {
    
    return false;
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = super.process(comm); 
    
    Scheme scheme = getScheme();
    
    if (isNotNull(scheme)) {
      
      String rest = getPath().getRestPath();
      if (isNotNull(rest) && !rest.equals("")) {
        
        String[] parts = rest.split("[\\/]");
        if (parts.length>1) {
          
          String channelId = parts[parts.length-1];
          if (!channelId.equals("")) {

            MessageGate gate = scheme.getController().getMessageGate();
            if (isNotNull(gate)) {

              ret = gate.notify(channelId);  
            }
          }
        }
      }
    }
    return ret;
  }
  
  
}
