package ufsic.gates.channels;

import java.util.Properties;
import javax.activation.DataHandler;
import javax.mail.*;
import javax.mail.internet.*;
import javax.mail.util.ByteArrayDataSource;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Attachment;
import ufsic.scheme.Attachments;
import ufsic.scheme.Message;
import ufsic.scheme.SchemeTable;

public class SmtpOutgoingChannel extends OutgoingMessageChannel {

  public SmtpOutgoingChannel() {
    super();
  }
  
  public SmtpOutgoingChannel(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  public boolean sendMessage(Message message) {
    
    boolean ret = false;
    
    if (isNotNull(message)) {
      
      Properties props = getProperties();
      if (isNotNull(props)) {

        final String userName = props.getProperty("UserName","").trim();
        final String password = props.getProperty("Password","").trim();
                
        Authenticator auth = new Authenticator() {
        
          protected PasswordAuthentication getPasswordAuthentication() {
            return new PasswordAuthentication(userName,password);
          }
        };                
        
        Session session = Session.getInstance(props,auth);
        if (isNotNull(session)) {

          try {
            
            MimeMessage msg = new MimeMessage(session);
            
            String fromAddress = props.getProperty("FromAddress",userName);
            String fromName = props.getProperty("FromName","");
            
            Value senderContact = message.getSenderContact();
            if (senderContact.isNotNull()) {
              fromAddress = senderContact.asString();
            } 
            
            Value senderName = message.getSenderName();
            if (senderName.isNotNull()) {
              fromName = senderName.asString();
            }
            
            msg.setFrom(new InternetAddress(fromAddress,fromName));
            
            String toAddress = message.getRecipientContact().asString();
            String toName = message.getRecipientName().asString();
            
            msg.setRecipient(javax.mail.Message.RecipientType.TO,new InternetAddress(toAddress,toName));
            
            String replyAddress = props.getProperty("ReplyAddress");
            String replyName = props.getProperty("ReplyName","");
            if (isNotNull(replyAddress)) {
              Address[] addresses = new Address[1];
              addresses[0] = new InternetAddress(replyAddress,replyName);
              msg.setReplyTo(addresses);
            }
            
            msg.setSubject(message.getSubject().asString());
            
            boolean mime = false;
            
            Value bodyContentType = message.getContentType();
            if (bodyContentType.isNotNull()) {
              mime = bodyContentType.asString().toLowerCase().contains("html");
            }
            
            Attachments atts = message.getAttachments(true);
            if ((isNotNull(atts) && !atts.isEmpty()) || mime) {
              
              Multipart content = new MimeMultipart();
              
              MimeBodyPart part = new MimeBodyPart();
              part.setText(message.getBody().asString());
              if (bodyContentType.isNotNull()) {
                part.setHeader("Content-Type",bodyContentType.asString());
              }
              content.addBodyPart(part);
                      
              for (Record rec: atts) {
                
                Attachment att = (Attachment)rec;
                
                Value data = att.getData();
                if (data.isNotNull()) {

                  part = new MimeBodyPart();
                  
                  Value contentType = att.getContentType();
                  if (contentType.isNotNull()) {
                    part.setHeader("Content-Type",contentType.asString());
                  }
                  
                  Value contentId = att.getContentId();
                  if (contentId.isNotNull()) {
                    part.setContentID(contentId.asString());
                    part.setDisposition(BodyPart.INLINE);
                  } else {
                    part.setDisposition(BodyPart.ATTACHMENT);
                  }

                  Value name = att.getName();
                  if (name.isNotNull()) {
                    String fileName = att.getName().asString();
                    String ext = att.getExtension().asString();
                    fileName = (!ext.equals(""))?String.format("%s.%s",fileName,ext):fileName;

                    part.setFileName(fileName);
                  }
                  
                  ByteArrayDataSource source = new ByteArrayDataSource(data.asBytes(),contentType.asString());
                  
                  part.setDataHandler(new DataHandler(source));
                  
                  content.addBodyPart(part);
                }
              }
              msg.setContent(content);
            } else {
              msg.setText(message.getBody().asString());
            }
            
            Transport.send(msg);
            
            ret = true;
            
          } catch (Exception e) {
            message.setError(e.getMessage());
            logException(e);
          }
        }
      }
    }
    return ret;
  }
  
}
