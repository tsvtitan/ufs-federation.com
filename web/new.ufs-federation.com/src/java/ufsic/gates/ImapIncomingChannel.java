package ufsic.gates;

import com.sun.mail.imap.IMAPFolder;
import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.security.GeneralSecurityException;
import java.util.Properties;
import javax.mail.*;
import javax.mail.internet.*;
import java.util.*;
import javax.mail.search.FlagTerm;

import ufsic.providers.Record;
import ufsic.scheme.Attachment;
import ufsic.scheme.Attachments;
import ufsic.scheme.Message;
import ufsic.scheme.Messages;
import ufsic.scheme.SchemeTable;

public class ImapIncomingChannel extends IncomingMessageChannel {

  private Store store = null;
  private IMAPFolder folder = null;
  
  public ImapIncomingChannel() {
    super();
  }

  public ImapIncomingChannel(SchemeTable table, Record record) {
    super(table, record);
  }

  public ImapIncomingChannel(SchemeTable table) {
    super(table);
  }

  @Override
  protected boolean isConnected() {
    
    return isNotNull(folder);
  }
  
  public class ImapSSLSocketFactory extends com.sun.mail.util.MailSSLSocketFactory {

    public ImapSSLSocketFactory() throws GeneralSecurityException {
    }

    public ImapSSLSocketFactory(String protocol) throws GeneralSecurityException {
      super(protocol);
    }

  }
  
  @Override
  protected boolean connect() {
    
    if (!isConnected()) {
      
      Properties props = getProperties();
      if (isNotNull(props)) {

        final String userName = props.getProperty("UserName","").trim();
        final String password = props.getProperty("Password","").trim();
                
        Authenticator auth = new Authenticator() {
        
          protected PasswordAuthentication getPasswordAuthentication() {
            return new PasswordAuthentication(userName,password);
          }
        };                
        
        ImapSSLSocketFactory sf;
        try {
          sf = new ImapSSLSocketFactory();
          sf.setTrustAllHosts(true);
          props.put("mail.imaps.ssl.socketFactory", sf);
        } catch (GeneralSecurityException ex) {
          //
        }

        Session session = Session.getInstance(props,auth);
        try {
          Store st = session.getStore(props.getProperty("Store","imaps"));
        
          String host = props.getProperty("Host","");
          String port = props.getProperty("Port","");
          
          if (!port.equals("")) {
            st.connect(host,Integer.parseInt(port),userName,password);
          } else {
            st.connect(host,userName,password);
          }
          
          if (st.isConnected()) {
            
            String folderName = getProperties().getProperty("Folder","");
            
            IMAPFolder fl;
            if (folderName.equals("")) {
              fl = (IMAPFolder)st.getDefaultFolder();
            } else {
              fl = (IMAPFolder)st.getFolder(folderName);
            }

            if (!fl.isOpen()) {
              fl.open(Folder.READ_WRITE);
              store = st;
              folder = fl;
            }
          }
        } catch (Exception e) {
          //
        }  
      }
    }
    return isConnected();
  }
  
  @Override
  protected void disconnect() {

    if (isNotNull(folder)) {
      IMAPFolder fl = folder;
      try {
        folder = null;
        if (fl.isOpen()) {
          fl.close(true);
        }
      } catch (Exception e) {
        //
      }
    }
    if (isNotNull(store)) {
      Store st = store;
      try {
        store = null;
        st.close();
      } catch (Exception e) {
        //
      }
    }   
  }
  
/*  private String getBodyContent(BodyPart part) {
    
    String ret = null;
    try {
      Object content = part.getContent();
      if (isNotNull(content)) {
        if (content instanceof Multipart) {

          Multipart mp = (Multipart)content;
          int mpCount = mp.getCount();
          for (int i=0; i<mpCount; i++) {
            ret = getBodyContent(mp.getBodyPart(i));
            if (isNotNull(ret)) {
              if (!ret.equals("")) {
                break;
              }
            }
          }
        } else {
          ret = content.toString();
        }
      }
    } catch (Exception e) {
      ret = e.getMessage();
    }
    return ret;
  }

  private void setAttachments(BodyPart part, Attachments attachments) {

    Attachment att = new Attachment(attachments);
    try {
      try {
        String fileName = part.getFileName();
        
        if (isNotNull(fileName)) {
          
          int index = fileName.lastIndexOf(".");
          if (index>=0) {
            att.setExtension(fileName.substring(index+1));
            att.setName(fileName.substring(0,index));
          } else {
            att.setName(fileName);  
          }
        }
        
        att.setContentType(part.getContentType());
        
        Object content = part.getContent();
        if (isNotNull(content)) {
          if (content instanceof Multipart) {

            Multipart mp = (Multipart)content;
            int mpCount = mp.getCount();
            
            for (int i=0; i<mpCount; i++) {
              setAttachments(mp.getBodyPart(i),attachments);
            }
          } else {
            InputStream is = part.getInputStream();
            if (isNotNull(is)) {
              try (ByteArrayOutputStream b = new ByteArrayOutputStream()) {
                byte[] buf = new byte[1024];
                int count = 0;
                while ((count = is.read(buf))>0) {
                  b.write(buf,0,count);
                }
                att.setData(b.toByteArray());
              }
            }
          }
        }
      } catch (Exception e) {
        att.setData(e.getMessage());
      }
    } finally {
      attachments.add(att);
    }      
  }
  
  private void set(BodyPart part, Attachments attachments) {

    Attachment att = new Attachment(attachments);
    try {
      try {
        String fileName = part.getFileName();
        
        if (isNotNull(fileName)) {
          
          int index = fileName.lastIndexOf(".");
          if (index>=0) {
            att.setExtension(fileName.substring(index+1));
            att.setName(fileName.substring(0,index));
          } else {
            att.setName(fileName);  
          }
        }
        
        att.setContentType(part.getContentType());
        
        Object content = part.getContent();
        if (isNotNull(content)) {
          if (content instanceof Multipart) {

            Multipart mp = (Multipart)content;
            int mpCount = mp.getCount();
            
            for (int i=0; i<mpCount; i++) {
              setAttachments(mp.getBodyPart(i),attachments);
            }
          } else {
            InputStream is = part.getInputStream();
            if (isNotNull(is)) {
              try (ByteArrayOutputStream b = new ByteArrayOutputStream()) {
                byte[] buf = new byte[1024];
                int count = 0;
                while ((count = is.read(buf))>0) {
                  b.write(buf,0,count);
                }
                att.setData(b.toByteArray());
              }
            }
          }
        }
      } catch (Exception e) {
        att.setData(e.getMessage());
      }
    } finally {
      attachments.add(att);
    }      
  } */
  
  private void getContent(Multipart content, Message msg, Attachments atts) {
    
    try {
      int mpCount = content.getCount();
      for (int i=0; i<mpCount; i++) {

        BodyPart part = content.getBodyPart(i);
        String contentType = part.getContentType();
        String disposition = part.getDisposition();

        boolean body = false;
        if (isNotNull(contentType)) {
          body = contentType.toLowerCase().startsWith("text"); 
        }
        if (isNotNull(disposition)) {
          body = !(disposition.toLowerCase().equals(BodyPart.ATTACHMENT) ||
                   disposition.toLowerCase().equals(BodyPart.INLINE));
        }

        Object cont = part.getContent();
        if (isNotNull(cont)) {

          if (cont instanceof Multipart) {
            getContent((Multipart)cont,msg,atts);
          } else {
            
            if (body) {
              msg.setBody(cont.toString());
              msg.setContentType(contentType);
            } else {
              
              Attachment att = new Attachment(atts);
              try {
                
                try {
                  
                  String fileName = part.getFileName();
                  if (isNotNull(fileName)) {
                    
                    fileName = MimeUtility.decodeText(fileName);

                    int index = fileName.lastIndexOf(".");
                    if (index>=0) {
                      att.setExtension(fileName.substring(index+1));
                      att.setName(fileName.substring(0,index));
                    } else {
                      att.setName(fileName);  
                    }
                  }
                  
                  att.setContentType(contentType);
                  
                  String[] header = part.getHeader("Content-ID"); 
                  if (header.length>0) {
                    att.setContentId(header[0]); 
                  }
                  
                  InputStream is = part.getInputStream();
                  if (isNotNull(is)) {
                    try (ByteArrayOutputStream b = new ByteArrayOutputStream()) {
                      byte[] buf = new byte[1024];
                      int count = 0;
                      while ((count = is.read(buf))>0) {
                        b.write(buf,0,count);
                      }
                      att.setData(b.toByteArray());
                    }
                  }
                  
                } catch (Exception e) {
                  att.setData(e.getMessage());
                }
              } finally {
                atts.add(att);
              }
            }
          }
        }
      }
    } catch (Exception e) {
      msg.setBody(e.getMessage());
    }
  }
  
  private void getMessageContent(javax.mail.Message message, Message msg) {
    
    try {
      Attachments atts = msg.getAttachments(false);
      if (isNotNull(atts)) {
        
        Object content = message.getContent();
        if (isNotNull(content)) {

          if (content instanceof Multipart) {
            getContent((Multipart)content,msg,atts);
          } else {
            msg.setBody(content.toString());
          }
        }
      }
    } catch (Exception e) {
      msg.setError(e.getMessage());
    }
  }
  
  @Override
  public boolean receiveMessages() {
    
    boolean ret = false;
    
    try {
      if (isConnected()) {
        
        int count = folder.getUnreadMessageCount();
        if (count>0) {
          
          javax.mail.Message[] messages = folder.search(new FlagTerm(new Flags(Flags.Flag.SEEN),false));
          if (messages.length>0) {

            FetchProfile profile = new FetchProfile();
            profile.add(FetchProfile.Item.ENVELOPE);
            profile.add(FetchProfile.Item.CONTENT_INFO);

            folder.fetch(messages,profile);
            if (messages.length>0) {

              boolean f = true;
              
              for (javax.mail.Message message: messages) {

                Message msg = new Message();
                
                Address[] from = message.getFrom();
                if (isNotNull(from) && (from.length>0)) {

                  InternetAddress ia = new InternetAddress(from[0].toString());
                  msg.setSenderName(ia.getPersonal());
                  msg.setSenderContact(ia.getAddress());
                }

                Address[] recs = message.getRecipients(javax.mail.Message.RecipientType.TO);
                if (isNotNull(recs) && (recs.length>0)) {

                  InternetAddress ia = new InternetAddress(recs[0].toString());
                  msg.setRecipientName(ia.getPersonal());
                  msg.setRecipientContact(ia.getAddress());
                }

                msg.setSubject(message.getSubject());
                
                getMessageContent(message,msg);

                Date sent = message.getSentDate();
                if (isNotNull(sent)) {
                  msg.setSent(new java.sql.Timestamp(sent.getTime()));
                }

                Long uid = folder.getUID(message);
                msg.setRemoteId(uid.toString());

                f = f & incomingMessage(msg) & processMessage(msg);
              }
              ret = f;
            }
          }
        }
      }
    } catch (Exception e) {
      //
    }
    return ret;
  }
  
}
