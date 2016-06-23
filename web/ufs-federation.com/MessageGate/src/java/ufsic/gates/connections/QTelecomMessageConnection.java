package ufsic.gates.connections;

import ufsic.gates.connections.HttpMessageConnection;
import ufsic.gates.channels.IMessageChannel;
import ufsic.gates.IHttpProcessHandle;
import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.Properties;
import java.util.zip.GZIPInputStream;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.w3c.dom.Document;
import org.w3c.dom.NamedNodeMap;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import ufsic.providers.Value;
import ufsic.scheme.Message;
import ufsic.utils.Utils;

public class QTelecomMessageConnection extends HttpMessageConnection {

  public enum Action {
  
    POST_SMS, INBOX;
    
    public static String asString(Action action) {
      
      String ret = "";
      switch (action) {
        case POST_SMS: { ret = "post_sms"; break; }
        case INBOX: { ret = "inbox"; break; }
        default: break;
      }
      return ret;
    }
    
    public String asString() {
      
      return Action.asString(this);
    }
  }
  
  private Action action = Action.POST_SMS;
  private static String dateFormat = "dd.MM.yyyy HH:mm:ss";
  
  public QTelecomMessageConnection() {
  }

  public QTelecomMessageConnection(Properties properties) {
    super(properties);
  }

  public QTelecomMessageConnection(IMessageChannel channel) {
    super(channel);
  }

  @Override
  public Method getMethod() {
     
    return Method.POST;
  }
  
  public void setAction(Action action) {
  
    this.action = action;
  }
  
  private String getEncoding() {
    
    return getProperties().getProperty("Encoding","UTF-8");
  }
  
  @Override
  public boolean prepareConnection(HttpURLConnection connection) {
    
    boolean ret = false;
    String userName = getProperties().getProperty("UserName","").trim();
    if (!userName.equals("")) {
      connection.setRequestProperty("Content-Type",String.format("application/x-www-form-urlencoded; charset=%s",getEncoding()));
      ret = true;
    }
    return ret;
  }

  private String getSender(Message message) {
  
    String ret = getProperties().getProperty("FromName","");
    
    Value senderName = message.getSenderName();
    if (senderName.isNotNull()) {
      ret = senderName.asString();
    }
    return ret;
  }
  
  private String buildPostSmsParams(Message message) {

    String ret = "";
    if (isNotNull(message)) {
      try {
        String encoding = getEncoding();

        String userName = URLEncoder.encode(getProperties().getProperty("UserName",""),encoding);
        String password = URLEncoder.encode(getProperties().getProperty("Password",""),encoding);
        String saction = URLEncoder.encode(action.asString(),encoding);
        String sender = URLEncoder.encode(getSender(message),encoding);
        String target = URLEncoder.encode(message.getRecipientContact().asString(),encoding);
        String text = URLEncoder.encode(message.getBody().asString(),encoding);
        String postId = URLEncoder.encode(message.getMessageId().asString(),encoding);
        String smsType = URLEncoder.encode(getProperties().getProperty("SmsType",""),encoding);

        ret = String.format("user=%s&pass=%s&action=%s&sender=%s&target=%s&message=%s&post_id=%s&sms_type=%s",
                             userName,password,saction,sender,target,text,postId,smsType);

      } catch (Exception e) {
        message.setError(e.getMessage());
        logException(e);
      }
    }
    return ret;
  }
  
  private boolean writePostSmsAsHttp(OutputStream stream, Message message) {
    
    boolean ret = false;
    String params = buildPostSmsParams(message);
    if (!params.equals("")) {
      try {
        try (DataOutputStream dos = new DataOutputStream(stream)) {
          dos.writeBytes(params);
          dos.flush();
        }
        ret = true;
      } catch (Exception e) {
        message.setError(e.getMessage());
        logException(e);
      }
    }
    return ret;
  }
  
  private boolean writePostSmsAsXml(OutputStream stream, Message message) {
    
    return false;
  }
  
  private Date getDateFrom(Date def) {
    
    Date ret = def;
    return ret;
  }
  
  private String buildInboxParams() {

    String ret = "";
    
    Properties props = getProperties();
    if (isNotNull(props)) {
    
      try {
        String encoding = getEncoding();

        String userName = URLEncoder.encode(props.getProperty("UserName",""),encoding);
        String password = URLEncoder.encode(props.getProperty("Password",""),encoding);
        String saction = URLEncoder.encode(action.asString(),encoding);
        String sibNum = URLEncoder.encode(props.getProperty("InboxId","-"),encoding);
        String newOnly = URLEncoder.encode(props.getProperty("NewOnly","0"),encoding);

        String seconds = props.getProperty("Seconds","-3600");

        Date stamp = new Date();

        Calendar cal = Calendar.getInstance();
        cal.setTime(stamp);
        cal.add(Calendar.SECOND,Integer.parseInt(seconds));

        String dateFrom = Utils.formatDate(dateFormat,getDateFrom(new Date(cal.getTimeInMillis())));
        dateFrom = URLEncoder.encode(dateFrom,encoding);

        String dateTo = Utils.formatDate(dateFormat,stamp);
        dateTo = URLEncoder.encode(dateTo,encoding);

        ret = String.format("user=%s&pass=%s&action=%s&sib_num=%s&new_only=%s&date_from=%s&date_to=%s",
                             userName,password,saction,sibNum,newOnly,dateFrom,dateTo);

      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
 
  private boolean writeInboxAsHttp(OutputStream stream) {
    
    boolean ret = false;
    String params = buildInboxParams();
    if (!params.equals("")) {
      try {
        try (DataOutputStream dos = new DataOutputStream(stream)) {
          dos.writeBytes(params);
          dos.flush();
        }
        ret = true;
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  @Override
  public boolean processOutputStream(HttpURLConnection connection, OutputStream stream, IHttpProcessHandle handle) {

    boolean ret = false;
    
    Properties props = getProperties();
    if (isNotNull(props)) {
      
      switch (action)  {
        case POST_SMS: {
          String protocol = props.getProperty("Protocol","HTTP").trim().toUpperCase();
          switch (protocol) {
            case "HTTP": { ret = writePostSmsAsHttp(stream,handle.getMessage()); break; }
            case "XML": { ret = writePostSmsAsXml(stream,handle.getMessage()); break;}
            default: break;
          }
          break;
        }
        case INBOX: {
          ret = writeInboxAsHttp(stream);
          break;
        }
      }
    }
    return ret;
  }

  private boolean readPostSms(InputStream stream, Message message) {
    
    boolean ret = false;
    if (isNotNull(message)) {

      try {

        DocumentBuilder builder = DocumentBuilderFactory.newInstance().newDocumentBuilder();
        Document doc = builder.parse(stream);
        if (doc.hasChildNodes()) {

          NodeList smsList = doc.getElementsByTagName("sms");
          if (isNotNull(smsList)) {

            for (int i=0; i<smsList.getLength(); i++) {

              Node node = smsList.item(i);
              if (node.hasAttributes()) {

                NamedNodeMap map = node.getAttributes();
                Node postId = map.getNamedItem("post_id");
                if (isNotNull(postId)) {
                  
                  if (message.getMessageId().same(postId.getNodeValue())) {

                    Node id = map.getNamedItem("id");
                    if (isNotNull(id)) {
                      message.setRemoteId(id.getNodeValue());
                    }
                    ret = true;
                    break;
                  }
                }
              }
            }
          }
        
          if (!ret) {
            NodeList errorList = doc.getElementsByTagName("error");
            if (isNotNull(errorList)) {

              for (int i=0; i<errorList.getLength(); i++) {

                Node node = errorList.item(i);
                if (node.hasAttributes()) {

                  NamedNodeMap map = node.getAttributes();
                  Node postId = map.getNamedItem("post_id");
                  if (isNotNull(postId)) {
                    if (message.getMessageId().same(postId.getNodeValue())) {
                      message.setError(node.getTextContent());
                      ret = false;
                      break;
                    }
                  }
                }
              }
            }
          }
        }
        
      } catch (Exception e) {
        message.setError(e.getMessage());
        logException(e);
      }
    }
    return ret;
  }
  
  private boolean readInbox(InputStream stream, IMessageChannel channel) {
    
    boolean ret = false;
    if (isNotNull(channel)) {
      
      try {

        DocumentBuilder builder = DocumentBuilderFactory.newInstance().newDocumentBuilder();
        Document doc = builder.parse(stream);
        if (doc.hasChildNodes()) {

          NodeList messageList = doc.getElementsByTagName("MESSAGE");
          if (isNotNull(messageList)) {

            boolean f = true;
            
            for (int i=0; i<messageList.getLength(); i++) {

              Node messageNode = messageList.item(i);

              Message msg = new Message();

              Date sent = null;

              for (int y=0; y<messageNode.getChildNodes().getLength(); y++) {

                Node node = messageNode.getChildNodes().item(y);

                String n = node.getNodeName().toUpperCase();
                switch (n) {
                  case "CREATED": { 
                    String v = node.getTextContent();
                    SimpleDateFormat sdf = new SimpleDateFormat(dateFormat);
                    try {
                      sent = sdf.parse(v);
                    } catch (Exception e) {
                      logException(e);
                    }
                    break; 
                  }
                  case "SMS_SENDER": { 
                    msg.setSenderId(null); // read from accounts based on email
                    msg.setSenderContact(node.getTextContent());
                    break; 
                  }
                  case "SMS_TARGET": { 
                    msg.setRecipientId(null); // read from accounts based on email
                    msg.setRecipientContact(node.getTextContent());
                    break; 
                  }
                  case "SMS_RES_COUNT": { break; }
                  case "SMS_TEXT": { 
                    msg.setBody(node.getTextContent());
                    break; 
                  }
                  case "SMS_STATUS": { break; }
                  default: break;
                }
              }

              if (isNotNull(sent)) {
                msg.setSent(new java.sql.Timestamp(sent.getTime()));
              }

              if (messageNode.hasAttributes()) {

                NamedNodeMap map = messageNode.getAttributes();
                Node smsId = map.getNamedItem("SMS_ID");
                if (isNotNull(smsId)) {
                  msg.setRemoteId(smsId.getNodeValue());
                }
              }
              
              boolean b = channel.incomingMessage(msg) && channel.processMessage(msg);
              if (!b) {
                // need to set the message status as unread
              }
              f = f && b;
            }
            ret = f;
          }
        }
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  @Override
  public boolean processInputStream(HttpURLConnection connection, InputStream stream, IHttpProcessHandle handle) {
    
    boolean ret = false;
    if (isNotNull(stream)) {
      
      try {
        InputStream temp = stream;

        String encoding = connection.getContentEncoding();
        if (isNotNull(encoding)) {
          encoding = encoding.toLowerCase();
          if (encoding.equals("gzip")) {
            temp = new GZIPInputStream(stream);  
          }
        }

        /*StringBuilder sb = new StringBuilder();
        BufferedReader reader = new BufferedReader(new InputStreamReader(temp));
        String line;
        while ((line = reader.readLine())!=null) {
          sb.append(line);
        }
        temp.setPosition(0);        
        */
        
        switch (action) {
          case POST_SMS: { ret = readPostSms(temp,handle.getMessage()); break; }
          case INBOX: { ret = readInbox(temp,getChannel()); break; }
          default: break;
        }
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  
}
