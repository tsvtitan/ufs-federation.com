package ufsic.gates;

import ufsic.providers.Record;
import ufsic.scheme.Message;
import ufsic.scheme.SchemeTable;

public class HttpOutgoingChannel extends OutgoingMessageChannel {

  public HttpOutgoingChannel() {
    super();
  }
  
  public HttpOutgoingChannel(SchemeTable table, Record record) {
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
  public boolean sendMessage(Message message) {
    
    boolean ret = false;
    HttpMessageConnection connection = (HttpMessageConnection)getConnection();
    if (isNotNull(connection)) {
      ret = connection.sendMessage(message);
    }
    return ret;
  }
  
}