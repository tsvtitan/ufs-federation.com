package ufsic.gates.connections;

import ufsic.connections.Connection;
import ufsic.gates.channels.IMessageChannel;
import java.util.Properties;

public class MessageConnection extends Connection {

  private IMessageChannel channel = null;
  
  public MessageConnection() {
  }

  public MessageConnection(Properties properties) {
    super(properties);
  }
  
  public MessageConnection(IMessageChannel channel) {
    
    this(channel.getProperties());
    this.channel = channel;  
  }
  
  public IMessageChannel getChannel() {
    
    return this.channel;
  }
}
