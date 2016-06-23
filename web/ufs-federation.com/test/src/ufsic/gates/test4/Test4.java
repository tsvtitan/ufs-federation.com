/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.gates.test4;

import java.io.Console;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InvalidClassException;
import java.io.RandomAccessFile;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.nio.file.StandardOpenOption;
import java.nio.file.StandardWatchEventKinds;
import java.nio.file.WatchService;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.NumberFormat;
import java.util.ArrayDeque;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Deque;
import java.util.List;
import java.util.ResourceBundle;
import java.util.concurrent.Callable;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import javax.sql.rowset.FilteredRowSet;

/**
 *
 * @author tsv
 */

/*private class T1 {
  
}*/

abstract class MyClass {
  
}

public class Test4 {
  
  static String st = "";
  
  class A {
    
    A() {
      
    }
  }
  
  static class B {
    
  }
  
  class C1 {
  
    int i = 0;
    
    C1() {
      
    }
    
    C1(int i) {
      this.i = i;
    }
  }
  
  class C2 extends C1 {
    
    int j = 0;
    
    C2(int x, int y, int z) {
      
    }
    
    C2(int x, int y) {
      super(x);
    }
  }
  
  public void test2() {
    
    new A();
    new Test4.A();
    new Test4().new A();
    
    new B();
    new Test4.B();
    //new Test4().new B();
  }
  
  public static void test3() {
    
    //new A();
    //new Test4.A();
    new Test4().new A();
    
    new B();
    new Test4.B();
    //new Test4.new B();
  }
  
  public static void main(String... args) {
    
    
    
    System.out.printf("%1$s %s %<s", "A", "B", "C");
    
    new Object() {
      
    };
    
    
    
    new InvalidClassException(""); // IOException
    new SecurityException(); // Runtime
    new IllegalArgumentException(); //Runtime
    new FileNotFoundException(); // IOException
    new ArithmeticException(); // RuntimeException
    new ClassNotFoundException(); // Exception
    new NoSuchFieldException(); // Exception
    
    Path p1 = Paths.get("/photos/vacation");
    Path p2 = Paths.get("/photos/yellowstone"); 
    System.out.println(p1.resolve(p2)+"  "+p1.relativize(p2));

   // new RandomAccessFile();
    FilteredRowSet frs;
    
    Object obj = new Object() {
      {
        int i = 0;
      }
    };
    
    new Callable<String>() {

      @Override
      public String call() {
        throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
      }
      
    };
    
    //StandardOpenOption.CREATE
    //NumberFormat.getInstance().
    //while(true){}
    //Console console;
    
    // Single Responsibility = DAO
    //
    
    StringBuilder sb = new StringBuilder("8");
    int i = 8;
    System.out.println(8 + i + Integer.parseInt(sb.toString()));
    
    
    File file;
    //file.li
    
    Statement st = null;
    Connection con = null;
    /*try (st=con.createStatement()) {
      
    }*/
    
    Deque<Integer> d = new ArrayDeque<>();
    d.add(1); 
    d.push(2); 
    d.pop(); 
    d.offerFirst(3);
    d.remove(); 
    System.out.println(d);
    
    
    ResourceBundle rb;
    
    // StandardWatchEventKinds
    
    Thread.yield();
    
    //Collections.synchronizedCollection
    
    ExecutorService es = null;
    es.shutdownNow();
  }
  
  interface I2 {
    static String s = "";
  }
  
  static class CC {
    
    static void test() {
      
    }
  }
  
  public List<? extends Object> m4(List<? extends Object> strList){ 
    List<Object> list = new ArrayList<>(); 
    list.add(new Object()); 
    list.addAll(strList); 
    return list;
  }
  
  public void m5(ArrayList<? extends Object> strList){
    List<Object> list = new ArrayList<>(); 
    list.add(new Object()); 
    list.addAll(strList); 
  }
}

interface I1 {
  
  void test() throws IOException;
}

interface I2 {
  
  void test() /*throws SQLException, FileNotFoundException*/;
}

class C1 implements I1, I2 {

  @Override
  public void test() /*throws FileNotFoundException*/ {
    
  }
  
}

enum EEE {
  
  E1, E2;
  
  EEE() {
    
  }
}