package ufsic.gates.channels;

import java.io.File;

import java.io.FileOutputStream;
import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;
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
import ufsic.utils.Utils;

public class SmtpOutgoingChannel extends OutgoingMessageChannel {

  public SmtpOutgoingChannel() {
    super();
  }
  
  public SmtpOutgoingChannel(SchemeTable table, Record record) {
    super(table, record);
  }
  
  private Map<String,String> getHeaders(String headers) {
    
    Map<String,String> ret = new HashMap<>();
    if (!isEmpty(headers)) {
      
      String[] list = headers.split(Utils.getLineSeparator());
      for (String item: list) {
        
        String[] temp = item.split(":");
        if (temp.length>1) {
          
          StringBuilder sb = new StringBuilder();
          for (int i=1; i<temp.length; i++) {
            sb.append((sb.length()==0)?temp[i]:":"+temp[i]);
          }
          ret.put(temp[0].trim(),sb.toString());
        }
      }
    }
    return ret;
  }

  private void outputMessage(Properties props, Message message, MimeMessage msg) {
    
    String outputDir = props.getProperty("OutputDir");
    if (!isEmpty(outputDir)) {

      String name = outputDir;
      
      String channel = getClass().getSimpleName();
      if (!isEmpty(channel)) {

        name = String.format("%s%s%s",name,Utils.getPathSeparator(),channel);
        File file = new File(name);
        if (!file.exists()) {
          file.mkdirs();
        }
      }
      
      if (message.getParentId().isNotNull()) {
        
        name = String.format("%s%s%s",name,Utils.getPathSeparator(),message.getParentId().asString());
        File file = new File(name);
        if (!file.exists()) {
          file.mkdirs();
        }
      }

      name = String.format("%s%s%s",name,Utils.getPathSeparator(),message.getMessageId().asString());
     
      try {
        msg.writeTo(new FileOutputStream(name));
      } catch (Exception e) {
        logException(e);
      }
    
    }
  }
  
  @Override
  public boolean sendMessage(Message message) {
    
    boolean ret = false;
    
    if (isNotNull(message)) {
      
      Properties props = getProperties();
      if (isNotNull(props)) {

        final String userName = props.getProperty("UserName","").trim();
        final String password = props.getProperty("Password","").trim();
        final String domainName = props.getProperty("DomainName","").trim();
                
        Authenticator auth = new Authenticator() {
        
          @Override
          protected PasswordAuthentication getPasswordAuthentication() {
            return new PasswordAuthentication(userName,password);
          }
        };                
        
        Session session = Session.getInstance(props,auth);
        if (isNotNull(session)) {

          try {
            
            class InternalMimeMessage extends MimeMessage {

              private String messageId = null;
              private String domainName = null;
              
              public InternalMimeMessage(Session session) {
                super(session);
              }
              
              @Override
              protected void updateMessageID() throws MessagingException {
                
                if (isEmpty(messageId)) {
                  super.updateMessageID();
                } else {
                  if (!isEmpty(domainName)) {
                    setHeader("Message-ID",String.format("<%s@%s>",messageId,domainName));
                  } else {
                    setHeader("Message-ID",messageId);
                  }
                }
              }
              
              @Override
              protected void updateHeaders() throws MessagingException {
                super.updateHeaders();
              }
            }
            
            InternalMimeMessage msg = new InternalMimeMessage(session);
            
            msg.messageId = message.getMessageId().asString();
            msg.domainName = domainName;
            
            Map<String,String> headers = getHeaders(message.getHeaders().asString());
            for (Entry<String,String> header: headers.entrySet()) {
              msg.setHeader(header.getKey(),header.getValue());
            }
            
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
            
            msg.setSubject(message.getSubject().asString(),Utils.getCharset().name());
            
            boolean html = false;
            
            Value bodyContentType = message.getContentType();
            if (bodyContentType.isNotNull()) {
              html = bodyContentType.asString().toLowerCase().contains("html");
            }
            
            Attachments atts = message.getAttachments(true);
            if ((isNotNull(atts) && !atts.isEmpty()) || html) {
              
              Multipart content = new MimeMultipart();
              
              MimeBodyPart part = new MimeBodyPart();
              
              if (html) {
                part.setText(message.getBody().asString(),Utils.getCharset().name(),"html");
              } else {
                part.setText(message.getBody().asString(),Utils.getCharset().name(),"plain");
              }
              
              if (bodyContentType.isNotNull()) {
                part.setHeader("Content-Type",bodyContentType.asString());
              }
              part.setHeader("Content-Transfer-Encoding", "base64");
                           
              content.addBodyPart(part);
                      
              for (Attachment att: atts) {
                
                Value data = att.getData();
                if (data.isNotNull()) {

                  part = new MimeBodyPart();
                  
                  
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
                    fileName = MimeUtility.encodeText(fileName,Utils.getCharset().name(),"B");
                    part.setFileName(fileName);
                  }
                  
                  String cType = "application/octet-stream";
                  Value contentType = att.getContentType();
                  if (!contentType.isEmpty()) {
                    cType = contentType.asString();
                    part.setHeader("Content-Type",cType);
                  }
                  
                  ByteArrayDataSource source = new ByteArrayDataSource(data.asBytes(),cType);
                  
                  part.setDataHandler(new DataHandler(source));
                  
                  content.addBodyPart(part);
                }
              }
              msg.setContent(content);
              
            } else {
              
              msg.setText(message.getBody().asString(),Utils.getCharset().name(),"plain");
              if (bodyContentType.isNotNull()) {
                msg.setHeader("Content-Type",bodyContentType.asString());
              }
              msg.setHeader("Content-Transfer-Encoding", "base64");
            }
            
            outputMessage(props,message,msg);
            
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
