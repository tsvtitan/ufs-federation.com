package ufsic.gates.channels;

import ufsic.gates.HttpMessageConnection;
import ufsic.providers.Record;
import ufsic.scheme.SchemeTable;

public class HttpIncomingChannel extends IncomingMessageChannel {

  public HttpIncomingChannel() {
  }

  public HttpIncomingChannel(SchemeTable table, Record record) {
    super(table, record);
  }
  
  @Override
  public Class getMessageConnectionClass() {
  
    return HttpMessageConnection.class;
  }
 
  @Override
  public boolean isConnected() {
    
    boolean ret = false;
    HttpMessageConnection connection = (HttpMessageConnection)getConnection();
    if (isNotNull(connection)) {
      ret = connection.isConnected();
    }
    return ret;
  }
  
  @Override
  public boolean receiveMessages(boolean withWait) {
    
    boolean ret = false;
    HttpMessageConnection connection = (HttpMessageConnection)getConnection();
    if (isNotNull(connection)) {
      ret = connection.receiveMessages();
    }
    return ret;
  }
  
}
