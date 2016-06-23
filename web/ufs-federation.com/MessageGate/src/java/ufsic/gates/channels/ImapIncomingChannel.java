package ufsic.gates.channels;

import com.sun.mail.imap.IMAPFolder;

import java.io.ByteArrayOutputStream;
import java.io.InputStream;

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
          logException(ex);
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
          logException(e);
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
        logException(e);
      }
    }
    if (isNotNull(store)) {
      Store st = store;
      try {
        store = null;
        st.close();
      } catch (Exception e) {
        logException(e);
      }
    }
    super.disconnect();
  }
  
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
                  if (isNotNull(header) && header.length>0) {
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
                  logException(e);
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
      logException(e);
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
      logException(e);
    }
  }
  
  @Override
  public boolean receiveMessages(boolean withWait) {
    
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

              folder.setFlags(messages,new Flags(Flags.Flag.SEEN),true);
              
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

                boolean b = incomingMessage(msg) && processMessage(msg);
                if (!b) {
                  folder.setFlags(new javax.mail.Message[] {message},new Flags(Flags.Flag.SEEN),false);
                }               
                f = f && b;
              }
              ret = f;
            }
          }
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
}
