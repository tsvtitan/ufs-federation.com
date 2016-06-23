package ufsic.applications;

import java.util.Date;
import java.util.concurrent.ScheduledFuture;
import java.util.concurrent.TimeUnit;

import javax.annotation.Resource;
import javax.ejb.LocalBean;
import javax.ejb.Singleton;
import javax.ejb.Startup;

import javax.ejb.Timer;
import javax.enterprise.concurrent.ManagedScheduledExecutorService;

import ufsic.gates.IMessageGate;
import ufsic.gates.IMessageGateRemote;
import ufsic.gates.tasks.IncomingChannelsTask;
import ufsic.gates.tasks.MessageGateTask;
import ufsic.gates.tasks.MessageGateTaskResult;
import ufsic.gates.tasks.NotifyChannelsTask;
import ufsic.gates.tasks.OutgoingChannelsTask;
import ufsic.utils.Utils;

@Singleton
@Startup
@LocalBean
public class MessageGate extends TimerApplication implements IMessageGate, IMessageGateRemote {

  @Resource
  ManagedScheduledExecutorService executor;
  
  @Override
  public ManagedScheduledExecutorService getExecutor() {
    
    return this.executor;
  };

  @Override
  public void timerTimeout(Timer timer) {
    
    super.timerTimeout(timer);
    logInfo("before");
    try {
      
     //timer.cancel();
      
      checkIncoming();
      checkOutgoing();
      
    } finally {
      logInfo("after");
    }  
    
  }
  
  private boolean run(MessageGateTask task, boolean withWait) {
  
    boolean ret = false;
    
    logInfo(String.format("%s: %s",Utils.formatDate("H:m:s.S",new Date()),task.getClass().getSimpleName()));
    
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
    return ret;
  }
  
  @Override
  public boolean checkOutgoing() {
  
    return run(new OutgoingChannelsTask(this,null,false),false);
  }
  
  @Override
  public boolean checkOutgoing(String channelId) {
    
    boolean ret;
    if (isNotNull(channelId)) {
      ret = checkOutgoing();
    } else {
      ret = run(new OutgoingChannelsTask(this,channelId,false),false);
    }
    return ret;
  }
  
  @Override
  public boolean checkIncoming() {
    
    return run(new IncomingChannelsTask(this,null,false),false);
  }
  
  @Override
  public boolean checkIncoming(String channelId) {

    boolean ret;
    if (isNull(channelId)) {
      ret = checkIncoming();
    } else {
      ret = run(new IncomingChannelsTask(this,channelId,false),false);
    }
    return ret;
  }
  
  @Override
  public boolean notify(String channelId) {
    
    boolean ret = false;
    if (isNotNull(channelId)) {
      ret = run(new NotifyChannelsTask(this,channelId,false),false);
    }
    return ret;
  }
  
}
