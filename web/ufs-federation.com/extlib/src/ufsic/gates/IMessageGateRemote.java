package ufsic.gates;

import javax.ejb.Remote;

@Remote
public interface IMessageGateRemote {

  public boolean checkOutgoing();
  public boolean checkOutgoing(String channelId);
  public boolean checkIncoming();
  public boolean checkIncoming(String channelId);
  public boolean checkStatuses();
  public boolean checkStatuses(String channelId);
  public boolean notify(String channelId);
  
}
