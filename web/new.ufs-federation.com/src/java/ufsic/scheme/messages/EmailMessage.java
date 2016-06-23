package ufsic.scheme.messages;

import java.sql.Timestamp;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.Pattern;

public class EmailMessage extends PatternMessage {

  public EmailMessage(Pattern pattern) {
    super(pattern);
  }
  
  // from/to
  
  @Override
  public boolean send(String senderName, String senderEmail, String recipientName, String recipientEmail, 
                      String subject, Timestamp begin, Timestamp end, Integer priority) {
    
    return super.send(senderName,senderEmail,recipientName,recipientEmail,subject,begin,end,priority);
  }

  public boolean send(String senderName, String senderEmail, String recipientName, String recipientEmail, String subject) {
    
    return send(senderName,senderEmail,recipientName,recipientEmail,subject,null,null,null);
  }
  
  public boolean send(String senderEmail, String recipientName, String recipientEmail, 
                      String subject, Timestamp begin, Timestamp end, Integer priority) {
    
    return send(null,senderEmail,recipientName,recipientEmail,subject,begin,end,priority);
  }
  
  public boolean send(String recipientName, String recipientEmail, 
                      String subject, Timestamp begin, Timestamp end, Integer priority) {
    
    return send(null,null,recipientName,recipientEmail,subject,begin,end,priority);
  }

  public boolean send(String recipientEmail, String subject, Timestamp end, Integer priority) {
    
    return send(null,null,null,recipientEmail,subject,null,end,priority);
  }

  public boolean send(String recipientEmail, String subject, Timestamp begin, Timestamp end) {
    
    return send(null,null,null,recipientEmail,subject,begin,end,null);
  }

  public boolean send(String recipientEmail, String subject, Timestamp end) {
    
    return send(null,null,null,recipientEmail,subject,null,end,null);
  }
  
  public boolean send(String recipientEmail, String subject, Integer priority) {
    
    return send(null,null,null,recipientEmail,subject,null,null,priority);
  }
  
  public boolean send(String recipientEmail, String subject) {
    
    return send(null,null,null,recipientEmail,subject,null,null,null);
  }

  public boolean send(String recipientEmail) {
    
    return send(null,null,null,recipientEmail,null,null,null,null);
  }

  // Account
  
  public boolean send(String senderName, String senderContact, Account account, 
                      String subject, Timestamp begin, Timestamp end, Integer priority) {
    boolean ret = false;
    if (isNotNull(account)) {
      Value email = account.getEmail();
      if (email.isNotNull()) {
        ret = super.send(null,senderName,senderContact,
                         account.getAccountId(),account.getName().asString(),email.asString(),
                         subject,begin,end,priority,null,null);
      }
    }
    return ret;
  }
  
  public boolean send(String senderContact, Account account, 
                      String subject, Timestamp begin, Timestamp end, Integer priority) {
    return send(null,senderContact,account,subject,begin,end,priority);
  }

  public boolean send(Account account, String subject, Timestamp begin, Timestamp end, Integer priority) {
    return send(null,null,account,subject,begin,end,priority);
  }

  public boolean send(Account account, String subject, Timestamp begin, Timestamp end) {
    return send(null,null,account,subject,begin,end,null);
  }

  public boolean send(Account account, String subject, Timestamp end, Integer priority) {
    return send(null,null,account,subject,null,end,priority);
  }
  
  public boolean send(Account account, String subject, Timestamp end) {
    return send(null,null,account,subject,null,end,null);
  }
  
  public boolean send(Account account, String subject, Integer priority) {
    return send(null,null,account,subject,null,null,priority);
  }

  public boolean send(Account account, String subject) {
    return send(null,null,account,subject,null,null,null);
  }

  public boolean send(Account account) {
    return send(null,null,account,null,null,null,null);
  }

  // from/to Account
  public boolean send(Account fromAccount, Account toAccount, 
                      String subject, Timestamp begin, Timestamp end, Integer priority) {
    boolean ret = false;
    if (isNotNull(toAccount) && isNotNull(fromAccount)) {

      Value email = toAccount.getEmail();
      if (email.isNotNull()) {
        ret = super.send(fromAccount.getAccountId(),fromAccount.getName().asString(),fromAccount.getEmail().asString(),
                         toAccount.getAccountId(),toAccount.getName().asString(),email.asString(),
                         subject,begin,end,priority,null,null);
      }
    }
    return ret;
  }

  public boolean send(Account fromAccount, Account toAccount, String subject, Timestamp end, Integer priority) {
    return send(fromAccount,toAccount,subject,null,end,priority);
  }
  
  public boolean send(Account fromAccount, Account toAccount, String subject, Timestamp end) {
    return send(fromAccount,toAccount,subject,null,end,null);
  }
  
  public boolean send(Account fromAccount, Account toAccount, String subject, Timestamp begin, Timestamp end) {
    return send(fromAccount,toAccount,subject,begin,end,null);
  }

  public boolean send(Account fromAccount, Account toAccount, String subject) {
    return send(fromAccount,toAccount,subject,null,null,null);
  }

  public boolean send(Account fromAccount, Account toAccount) {
    return send(fromAccount,toAccount,null,null,null,null);
  }
  
}