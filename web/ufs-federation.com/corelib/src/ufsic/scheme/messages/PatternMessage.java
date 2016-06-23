package ufsic.scheme.messages;

import java.sql.Timestamp;

import ufsic.gates.IMessageGateRemote;
import ufsic.providers.Value;
import ufsic.scheme.Attachment;
import ufsic.scheme.Attachments;
import ufsic.scheme.Message;
import ufsic.scheme.Pattern;
import ufsic.scheme.Scheme;


public class PatternMessage extends Message {

  private final Pattern pattern;
  private boolean bodyExists = false;
  
  public PatternMessage(Pattern pattern) {
    super(pattern.getScheme().getMessages());
    this.pattern = pattern;
    setSenderId(pattern.getScheme().getAccountId());
  }

  @Override
  public Attachments getAttachments() {
    
    return super.getAttachments(false);
  }
  
  public void setBodyExists(boolean bodyExists) {
    
    this.bodyExists = bodyExists;
  }
  
  public boolean queue(Value senderId, String senderName, String senderContact, 
                       Value recipientId, String recipientName, String recipientContact,
                       String subject, Timestamp begin, Timestamp end,
                       Integer priority, Value channelId, Value patternId, Value parentId,
                       String headers) {
    
    boolean ret = false;
    
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
    
      String body = pattern.parseBody();
      
      if ((bodyExists && !isEmpty(body)) || !bodyExists) {
        
        if (getMessageId().isNull()) setMessageId(scheme.getProvider().getUniqueId());

        if (getCreatorId().isNull()) setCreatorId(scheme.getApplicationId());
        if (isNotNull(senderId)) setSenderId(senderId);
        if (isNotNull(senderName)) setSenderName(senderName);
        if (isNotNull(senderContact)) setSenderContact(senderContact);
        if (isNotNull(recipientId)) setRecipientId(recipientId);
        if (isNotNull(recipientName)) setRecipientName(recipientName);
        if (isNotNull(recipientContact)) setRecipientContact(recipientContact);

        if (isNotNull(subject)) { 
          setSubject(subject);
        } else {
          Value s = pattern.getSubject();
          setSubject(scheme.getDictionary().replace(s.asString()));  
        }

        setBody(body);

        if (isNotNull(begin)) setBegin(begin);
        if (isNotNull(end)) setEnd(end);
        if (isNotNull(priority)) setPriority(priority);
        if (isNotNull(channelId)) setChannelId(channelId);

        setContentType(pattern.getContentType());

        if (isNotNull(patternId)) setPatternId(patternId);
        if (isNotNull(parentId)) setParentId(parentId);
        if (isNotNull(headers)) setHeaders(headers);

        if (insert()) {

          boolean flag = true;
          Value id = getMessageId();
          if (id.isNotNull()) {
            
            Attachments atts = getAttachments();
            if (isNotNull(atts) && !atts.isEmpty()) {

              for (Attachment att: atts) {

                if (att.getAttachmentId().isNull()) {
                  att.setAttachmentId(scheme.getProvider().getUniqueId());
                }
                if (att.getMessageId().isNull()) {
                  att.setMessageId(id);
                }
                flag = flag && att.insert();
              }
            }
            ret = flag;
          }
        }

      }
    }
    return ret;
  }
  
  public boolean send(Value senderId, String senderName, String senderContact, 
                      Value recipientId, String recipientName, String recipientContact,
                      String subject, Timestamp begin, Timestamp end,
                      Integer priority, Value channelId, Value patternId, Value parentId,
                      String headers) {

    boolean ret = queue(senderId,senderName,senderContact,
                        recipientId,recipientName,recipientContact,
                        subject,begin,end,priority,channelId,patternId,parentId,
                        headers);
    if (ret) {
    
      Scheme scheme = getScheme();
      if (isNotNull(scheme)) {
        
        IMessageGateRemote gate = scheme.getMessageGate();
        if (isNotNull(gate)) {

          ret = gate.checkOutgoing(getChannelId().asString());
        }
      }
    }
    return ret;
  }
  
  public boolean send(String senderName, String senderContact, String recipientName, String recipientContact,
                      String subject, Timestamp begin, Timestamp end, Integer priority) {
    return send(null,senderName,senderContact,null,recipientName,recipientContact,subject,begin,end,priority,null,null,null,null);
  }
  
  public boolean send(String senderName, String senderContact, String recipientName, String recipientContact,
                      String subject, Timestamp begin, Timestamp end, Integer priority, String headers, Value parentId) {
    return send(null,senderName,senderContact,null,recipientName,recipientContact,subject,begin,end,priority,null,null,parentId,headers);
  }
  
 }
