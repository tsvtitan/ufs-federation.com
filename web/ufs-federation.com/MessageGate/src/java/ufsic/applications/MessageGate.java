package ufsic.applications;


import java.util.Date;
import java.util.concurrent.ScheduledFuture;
import java.util.concurrent.TimeUnit;
import java.util.concurrent.atomic.AtomicInteger;

import javax.annotation.Resource;
import javax.ejb.LocalBean;
import javax.ejb.Singleton;
import javax.ejb.Startup;

import javax.ejb.Timer;
import javax.enterprise.concurrent.ManagedScheduledExecutorService;

import ufsic.gates.IMessageGate;
import ufsic.gates.IMessageGateRemote;
import ufsic.gates.tasks.IncomingTask;
import ufsic.gates.tasks.MessageGateTask;
import ufsic.gates.tasks.MessageGateTaskResult;
import ufsic.gates.tasks.NotifyTask;
import ufsic.gates.tasks.OutgoingTask;
import ufsic.gates.tasks.StatusesTask;
import ufsic.utils.Utils;

@Singleton
@Startup
@LocalBean
public class MessageGate extends TimerApplication implements IMessageGate, IMessageGateRemote {

  @Resource
  ManagedScheduledExecutorService executor;
  
  private final String incomingTimer = "IncomingTimer";
  private final String outgoingTimer = "OutgoingTimer";
  private final String statusesTimer = "StatusesTimer";
  private final AtomicInteger outgoingCounter = new AtomicInteger(0);
  
  @Override
  public ManagedScheduledExecutorService getExecutor() {
    
    return this.executor;
  }
  
  @Override
  public AtomicInteger getOutgoingCounter() {
    
    return this.outgoingCounter;
  }

  @Override
  public void timeout(Timer timer) {
    
    super.timeout(timer);
    
    String name = getTimerName(timer);
    if (!isEmpty(name)) {
      
      switch (name) {
        case incomingTimer: checkIncoming(); break;
        case outgoingTimer: checkOutgoing(); break;
        case statusesTimer: checkStatuses(); break;  
      }
    }
  }
  
  @Override
  public void reloadOptions() { 
    
    super.reloadOptions();
    
    newTimer(incomingTimer); 
    newTimer(outgoingTimer);
    newTimer(statusesTimer);
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
  
    return run(new OutgoingTask(this,null,false),false);
  }
  
  @Override
  public boolean checkOutgoing(String channelId) {
    
    boolean ret;
    if (isEmpty(channelId)) {
      ret = checkOutgoing();
    } else {
      ret = run(new OutgoingTask(this,channelId,false),false);
    }
    return ret;
  }
  
  @Override
  public boolean checkIncoming() {
    
    return run(new IncomingTask(this,null,false),false);
  }
  
  @Override
  public boolean checkIncoming(String channelId) {

    boolean ret;
    if (isEmpty(channelId)) {
      ret = checkIncoming();
    } else {
      ret = run(new IncomingTask(this,channelId,false),false);
    }
    return ret;
  }
  
  @Override
  public boolean checkStatuses() {
    
    return run(new StatusesTask(this,null,false),false);
  }
  
  @Override
  public boolean checkStatuses(String channelId) {

    boolean ret;
    if (isEmpty(channelId)) {
      ret = checkIncoming();
    } else {
      ret = run(new StatusesTask(this,channelId,false),false);
    }
    return ret;
  }
  
  @Override
  public boolean notify(String channelId) {
    
    boolean ret = false;
    if (isEmpty(channelId)) {
      ret = run(new NotifyTask(this,channelId,false),false);
    }
    return ret;
  }
  
}
