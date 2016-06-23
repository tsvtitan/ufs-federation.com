package ufsic.gates;

import ufsic.providers.Record;
import ufsic.scheme.SchemeTable;

public class QTelecomOutgoingChannel extends HttpOutgoingChannel {

  public QTelecomOutgoingChannel() {
    super();
  }
  
  public QTelecomOutgoingChannel(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  public Class getMessageConnectionClass() {
  
    return QTelecomMessageConnection.class;
  }
  
  @Override
  public void beforeConnect() {
    
    QTelecomMessageConnection connection = (QTelecomMessageConnection)getConnection();
    if (isNotNull(connection)) {
      connection.setAction(QTelecomMessageConnection.Action.POST_SMS);
    }
  }
 
}