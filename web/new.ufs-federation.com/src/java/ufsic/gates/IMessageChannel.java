package ufsic.gates;

import java.util.Properties;
import ufsic.providers.Provider;
import ufsic.providers.Value;
import ufsic.scheme.Message;
import ufsic.scheme.Messages;

public interface IMessageChannel {
  
  public Properties getProperties();
  public Provider getProvider();
  public Value getChannelId();
  public MessageGateChannels getChannels();  
  public IMessageGate getGate();
  public Messages getMessages();
  public boolean incomingMessage(Message message);
  public boolean processMessage(Message message);
}
