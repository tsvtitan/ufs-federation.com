package ufsic.gates.channels;

import ufsic.providers.Record;
import ufsic.scheme.SchemeTable;

public class GoogleIncomingChannel extends ImapIncomingChannel {

  public GoogleIncomingChannel() {
    super();
  }

  public GoogleIncomingChannel(SchemeTable table, Record record) {
    super(table, record);
  }

  public GoogleIncomingChannel(SchemeTable table) {
    super(table);
  }
   
  
}