package ufsic.applications;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.HashMap;
import javax.annotation.Resource;
import javax.ejb.ScheduleExpression;
import javax.ejb.Timeout;
import javax.ejb.Timer;
import javax.ejb.TimerConfig;
import javax.ejb.TimerService;
import ufsic.utils.Utils;

public class TimerApplication extends BeanApplication {
  
  @Resource 
  private TimerService timerService;
  
  private class Timers extends HashMap<String,Timer> {
    
    void cleanUp(String name) {
      
      Timer timer = get(name);
      if (isNotNull(timer)) {
        timer.cancel();
        remove(name);
      }
    }
    
    void cleanUpAll() {
      
      for (String name: keySet()) {
        cleanUp(name);
      }
    }
    
    boolean newTimer(String name, String expression) {
      
      boolean ret = false;
      if (!Utils.isEmpty(name) && !Utils.isEmpty(expression)) {
        
        // 0 1 2 3 4 5 6 = expression
        // 0 = second */(0 - 59)
        // 1 = minute */(0 - 59)
        // 2 = hour */(0 - 23)
        // 3 = day of month */(1 - 31)
        // 4 = month */(1 - 12)
        // 5 = day of week */(0 - 7) (0 to 6 are Sunday to Saturday, or use names; 7 is Sunday, the same as 0)
        // 6 = year */(1 - 12)
        
        try {
          
          ScheduleExpression schedule = new ScheduleExpression();
          
          String[] parts = expression.split(" ");
          ArrayList<String> list = new ArrayList<>();
          
          for (int i=6; i>=0; i--) {
            if (parts.length>i) {
              list.add(0,parts[i]);
            } else {
              list.add("*");
            }
          }
          for (int i=0; i<list.size(); i++) {
            String s = list.get(i);
            switch (i) {
              case 0: schedule.second(s); break;
              case 1: schedule.minute(s); break;
              case 2: schedule.hour(s); break;
              case 3: schedule.dayOfMonth(s); break;
              case 4: schedule.month(s); break;
              case 5: schedule.dayOfWeek(s); break;
              case 6: schedule.year(s); break;
              default: break;
            }
          }

          TimerConfig config = new TimerConfig();
          config.setPersistent(false);
          config.setInfo(name);
          
          Timer timer = timerService.createCalendarTimer(schedule,config);
          if (isNotNull(timer)) {
            
            cleanUp(name);
            put(name,timer);
            ret = true;
          }
          
        } catch (Exception e) {
          logException(e);
        }
      }
      return ret;
    }
  }
  
  private final Timers timers = new Timers();

  @Timeout
  protected void timeout(Timer timer) {
    //
  }
  
  protected void cleanUp(String name) {
    timers.cleanUp(name);
  }
  
  protected boolean newTimer(String name, String expression) {
    return timers.newTimer(name,expression);
  }
  
  protected boolean newTimer(String name) {
    
    return timers.newTimer(name,getOption(String.format("%s.Expression",name),null));
  }
  
  protected String getTimerName(Timer timer) {
    
    String ret = null;
    if (isNotNull(timer)) {
      
      Serializable info = timer.getInfo();
      if (isNotNull(info) && info instanceof String) {
        return (String)info;
      }
    }
    return ret;
  }
  
  @Override
  public void shutDown() {
    
    timers.cleanUpAll();
    super.shutDown(); 
  }

  
}
