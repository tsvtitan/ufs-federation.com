package ufsic.gates;

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
