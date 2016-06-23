package ufsic.scheme;

import java.sql.Date;
import java.sql.Types;
import ufsic.providers.DataSet;
import ufsic.providers.Params;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Client extends Account {

  public Client(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getClientId() {

    return getValue(Clients.ClientId);
  }
  
  public Value getSpectreUfsId() {
  
    return getValue(Clients.SpectreUfsId);
  }
  
  public String getTEST() {
    
    String ret = "";
    
    Date df = Date.valueOf("2013-10-01");
    Date tf = Date.valueOf("2013-11-30");
    
   /* Params ps = new Params("CLIENT_ID",getClientId()).Add("FROM_DATE",df).Add("TILL_DATE",tf);
    DataSet ds = provider.select("FP_CLIENT_MONEY",ps);
    if (isNotNull(ds)) { 
      for (Record r: ds) {
        ret = ret +" - "+ r.getValue("BEFORE_BALANCE").asString();  
      }
    }
   /* Params ps = new Params().Add("TEMP").Add("NAME","test").Add("ID",150);
    boolean r = provider.execute("I_TEST",ps);
    if (r) {
      ret = "It works "+ps.getValue("ID").asString()+" "+ps.getValue("TEMP").asString();
      
      //Params ps2 = new Params("ID",ps.getValue("ID").asInteger()*3);
      Params ps2 = new Params("ID",99);
      boolean r2 = provider.execute("I_TEST",ps2);
      if (r2) {
        ret = ret + " It works "+ps2.getValue("ID").asString();
      }
    }*/
    return ret;
  }
  
}
