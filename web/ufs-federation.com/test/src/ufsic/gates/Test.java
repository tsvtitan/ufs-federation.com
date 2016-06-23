package ufsic.gates;


import java.util.LinkedList;
import java.io.BufferedReader;
import java.io.File;
import java.text.DateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

import static java.lang.Thread.sleep;
import static java.util.ArrayList.*;
import static java.text.Format.Field.*;
import java.util.Arrays;
import java.util.Calendar;
import java.util.Comparator;
import java.util.HashSet;
import java.util.LinkedHashSet;
import java.util.Locale;
import java.util.NavigableMap;
import java.util.Scanner;
import java.util.Set;
import java.util.TreeMap;

class Exception1 extends Exception{}
class Exception2 extends Exception{}

abstract interface II {
  
  int iiII = 0;
  
  
  
  // static void test5();
  
}

public class Test implements II {
  
  static String[] aaa;
  static volatile String sss = "";
  static int iii = 0;
  
  static volatile int x =0;
  
  static void test(int... i) {
    
  }
  
  static void test(Integer... i) {
    
    char t = new char[]{'q'}[0];
  }

  public static void main(String... args) {
    
    System.out.println("==============================");
    LinkedList ll;
    
    //test(1);
    
    System.out.println(iiII);
    
    int l = args.length;
    
    
    
    try {
      sleep(100);
    } catch (InterruptedException ex) {
      Logger.getLogger(Test.class.getName()).log(Level.SEVERE, null, ex);
    }
    
    aaa = args;
    args = aaa;
    
    new ArrayIndexOutOfBoundsException(); // Runtime
    new ArithmeticException(); // Runtime
    new NumberFormatException(); // Runtime
    new InterruptedException(); // Exception
    new NullPointerException(); // Runtime
    
    
    Object obj = new Object() {
      
      /*public static void test() {
        
      }*/
    };
    
    DateFormat df = DateFormat.getTimeInstance(DateFormat.SHORT);
    System.out.println(df.format(new Date()));
    
    Object ob = new StringBuffer("11")+"";
    
    String pt = "[@.]";
    String[] arr = "Contact ExamLab customer service: cs@examlab.tk Thank you".split(pt);
    for (int i=0; i<arr.length; i++) {
      System.out.println(">"+arr[i]+"<");
    }
    
    
    new Thread() {

      @Override
      public void run() {
        try {
          sleep(1000);
        } catch (InterruptedException ex) {
          Logger.getLogger(Test.class.getName()).log(Level.SEVERE, null, ex);
        }
      }
      
    };
    
    
    
    try {
      //Thread.currentThread().join();
      Thread.sleep(100);
    } catch (InterruptedException ex) {
      Logger.getLogger(Test.class.getName()).log(Level.SEVERE, null, ex);
    }
    
    
    Calendar cal=null;
    
    String context="0100 55 3L 127 6F (int)7";
    Scanner s = new Scanner(context);
    while(s.hasNextLong()) {
      System.out.print(s.nextLong()+",");
    }
    /*while(s.hasNext()) {
      if (s.hasNextLong()) {
        System.out.print(s.nextLong()+",");
      } else {
        s.next();
      }
    }*/
    
    
    try{
      new ClassNotFoundException();
      
      throw new Exception2();

    } catch (Exception2 e2){
        System.out.print("Ex2 ");

    } finally {
        System.out.print("Fi-1 ");
    }
    //List<Number> ln = new ArrayList<Integer>();
    
    
    System.out.printf("45%2$b %3$+06d %4$+06.2f",978,654,1,1.955);
    
    Set<String> set = new HashSet<>();
    set.add("1");
    set.add("2");
    set.add("1");
    
    String s1 = "";
    
    Set<String> lh = new LinkedHashSet<>();
    lh.add("1");
    lh.add("2");
    lh.add("1");
    
    System.out.println(""); 
    System.out.println(Boolean.valueOf("TRue").getClass());  
    
    System.out.println(new String(new char[]{'x',33,'y'}));  
    
    test1();
    
    for (int h: new int[]{1,2,3,4}) {
      
    }
    
    File file = new File("add.java");
    //file.renameTo(file);
    
    int xxx =0;
    System.out.println(""+xxx);
    
    new Comparator() {

      @Override
      public int compare(Object o1, Object o2) {
        return 0;
      }
      
    };
    
    NavigableMap<Integer,String> map1 = new TreeMap<>();
   /// NavigableMap subm  = map1.subMap(xxx, true, xxx, true);
    
    Object obj1 = new AA3().equals(new AA3());
    System.out.println(obj1);
    
    String[] sa[]=new String[2][];
    sa[0]=new String[]{"A","B","C","D"};
    for (String[] s2:sa) {
/*      System.out.println(s2[0]);
      System.out.println(s2[1]);
      System.out.println(s2[2]);
      System.out.println(s2[3]);*/
    }
            
    new InterruptedException();
    new IllegalThreadStateException();
    
    System.out.println(null+"==============================");
  }
  
  
  static void test1() {

    Integer i = 1;
    Long l = (long)1;
    Float f = (float)1;
    Double d = (double)1;

    Object[] keys = new Object[]{i,l,f,d};
    Object oldKey = 1;
    for(Object key: keys){
      System.out.print(oldKey.equals(key)+", ");
      oldKey = key;
    }
  }
}

class A {

  A() throws RuntimeException {
    
  }
  
  A(int i) throws Exception {
    this();
  }
  
  void test() throws Exception {
    
    
  }

}
abstract class B extends A {

  abstract class C {
    
    //abstract synchronized void test();
  }
  
  void test() {
    
  }
}

class Asemble{

  public static void main(String args[]){
    try{go1();}catch(Exception e){}
    finally{
      System.out.println("B");
    }
  }
  public static void go1()throws ArrayIndexOutOfBoundsException, InterruptedException {go2();}
  public static void go2()throws ArithmeticException, InterruptedException {go3();}
  public static void go3()throws InterruptedException{go4();}
  public static void go4()throws NullPointerException{
    System.out.println("A");
    throw new ArrayIndexOutOfBoundsException();
  }
}

abstract class AA1 {
  
  public void test() throws InterruptedException {
    
  }
}

abstract class AA2 extends AA1 {
  
  abstract public void test2();
  
  public void main (String args[]) {
    
    Thread.currentThread().getName();
  }
} 

strictfp class AA3 extends AA1 {
  
  Integer[] arr;
  public void test3(int... i) {
   // arr = i;
  }
  
  final static void test4() {
    
  }
  
  private static class A {
    static int x=7;
  }
  
  public A getA() {
    return new A();
  }
  
  
} 