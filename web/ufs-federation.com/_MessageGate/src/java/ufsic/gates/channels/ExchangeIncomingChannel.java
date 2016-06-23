package ufsic.gates.channels;
 
import ufsic.providers.Record;
import ufsic.scheme.SchemeTable;

public class ExchangeIncomingChannel extends ImapIncomingChannel {

  public ExchangeIncomingChannel() {
  }

  public ExchangeIncomingChannel(SchemeTable table, Record record) {
    super(table, record);
  }

  public ExchangeIncomingChannel(SchemeTable table) {
    super(table);
  }
  
}
