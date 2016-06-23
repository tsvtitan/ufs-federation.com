package ufsic.gates.channels;

import ufsic.providers.Record;
import ufsic.scheme.SchemeTable;

public class ExchangeOutgoingChannel extends SmtpOutgoingChannel {
  
  public ExchangeOutgoingChannel() {
    super();
  }
  
  public ExchangeOutgoingChannel(SchemeTable table, Record record) {
    super(table, record);
  }
  
}
