package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Clients extends Accounts {
  
  public final static String TableName = "CLIENTS";
  
  public final static String ClientId = "CLIENT_ID";
  public final static String SpectreUfsId = "SPECTRE_UFS_ID";

  public Clients(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Clients(Scheme scheme) {
    super(scheme);
    this.name = TableName;
  }

  @Override
  public Class getRecordClass() {
    return Client.class;
  }
 
  public Client getClient(Value accountId) {
    
    Client ret = null;
    Record r = provider.first(getViewName(),null,new Filter(ClientId,accountId));
    if (isNotNull(r)) {
      ret = new Client(this,r);
    }
    return ret;
  }
  
}