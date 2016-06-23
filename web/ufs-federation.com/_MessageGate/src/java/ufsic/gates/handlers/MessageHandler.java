package ufsic.gates;

import ufsic.gates.channels.IMessageChannel;
import ufsic.core.ICoreObject;
import ufsic.scheme.Message;
import ufsic.utils.Utils;

public class MessageHandler implements ICoreObject {
  
  private IMessageChannel channel = null;
  
  public MessageHandler(IMessageChannel channel) {

    super();
    this.channel = channel;  
  }
  
  public IMessageChannel getChannel() {
    
    return this.channel;
  }
  
  @Override
  public boolean isNull(Object obj) {
    return Utils.isNull(obj);
  }
  
  @Override
  public boolean isNotNull(Object obj) {
    return Utils.isNotNull(obj);
  }
  
  @Override
  public boolean isEmpty(Object obj) {
    return Utils.isEmpty(obj);
  }
  
  public boolean process(Message message) {
    
    return true;
  }
  
}
