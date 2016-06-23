package ufsic.gates;

import java.util.concurrent.ScheduledFuture;
import java.util.concurrent.TimeUnit;
import javax.annotation.PostConstruct;
import javax.annotation.PreDestroy;
import javax.annotation.Resource;
import javax.ejb.LocalBean;
import javax.ejb.Singleton;
import javax.ejb.Startup;
import javax.enterprise.concurrent.ManagedScheduledExecutorService;

import ufsic.controllers.Options;
import ufsic.providers.Value;
import ufsic.utils.Utils;

@Singleton
@Startup
@LocalBean
public class MessageGate implements IMessageGate {

  @Resource
  ManagedScheduledExecutorService executor;
  
  private String jndiName;
  private Options options = null;
  private Value accountId = new Value(null);
  
  @PostConstruct
  private void startup() {
    
    options = new Options(String.format("%s.Options",MessageGate.class.getCanonicalName()));
    jndiName = options.getProperty("OracleProvider.JNDI","jdbc/work");
    accountId.setObject(options.getProperty("Application.ID",MessageGate.class.getSimpleName()));
  }

  @PreDestroy
  private void shutdown() {
    //provider.disconnect();
  }
  
  private boolean isNull(Object obj) {
    return Utils.isNull(obj);
  }
  
  private boolean isNotNull(Object obj) {
    return Utils.isNotNull(obj);
  }

  @Override
  public String getJndiName() {
    
    return this.jndiName;
  }
  
  @Override
  public Value getAccountId() {
    
    return this.accountId;
  }
  
  @Override
  public ManagedScheduledExecutorService getExecutor() {
    
    return this.executor;
  };
  
  @Override
  public Class getClass(String className) {
  
    Class ret = null;
    if (!className.equals("")) {
      try {
        String pkg = this.getClass().getPackage().getName();
        String name = String.format("%s.%s",pkg,className);
        ret = this.getClass().getClassLoader().loadClass(name);
      } catch (Exception e) {
        //
      }
    }
    return ret;
  }
  
  private boolean run(MessageGateTask task, boolean withWait) {
  
    boolean ret = false;
    
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
    return ret;
  }
  
  public boolean checkOutgoing() {
  
    return run(new OutgoingChannelsTask(this,null,false),false);
  }
  
  public boolean checkOutgoing(String channelId) {
    
    boolean ret;
    if (isNotNull(channelId)) {
      ret = checkOutgoing();
    } else {
      ret = run(new OutgoingChannelsTask(this,channelId,false),false);
    }
    return ret;
  }
  
  public boolean checkIncoming() {
    
    return run(new IncomingChannelsTask(this,null,false),false);
  }
  
  public boolean checkIncoming(String channelId) {

    boolean ret;
    if (isNull(channelId)) {
      ret = checkIncoming();
    } else {
      ret = run(new IncomingChannelsTask(this,channelId,false),false);
    }
    return ret;
  }
  
  public boolean notify(String channelId) {
    
    boolean ret = false;
    if (isNotNull(channelId)) {
      ret = run(new NotifyChannelsTask(this,channelId,false),false);
    }
    return ret;
  }
  
}
