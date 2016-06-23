package ufsic.services;

import javax.ejb.EJB;
import javax.ejb.Stateless;
import javax.jws.WebMethod;
import javax.jws.WebParam;

import ufsic.gates.IMessageGateRemote;

@javax.jws.WebService(serviceName = "MessageGate",name="WebService")
@Stateless
public class MessageGateWebService extends WebService {
  
  @EJB
  private IMessageGateRemote messageGate;

  @WebMethod
  public boolean checkOutgoing(@WebParam(name = "channelId") String channelId) {
    return messageGate.checkOutgoing(channelId);
  }

  @WebMethod
  public boolean checkIncoming(@WebParam(name = "channelId") String channelId) {
    return messageGate.checkIncoming(channelId);
  }

  @WebMethod
  public boolean notify(@WebParam(name = "channelId") String channelId) {
    return messageGate.notify(channelId);
  }
  
}
