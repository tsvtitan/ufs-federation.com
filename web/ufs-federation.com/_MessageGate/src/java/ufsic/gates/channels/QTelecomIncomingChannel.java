package ufsic.gates.channels;

import ufsic.gates.QTelecomMessageConnection;
import ufsic.providers.Record;
import ufsic.scheme.SchemeTable;

public class QTelecomIncomingChannel extends HttpIncomingChannel {

  public QTelecomIncomingChannel() {
  }

  public QTelecomIncomingChannel(SchemeTable table, Record record) {
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
      connection.setAction(QTelecomMessageConnection.Action.INBOX);
    }
  }
  
}
