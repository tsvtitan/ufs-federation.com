package ufsic.gates;

import java.util.Date;
import java.util.Random;
import java.util.Vector;
import java.util.concurrent.atomic.AtomicInteger;

public class Test2 {

  static final AtomicInteger ai = new AtomicInteger();
  static final Vector list = new Vector();
  
  static class ChildThread extends Thread {
  
    public ChildThread(String name) {
      super(name);
    }
    
    @Override
    public void run() {
      
      System.out.printf("%s has started\n",this.getName());
      
      Random rnd = new Random();
      long time = rnd.nextInt(10000);
      try {
        Thread.sleep(time);
      } catch(Exception e) {
        //
      } finally {
        System.out.printf("%s has finsihed for (%d)\n",this.getName(),time);
        /*ai.decrementAndGet();
        synchronized (ai) {
          ai.notify();
        }*/
        list.remove(this);
        synchronized (list) {
          list.notify();
        }
      }
    }
    
  }
  
  public static void main(String... args) {
  
    new Thread() {
    
      @Override
      public void run() {
        
        int count = 60;
        
        Date date = new Date(); 
        
        ai.set(count);
        for (int i=0; i<count; i++) {
          
          ChildThread thread = new ChildThread(String.format("ChildThread #%d",i));
          list.add(thread);
          thread.start();
          
        }
        
        /*while (ai.get()>0) {
          try {
            synchronized (ai) {
              ai.wait();
            }
          } catch(Exception e) {
            //
          }
        }*/
        
        while (!list.isEmpty()) {
          try {
            synchronized (list) {
              list.wait();
            }
          } catch(Exception e) {
            //
          }
        }
        
        System.out.printf("Main thread has finsihed (%d)\n",new Date().getTime() - date.getTime());
      }
    }.start();
    
  }
}
