package ufsic.gates.channels;

import java.io.StringReader;
import java.lang.reflect.Constructor;

import java.util.Date;

import java.util.Properties;
import java.util.Set;
import java.util.concurrent.ScheduledFuture;
import java.util.concurrent.TimeUnit;
import javax.enterprise.concurrent.ManagedScheduledExecutorService;

import ufsic.gates.IMessageGate;
import ufsic.gates.connections.MessageConnection;
import ufsic.gates.tasks.MessageGateTask;
import ufsic.gates.tasks.MessageGateTaskResult;
import ufsic.gates.MessageHandler;

import ufsic.providers.Filter;
import ufsic.providers.Params;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Attachment;
import ufsic.scheme.Attachments;
import ufsic.scheme.Channel;
import ufsic.scheme.Message;
import ufsic.scheme.Messages;
import ufsic.scheme.SchemeTable;
import ufsic.utils.Location;
import ufsic.utils.Utils;

public class MessageGateChannel extends Channel implements IMessageChannel {

  private final Messages messages = new Messages();
  private Properties properties = null;
  private MessageConnection connection = null;
  private Provider provider = null;
  
  public MessageGateChannel() {
    super();
  }
  
  public MessageGateChannel(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  public void copyFrom(Record source) {
    
    super.copyFrom(source);
    if (isNotNull(source) && (source instanceof MessageGateChannel)) {
      
     // MessageGateChannel channel = (MessageGateChannel)source;
    }
  }
  
  protected MessageConnection getConnection() {
  
    return this.connection;
  }
  
  public void addMessage(Message message) {
    
    messages.add(message);
  }

  @Override
  public Provider getProvider() {
    
    Provider ret = provider;
    if (isNull(ret)) {
      IMessageGate gate = getGate();
      if (isNotNull(gate)) {
        ret = gate.newProvider();
        provider = ret;
      } else {
        ret = super.getProvider();
      }
    }
    return ret;
  }
  
  private void providerDisconnect() {
    
    if (isNotNull(provider)) {
      provider.disconnect();
      provider = null;
    }
  }
  
  @Override
  public Messages getMessages() {
    
    return this.messages;
  }
  
  @Override
  public MessageGateChannels getChannels() {
  
    return (MessageGateChannels)getTable();
  }
  
  @Override
  public IMessageGate getGate() {
    
    IMessageGate ret = null;
    MessageGateChannels channels = getChannels();
    if (isNotNull(channels)) {
      ret = channels.getGate();
    }
    return ret;
  }
  
  @Override
  public Value getChannelId() {
    
    return super.getChannelId();
  }
  
  @Override
  public Properties getProperties() {
    
    Properties ret = properties;
    if (isNull(ret)) {
      ret = new Properties();
      
      Value settings = getSettings();
      if (settings.isNotNull()) {
        try {
          ret.load(new StringReader(settings.asString()));
        } catch (Exception e) {
          logException(e);
        }
      }
    }
    return ret;
  }
  
  private final boolean execute(ManagedScheduledExecutorService executor, MessageGateTask task, boolean withWait) {

    boolean ret = false;
    
    if (isNotNull(task) && isNotNull(executor)) {
      
      ScheduledFuture<MessageGateTaskResult> f = executor.schedule(task,0,TimeUnit.SECONDS);
      if (isNotNull(f)) {
        if (withWait) {
          try {
            MessageGateTaskResult result = f.get();
            if (isNotNull(result)) {
              ret = result.getSuccess();
            }
          } catch (Exception e) {
            logException(e);
          }
        } else {
          ret = true;
        }
      }
    }
    return ret;
  }

  private boolean connectionIsConnected() {
    
    return isNotNull(connection) && connection.isConnected();
  }
  
  protected boolean isConnected() {
    
    return connectionIsConnected();
  }
  
  public Class getMessageConnectionClass() {
  
    return null;
  }
  
  protected void beforeConnect() {
    //
  }
  
  protected boolean connect() {
    
    boolean ret = false;
    if (!connectionIsConnected()) {
      
      try {
        Class cls = getMessageConnectionClass();
        if (isNotNull(cls)) {
          Constructor con = cls.getConstructor(IMessageChannel.class);
          if (isNotNull(con)) {
            connection = (MessageConnection)con.newInstance(this);
            beforeConnect();
            connection.connect();
            ret = connectionIsConnected();
          }
        } else {
          ret = true;
        }
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  protected void disconnect() {
    
    if (connectionIsConnected()) {
      MessageConnection con = connection;
      con.disconnect();
    }
    connection = null;
    providerDisconnect();
  }
  
  public boolean incomingMessage(Message message, boolean checkPrior) {
    
    boolean ret = false;
    if (isNotNull(message)) {
      boolean next = true;
      if (checkPrior) {
        Message m = (Message)messages.findFirst(Messages.RemoteId,message.getRemoteId());
        next = isNull(m);
      }
      if (next) {
        Provider p = getProvider();
        if (isNotNull(p) && p.checkConnected()) {
          
          Params ps = new Params();
          ps.AddOut(Messages.MessageId);
          ps.AddOut("FOUND");
          ps.AddIn(Messages.CreatorId,getGate().getAccountId());
          ps.AddIn(Messages.ChannelId,getChannelId());
          ps.copyFrom(message);

          boolean r = p.execute("INCOMING_MESSAGE",ps);
          if (r) {
            
            Value messageId = ps.getValue(Messages.MessageId);
            ret = messageId.isNotNull();
            if (ret) {
              message.setMessageId(messageId);
              message.setCreatorId(ps.getValue(Messages.CreatorId));
              message.setChannelId(ps.getValue(Messages.ChannelId));
              messages.add(message);
            }
            
            Value exists = ps.getValue("FOUND");
            if (!exists.asBoolean()) {
              
              Attachments atts = message.getAttachments(false);
              if (isNotNull(atts)) {
                
                for (Record rec: atts) {

                  Attachment att = (Attachment)rec;
                  att.setAttachmentId(p.getUniqueId());
                  att.setMessageId(messageId);
                  p.insert(Attachments.TableName,att);
                  
                }
              }
            }
          }
        }
      }
    }
    return ret;
  }
  
  @Override
  public boolean incomingMessage(Message message) {
    
    return incomingMessage(message,true);
  }
  
  private Class getMessageHandlerClass(String className) {
  
    Class ret = null;
    if (!className.equals("")) {
      IMessageGate gate = getGate();
      if (isNotNull(gate)) {
        String pkg = MessageHandler.class.getPackage().getName();
        ret = gate.getClass(pkg.concat(".handlers"),className);
      }
    }
    return ret;
  }
  
  private boolean processByHandler(Message message, String handlerClass) {
    
    boolean ret = false;
    try {
      Class cls = getMessageHandlerClass(handlerClass);
      if (isNotNull(cls)) {
        Constructor con = cls.getConstructor(IMessageChannel.class);
        if (isNotNull(con)) {
          MessageHandler handler = (MessageHandler)con.newInstance(this);
          ret = handler.process(message);
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  @Override
  public boolean processMessage(Message message) {
    
    boolean ret = false;
    if (isNotNull(message)) {
      
      Value messageId = message.getMessageId();
      if (messageId.isNotNull()) {
        
        Provider p = getProvider();
        if (isNotNull(p)) {
          Params ps = new Params();
          ps.AddIn(Messages.MessageId,messageId);
          ps.AddOut("HANDLER_CLASS");
          
          boolean r = p.execute("PROCESS_MESSAGE",ps);
          if (r) {
            Value handlerClass = ps.getValue("HANDLER_CLASS");
            if (handlerClass.isNotNull()) {
              ret = processByHandler(message,handlerClass.asString());
            } else {
              ret = true;
            }
          }
        }
      }
    }
    return ret;
  }
  
  private void unlockMessage(Message message, boolean sent) {
    
    if (isNotNull(message)) {
      Provider p = getProvider();
      if (isNotNull(p) && p.checkConnected()) {
        Record r = new Record();
        if (sent) {
          if (message.getSenderId().isNull()) {
            r.add(Messages.SenderId,getGate().getAccountId());
          }
          r.add(Messages.Sent,p.getNow());
          r.add(Messages.ChannelId,message.getChannelId());
          r.add(Messages.Error,null);
          r.add(Messages.RemoteId,message.getRemoteId());
        } else {
          r.add(Messages.Error,message.getError());
        }
        r.add(Messages.LockId,null);
        p.update(Messages.TableName,r,new Filter(Messages.MessageId,message.getMessageId()));
      }
    }
  }
  
  protected boolean sendMessage(Message message) {
    
    boolean ret = false;
    if (isNotNull(message)) {
      message.setChannelId(getChannelId());
      ret = true;
    }
    return ret;
  }
  
  protected boolean sendMessages() {

    boolean ret = false;
    if (isNotNull(messages)) {

      boolean f = true;
      
      for (Record r: messages) {

        Message m = (Message)r;
        boolean sent = false;
        try {
          m.setProvider(getProvider());
          sent = sendMessage(m);
          f = f & sent;
        } finally {
          unlockMessage(m,sent);
        }
      }
      ret = f;
    }
    return ret;
  }
  
  public boolean canSend() {
  
    return !messages.isEmpty();
  }
  
  public final synchronized boolean sendAll(boolean withWait) {
    
    boolean ret = false;
    
    if (canSend()) {
      
      MessageGateChannels channels = getChannels();
      if (isNotNull(channels)) {
      
        final IMessageGate gate = getGate();
        if (isNotNull(gate)) {
          
          gate.getOutgoingCounter().incrementAndGet();

          class OutgoingTask extends MessageGateTask {

            public OutgoingTask(IMessageGate gate, boolean withWait) {
              super(gate, withWait);
            }
          }

          OutgoingTask task = new OutgoingTask(gate,withWait) {

            @Override
            public MessageGateTaskResult call() {

              MessageGateTaskResult result = new MessageGateTaskResult();
              try {
                try {
                  if (connect()) {
                    boolean f = sendMessages();
                    result.setSuccess(f);
                  }
                } finally {
                  gate.getOutgoingCounter().decrementAndGet();
                  disconnect();
                }
              } catch (Exception e) {
                logException(e);
              }
              return result;
            }
          };

          ret = execute(gate.getExecutor(),task,withWait);
        }
      }
      
    } else {
      ret = true;
    }
    return ret;
  }
  

  protected boolean receiveMessages(boolean withWait) {
    
    boolean ret = false;
    return ret;
  }
  
  public boolean canReceive() {
  
    return false;
  }
  
  public final synchronized boolean receiveAll(final boolean withWait) {
    
    boolean ret = false;
    
    if (canReceive()) {
      
      MessageGateChannels channels = getChannels();
      if (isNotNull(channels)) {

        IMessageGate gate = getGate();
        
        class IncomingTask extends MessageGateTask {

          public IncomingTask(IMessageGate gate, boolean withWait) {
            super(gate, withWait);
          }
        }
        
        IncomingTask task = new IncomingTask(gate,withWait) {

          @Override
          public MessageGateTaskResult call() {

            MessageGateTaskResult result = new MessageGateTaskResult();
            try {
              try {
                if (connect()) {
                  boolean f = receiveMessages(withWait);
                  result.setSuccess(f);
                }
              } finally {
                disconnect();
              }
            } catch (Exception e) {
              logException(e);
            }
            return result;
          }
        };

        ret = execute(gate.getExecutor(),task,withWait);
      }
    }
    return ret;
  }
  
  private boolean setMessageDelivered(Message message, Value stamp, boolean delivered, String error) {

    boolean ret = false;
    if (isNotNull(message) && message.getMessageId().isNotNull()) {
      Provider p = getProvider();
      if (isNotNull(p)) {
        Record r = new Record();
        if (delivered) {
          r.add(Messages.Delivered,stamp);
          r.add(Messages.Error,new Value(null));
        } else {  
          r.add(Messages.Delivered,message.getDelivered());
          r.add(Messages.Error,error);
        }
        ret = p.update(Messages.TableName,r,new Filter(Messages.MessageId,message.getMessageId()));
        if (ret) {
          message.setDelivered(r.getValue(Messages.Delivered));
          message.setError(r.getValue(Messages.Error));
        }
      }
    }
    return ret;
  }
          
  public boolean canGetStatuses() {
  
    boolean ret = false;
    Properties props = getProperties();
    if (isNotNull(props)) {
      
      String statusesUrl = props.getProperty("StatusesUrl",null);
      ret = !isEmpty(statusesUrl);
    }
    return ret;
  }
  
  protected boolean getStatuses() {
    
    boolean ret = false;
    
    Properties props = getProperties();
    if (isNotNull(props)) {
      
      String statusesUrl = props.getProperty("StatusesUrl",null);
      if (!isEmpty(statusesUrl)) {
       
        int offset = Utils.toInteger(props.getProperty("StatusesOffset",null),-3600);
        Date date = Utils.addSeconds(new Date(),offset);
        Long time = date.getTime() / 1000L;
        
        statusesUrl = statusesUrl.replaceAll("%time",time.toString());
        
        logInfo(String.format("getStatuses url => %s",statusesUrl));
        
        Location location = new Location(statusesUrl);
        if (location.exists()) {
          
          Properties ps = location.getProperties();
          if (isNotNull(ps) && !ps.isEmpty()) {
            
            logInfo(String.format("getStatuses count => %d",ps.size()));
            
            String domainName = "@"+props.getProperty("DomainName","").toLowerCase();
            Integer messageIdLength = Utils.getUniqueId().length();
            
            Provider p = getProvider();
            if (isNotNull(p)) {

              messages.setProvider(p);
              Value stamp = p.getNow();
              
              ret = true;
              
              Set<String> set = ps.stringPropertyNames();
              for (String s: set) {

                if (s.toLowerCase().endsWith(domainName)) {

                  String[] temp = s.split("@");
                  if (temp.length>1) {

                    String status = ps.getProperty(s);
                    if (!isEmpty(status)) {

                      String messageId = temp[0];

                      if (messageId.length()==messageIdLength) {

                        boolean delivered = status.toLowerCase().startsWith("sent");
                        
                        Filter filter = new Filter(Messages.MessageId,messageId);
                        filter.And(Messages.Sent).IsNotNull();
                        filter.And(Messages.Locked).IsNull();
                        if (delivered) {
                          filter.And(Messages.Delivered).IsNull();
                        }
                        
                        Message message = messages.first(filter);
                        if (isNotNull(message)) {
                          
                          boolean r = setMessageDelivered(message,stamp,delivered,status) && processMessage(message);
                          ret = ret && r;
                        }
                        
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    return ret;
  }
          
  public final synchronized boolean getStatuses(final boolean withWait) {
   
    boolean ret = false;
    
    if (canGetStatuses()) {
      
      MessageGateChannels channels = getChannels();
      if (isNotNull(channels)) {

        IMessageGate gate = getGate();
        
        class StatusesTask extends MessageGateTask {

          public StatusesTask(IMessageGate gate, boolean withWait) {
            super(gate, withWait);
          }
        }
        
        StatusesTask task = new StatusesTask(gate,withWait) {

          @Override
          public MessageGateTaskResult call() {

            MessageGateTaskResult result = new MessageGateTaskResult();
            try {
              try {
                
                boolean f = getStatuses();
                result.setSuccess(f);
                
              } finally {
                providerDisconnect();
              }
            } catch (Exception e) {
              logException(e);
            }
            return result;
          }
        };

        ret = execute(gate.getExecutor(),task,withWait);
      }
    }
    return ret;
  }
}
