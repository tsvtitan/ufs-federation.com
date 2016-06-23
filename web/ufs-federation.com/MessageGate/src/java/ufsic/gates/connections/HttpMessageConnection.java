package ufsic.gates.connections;

import ufsic.gates.channels.IMessageChannel;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Properties;
import ufsic.gates.IHttpProcessHandle;
import ufsic.scheme.Message;
import ufsic.scheme.Messages;

public class HttpMessageConnection extends MessageConnection  {
  
  private HttpURLConnection connection = null; 
  private String lastError = null;

  public HttpMessageConnection() {
    super();
  }

  public HttpMessageConnection(Properties properties) {
    super(properties);
  }

  public HttpMessageConnection(IMessageChannel channel) {
    super(channel);
  }
  
  public HttpURLConnection getConnection() {
    
    return connection;
  }
  
  public enum Method {
    
    GET, POST;
    
    public static String asString(Method method) {

      String ret = "";
      switch (method) {
        case GET: { ret = "GET"; break; }
        case POST: { ret = "POST"; break; }
        default: break;
      }
      return ret;
    }
    
    public String asString() {
      
      return Method.asString(this);
    }
  }
          
  public Method getMethod() {
    
    Method ret = Method.GET;
    Properties props = getProperties();
    if (isNotNull(props)) {
      String method = props.getProperty("Method","GET").toUpperCase();
      switch (method) {
        case "GET": { ret = Method.GET; break; }
        case "POST": { ret = Method.POST; break; }
        default: break;
      }
    }
    return ret;
  }

  public String getUserAgent() {
    
    String ret = "";
    Properties props = getProperties();
    if (isNotNull(props)) {
      ret = props.getProperty("UserAgent",ret);
      
      if (ret.equals("")) {
        IMessageChannel channel = getChannel();
        if (isNotNull(channel)) {
          ret = channel.getChannels().getGate().getAccountId().asString();
        }
      }
    }
    return ret;
  }
  
  public String getUrl() {
    
    String ret = "";
    Properties props = getProperties();
    if (isNotNull(props)) {
      ret = props.getProperty("Url",ret);
    }
    return ret;
  }
  
  @Override
  public boolean isConnected() {
    
    return isNotNull(connection);
  }
  
  @Override
  public boolean connect() {
    
    if (!isConnected()) {
      
      URL url;
      try {
        url = new URL(getUrl());
        Method method = getMethod();

        HttpURLConnection con = (HttpURLConnection)url.openConnection();
        con.setUseCaches(false);
        con.setRequestMethod(method.asString());
        con.setRequestProperty("User-Agent",getUserAgent());

        if (method==Method.POST) {
          con.setDoOutput(true);
        }
        connection = con;

      } catch (Exception e) {
        lastError = e.getMessage();
        logException(e);
      }  
    }
    return isConnected();
  }
  
  public boolean prepareConnection(HttpURLConnection connection) {
    
    return false;
  }
  
  public class HttpProcessHandle implements IHttpProcessHandle {
  
    private Message message = null;
    
    public HttpProcessHandle() {
    }
    
    public HttpProcessHandle(Message message) {
      this.message = message;  
    }
    
    @Override
    public Message getMessage() {
      return message;
    }
  }
  
  public boolean processOutputStream(HttpURLConnection connection, OutputStream stream, IHttpProcessHandle handle) {
    
    return false;
  }

  public boolean processErrorStream(HttpURLConnection connection, InputStream stream, IHttpProcessHandle handle) {
    
    return false;
  }
  
  public boolean processInputStream(HttpURLConnection connection, InputStream stream, IHttpProcessHandle handle) {
    
    return false;
  }
  
  private boolean processGet(HttpURLConnection connection, IHttpProcessHandle handle) {
    
    boolean ret = prepareConnection(connection);
    try {
      if (ret) {
        InputStream is = connection.getErrorStream();
        if (isNotNull(is)) {
          ret = processErrorStream(connection,is,handle);
        } else  {
          is = connection.getInputStream();
          if (isNotNull(is)) {
            ret = processInputStream(connection,is,handle);
          }
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  private boolean processPost(HttpURLConnection connection, IHttpProcessHandle handle) {
    
    boolean ret = prepareConnection(connection);
    try {
      if (ret) {
        OutputStream os = connection.getOutputStream();
        if (processOutputStream(connection,os,handle)) {
          InputStream is = connection.getErrorStream();
          if (isNotNull(is)) {
            ret = processErrorStream(connection,is,handle);
          } else  {
            is = connection.getInputStream();
            if (isNotNull(is)) {
              ret = processInputStream(connection,is,handle);
            }
          }
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }

  public boolean sendMessage(Message message) {
    
    boolean ret = false;
    
    if (isConnected() && isNotNull(message)) {

      try {
        Method method = getMethod();
        switch (method) {
          case GET: { ret = processGet(getConnection(),new HttpProcessHandle(message)); break; }
          case POST: { ret = processPost(getConnection(),new HttpProcessHandle(message)); break; }
          default: break;
        }
      } catch (Exception e) {
        message.setError(e.getMessage());
        logException(e);
      }
    }
    return ret;
  }

  public boolean receiveMessages() {
    
    boolean ret = false;
    
    if (isConnected()) {
      
      IMessageChannel channel = getChannel();
      if (isNotNull(channel)) {

        Messages messages = channel.getMessages();
        if (isNotNull(messages)) {
          try {
            Method method = getMethod();
            switch (method) {
              case GET: { ret = processGet(getConnection(),new HttpProcessHandle()); break; }
              case POST: { ret = processPost(getConnection(),new HttpProcessHandle()); break; }
              default: break;
            }
          } catch (Exception e) {
            logException(e);
          }
        }
      }
    }
    return ret;
  }
  
  
}
