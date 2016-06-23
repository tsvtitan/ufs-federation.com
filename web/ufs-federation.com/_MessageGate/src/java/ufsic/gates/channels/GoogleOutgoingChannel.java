package ufsic.gates.channels;

import ufsic.providers.Record;
import ufsic.scheme.SchemeTable;

public class GoogleOutgoingChannel extends SmtpOutgoingChannel {

  public GoogleOutgoingChannel() {
    super();
  }
  
  public GoogleOutgoingChannel(SchemeTable table, Record record) {
    super(table, record);
  }

  public GoogleOutgoingChannel(SchemeTable table) {
    super(table, null);
  }
   
  
}
