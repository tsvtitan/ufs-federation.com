package ufsic.gates.handlers;

import ufsic.gates.MessageHandler;
import ufsic.gates.channels.IMessageChannel;
import ufsic.scheme.Message;

public class SFtpMessageHandler extends MessageHandler {

  public SFtpMessageHandler(IMessageChannel channel) {
    super(channel);
  }
  
  @Override
  public boolean process(Message message) {
    
    return true;
  }
  
}
