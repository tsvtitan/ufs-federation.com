package ufsic.scheme.handlers;

import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.*;
import ufsic.scheme.messages.*;
import ufsic.scheme.patterns.*;

public class TestHandler extends Handler {

  public TestHandler(Path path) {
    super(path);
  }

  @Override
  public boolean process(Comm comm) {
    
    //SmsPasswordRestorePattern pattern = new SmsPasswordRestorePattern(getScheme());
    //pattern.setVar("body","<a href='/new'>TEST m1</a>");
    //pattern.setCode("ВАСЯ2");
    
    EmailPasswordRestorePattern pattern = new EmailPasswordRestorePattern(getScheme());
    //pattern.setVar("body","<a href='/new'>TEST m1</a>");
    //pattern.setLink("http://ru.ru");
    pattern.setCode("ВАСЯ2");
    
    
    EmailMessage message = new EmailMessage(pattern);
    if (message.send("tsv@ufs-federation.com")) {
    /*SmsMessage message = new SmsMessage(pattern);
    if (message.send("+79153369769")) {*/
      
      Value id = message.getMessageId();
      getEcho().write("<h1>%s</h1>",id.asString());  
      
      Account a = getScheme().getAccount();
      if (isNotNull(a)) {
        AccountPage ap = a.getAuthFailedPage();
        if (isNotNull(ap)) {
          getPath().redirect(ap.getPath());
        }
      }
    } else {
      getEcho().write("<h2>%s</h2>","false");    
    }
    
    return true;
  }
 
  
  
}