package ufsic.gates;

import java.io.StringReader;
import java.lang.reflect.Constructor;
import java.util.Properties;
import java.util.concurrent.ScheduledFuture;
import java.util.concurrent.TimeUnit;
import javax.enterprise.concurrent.ManagedScheduledExecutorService;
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

public class MessageGateChannel extends Channel implements IMessageChannel {

  private final Messages messages = new Messages();
  private Properties properties = null;
  private MessageConnection connection = null;
  
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
    
    return super.getProvider();
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
          //
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
            //
          }
        } else {
          ret = true;
        }
      }
    }
    return ret;
  }

  protected boolean isConnected() {
    
    return isNotNull(connection) && connection.isConnected();
  }
  
  public Class getMessageConnectionClass() {
  
    return MessageConnection.class;
  }
  
  protected void beforeConnect() {
    //
  }
  
  protected boolean connect() {
    
    if (!isConnected()) {
      
      try {
        Class cls = getMessageConnectionClass();
        if (isNotNull(cls)) {
          Constructor con = cls.getConstructor(IMessageChannel.class);
          if (isNotNull(con)) {
            connection = (MessageConnection)con.newInstance(this);
            beforeConnect();
            connection.connect();
          }
        }
      } catch (Exception e) {
        //
      }
    }
    return isConnected();
  }
  
  protected void disconnect() {
    if (isConnected()) {
      MessageConnection con = connection;
      con.disconnect();
      connection = null;
    }
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
              
              Attachments atts = message.getAttachments(false);
              if (isNotNull(atts)) {
                
                for (Record rec: atts) {

                  Attachment att = (Attachment)rec;
                  if (att.getData().isNotNull()) {
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
        ret = gate.getClass(className);
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
      //
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
        if (isNotNull(p) && p.checkConnected()) {
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
        p.update(message.getTable().getName(),r,new Filter(Messages.MessageId,message.getMessageId()));
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
  
  private class OutgoingChannelTask extends MessageGateTask {

    public OutgoingChannelTask(IMessageGate gate, boolean withWait) {
      super(gate, withWait);
    }
    
  }
          
  public final boolean sendAll(boolean withWait) {
    
    boolean ret = false;
    if (!messages.isEmpty()) {
      
      MessageGateChannels channels = getChannels();
      if (isNotNull(channels)) {
      
        IMessageGate gate = getGate();
        OutgoingChannelTask task = new OutgoingChannelTask(gate,withWait) {
          
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
                disconnect();
              }
            } catch (Exception e) {
              //
            }
            return result;
          }
        };
        
        ret = execute(gate.getExecutor(),task,withWait);
      }
      
    } else {
      ret = true;
    }
    return ret;
  }
  

  protected boolean receiveMessages() {
    
    boolean ret = false;
    return ret;
  }
  
  private class IncomingChannelTask extends MessageGateTask {

    public IncomingChannelTask(IMessageGate gate, boolean withWait) {
      super(gate, withWait);
    }
    
  }
  
  public final boolean receiveAll(boolean withWait) {
    
    boolean ret = false;
      
    MessageGateChannels channels = getChannels();
    if (isNotNull(channels)) {

      IMessageGate gate = getGate();
      IncomingChannelTask task = new IncomingChannelTask(gate,withWait) {

        @Override
        public MessageGateTaskResult call() {

          MessageGateTaskResult result = new MessageGateTaskResult();
          try {
            try {
              if (connect()) {
                boolean f = receiveMessages();
                result.setSuccess(f);
              }
            } finally {
              disconnect();
            }
          } catch (Exception e) {
            //
          }
          return result;
        }
      };

      ret = execute(gate.getExecutor(),task,withWait);
    }
    return ret;
  }
  
}
