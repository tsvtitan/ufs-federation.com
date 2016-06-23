package ufsic.scheme.messages;

import java.sql.Timestamp;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.Pattern;

public class SmsMessage extends PatternMessage {

  public SmsMessage(Pattern pattern) {
    super(pattern);
  }
  
  public boolean send(String senderName, String recipientPhone, Timestamp begin, Timestamp end, Integer priority) {
    
    return super.send(senderName,null,null,recipientPhone,null,begin,end,priority);
  }
  
  public boolean send(String recipientPhone, Timestamp begin, Timestamp end, Integer priority) {
    
    return send(null,recipientPhone,begin,end,priority);
  }

  public boolean send(String recipientPhone, Timestamp begin, Timestamp end) {
    
    return send(null,recipientPhone,begin,end,null);
  }
  
  public boolean send(String recipientPhone, Timestamp end, Integer priority) {
    
    return send(null,recipientPhone,null,end,priority);
  }

  public boolean send(String recipientPhone, Integer priority) {
    
    return send(null,recipientPhone,null,null,priority);
  }

  public boolean send(String recipientPhone, Timestamp end) {
    
    return send(null,recipientPhone,null,end,null);
  }

  public boolean send(String recipientPhone) {
    
    return send(null,recipientPhone,null,null,null);
  }
  
  // Account
  
  public boolean send(String senderName, Account account, Timestamp begin, Timestamp end, Integer priority) {
    
    boolean ret = false;
    if (isNotNull(account)) {
      Value phone = account.getPhone();
      if (phone.isNotNull()) {
        ret = super.send(null,senderName,null,
                         account.getAccountId(),account.getName().asString(),phone.asString(),
                         null,begin,end,priority,null,null,null,null);
      }
    }
    return ret;
  }

  public boolean send(Account account, Timestamp begin, Timestamp end, Integer priority) {
    
    return send((String)null,account,begin,end,priority);
  }

  public boolean send(Account account, Timestamp begin, Timestamp end) {
    
    return send(account,begin,end,null);
  }
  
  public boolean send(Account account, Timestamp end, Integer priority) {
    
    return send(account,(Timestamp)null,end,priority);
  }

  public boolean send(Account account, Timestamp end) {
    
    return send(account,(Timestamp)null,end,(Integer)null);
  }

  public boolean send(Account account, Integer priority) {
    
    return send(account,(Timestamp)null,(Timestamp)null,priority);
  }

  public boolean send(Account account) {
    
    return send(account,(Integer)null);
  }
  
  // from/to Account
  public boolean send(Account fromAccount, Account toAccount, Timestamp begin, Timestamp end, Integer priority) {
    
    boolean ret = false;
    if (isNotNull(toAccount) && isNotNull(fromAccount)) {

      Value phone = toAccount.getEmail();
      if (phone.isNotNull()) {
        ret = super.send(fromAccount.getAccountId(),fromAccount.getName().asString(),fromAccount.getPhone().asString(),
                         toAccount.getAccountId(),toAccount.getName().asString(),phone.asString(),
                         null,begin,end,priority,null,null,null,null);
      }
    }
    return ret;
  }
  
  public boolean send(Account fromAccount, Account toAccount, Timestamp begin, Timestamp end) {
    
    return send(fromAccount,toAccount,begin,end,null);
  }

  public boolean send(Account fromAccount, Account toAccount, Timestamp end, Integer priority) {
    
    return send(fromAccount,toAccount,null,end,priority);
  }

  public boolean send(Account fromAccount, Account toAccount, Timestamp end) {
    
    return send(fromAccount,toAccount,null,end,null);
  }

  public boolean send(Account fromAccount, Account toAccount) {
    
    return send(fromAccount,toAccount,null,null,null);
  }
  
}
