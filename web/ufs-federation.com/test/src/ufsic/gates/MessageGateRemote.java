package ufsic.gates;

import java.awt.print.Book;
import java.io.BufferedReader;
import java.io.DataOutput;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.ObjectOutputStream;
import java.io.PrintWriter;
import java.io.RandomAccessFile;
import java.nio.charset.Charset;
import java.nio.file.AccessDeniedException;
import java.nio.file.FileSystem;
import java.nio.file.FileSystems;
import java.nio.file.Files;
import java.nio.file.NoSuchFileException;
import java.nio.file.Path;
import java.nio.file.PathMatcher;
import java.nio.file.Paths;
import java.nio.file.StandardOpenOption;
import java.nio.file.StandardWatchEventKinds;
import java.nio.file.WatchKey;
import java.nio.file.WatchService;
import java.nio.file.attribute.BasicFileAttributeView;
import java.nio.file.attribute.DosFileAttributeView;
import java.nio.file.attribute.PosixFileAttributeView;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.text.DateFormat;
import java.text.DecimalFormat;
import java.text.FieldPosition;
import java.text.NumberFormat;
import java.text.ParseException;
import java.text.ParsePosition;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;
import java.util.Date;
import java.util.Deque;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Map.Entry;
import java.util.NavigableSet;
import java.util.Properties;
import java.util.Scanner;
import java.util.TreeMap;
import java.util.TreeSet;
import java.util.Vector;
import java.util.concurrent.Callable;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ConcurrentMap;
import java.util.concurrent.CopyOnWriteArrayList;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import javax.naming.InitialContext;
import javax.sql.rowset.CachedRowSet;
import javax.sql.rowset.FilteredRowSet;
import javax.sql.rowset.JdbcRowSet;
import javax.sql.rowset.RowSetFactory;
import javax.sql.rowset.RowSetProvider;
import javax.sql.rowset.WebRowSet;
import ufsic.gates.IMessageGateRemote;


public class MessageGateRemote {

  private String tempString;
  
  private class MessageGateRemoteInner extends MessageGateRemote {
    
  }
  
  private static IMessageGateRemote getGate(String jndi) {
  
    
    System.out.println(String.format("gate %s",jndi));
    
    IMessageGateRemote ret = null;
    try {
      
      Properties props = new Properties();
      props.setProperty("java.naming.factory.url.pkgs.Naming","com.sun.enterprise.naming");
      props.setProperty("java.naming.factory.initial","com.sun.enterprise.naming.SerialInitContextFactory"); 
      
      //props.setProperty("java.naming.factory.initial","javax.naming.NoInitialContextException");
      System.setProperty("org.omg.CORBA.ORBInitialHost",jndi);
      props.setProperty("org.omg.CORBA.ORBInitialHost",jndi);
      props.setProperty("org.omg.CORBA.ORBInitialPort","3700");

      InitialContext ctx = new InitialContext(props);
      String s = "java:global/gate/MessageGate!ufsic.gates.IMessageGateRemote";
      //String s = "java:comp/env/gate/IMessageGateRemote";
      //String s = String.format("corbaname:iiop:%s:3700:#java:global/gate/MessageGate",jndi);
      ret = (IMessageGateRemote)ctx.lookup(s);
      
    } catch (Exception e) {
      System.out.println(e.getMessage());
    }
    return ret;
  }
  
  /*private static IMessageGateRemote getGate() {
    return null;
  }*/
  
  public static boolean checkOutgoing(String jndi) {
    
    boolean ret = false;
    IMessageGateRemote gate = getGate(jndi);
    if (gate!=null) {
      ret = gate.checkOutgoing();
    }
    return ret;
  }
  
  public static String test() {
    
    String ret = "false";
    if (checkOutgoing(null)) {
      ret = "true";
    }
    return ret;
  }
  
  interface I1 {
    void m1() throws IOException;
  }
  
  interface I2 {
    void m1() throws FileNotFoundException; 
  }
  
  class B {
    
    public void m2() throws IOException {
      
    }
  }
  
  class C implements I1, I2 {

    
    
    public void m2() {
      
    }
    
    public void m1() {
      
    }
    
    
  }
  
  public static boolean isTrue() {
    
    return true;
  }
  
  public static <E extends CharSequence> List<? super E> doIt(List<E> nums) {
   
    return null;
  }
  
  class A1 {
  
    private void printt() {
      
    }
  } 
  
  class B1 extends A1 {
  
    void print() {
      
    }

  }
          
  public static void main(String[] args) throws SQLException, IOException {
    
    /*System.out.println("start");
    if (checkOutgoing((args.length==0)?"localhost":args[0])) {
      System.out.println("true");
    } else {
      System.out.println("false");
    }*/
    
    FileWriter fw = new FileWriter("//Users//tsv//text.txt"); 
    fw.write("hello"); //1       
    fw.close();
           
    Pattern p = Pattern.compile("\\s[\\d][a-z]*\\s");
    Matcher m = p.matcher("12 3ds ab23");
    boolean b = false;
    while(b = m.find()) { 
      System.out.println(m.start()+" "+m.group()); 
    }
    
    HashMap<String,Integer> map = new HashMap<String,Integer>();
    
    try {
      
      java.util.StringTokenizer st = new java.util.StringTokenizer("a aa aaa", "x");
      while (st.hasMoreTokens()) {
        System.out.println(st.nextToken()); 
      }
      
      MessageGateRemote gate = new MessageGateRemote();
      
      
      /*TreeSet<Integer> s = new TreeSet<Integer>();
      TreeSet<Integer> subs = new TreeSet<Integer>();
      for(int i = 324; i<=328; i++) { 
        s.add(i);
      } 
      subs = (TreeSet) s.subSet(326, true, 328, true );
      subs.add(329); 
      System.out.println(s+" "+subs);*/
      

      Path p1 = Paths.get("/home/test.txt");
      Path p2 = Paths.get("/home/report.pdf");
      System.out.println(p1.resolve(p2));
      
      System.out.printf("%1$s %2$s %s", "A", "B", "C");
      
      File f = new File("/Users/tsv/text.txt");
      BufferedReader bfr1 = new BufferedReader(new FileReader(f)); //2 
      BufferedReader bfr2 = new BufferedReader( bfr1 ); //3 
      //PrintWriter pw = new PrintWriter(new FileReader(f)); //4
              
      //throw new IOException();
      
      //java.io.Console
      //Map m1 = new HashMap();
      Map<Object, ? super ArrayList> m1 = new LinkedHashMap<Object, ArrayList>(); 
      m1.put("1", new ArrayList());//1
      //m1.put(1, new Object());//2
      //m1.put(1.0, "Hello");//3
      System.out.println(m1);
      
      
      A1[] a, a1; 
      B1[] b1;
      a = new A1[10]; 
      a1 = a; 
      b1 = new B1[20];
      a = b1; // 1
      b1 = (B1[]) a;// 2
      //b1 = (B1[]) a1;// 3
      
      /*ArrayList<String> ar = new ArrayList<>();
      ar.remove(0);*/
     
     
      
    } catch (IOException | RuntimeException e) {
      e.printStackTrace();
    }
  }
  
  public class TestInner2 {
    
    public void test() throws ArithmeticException {
      
      FileSystem fs = FileSystems.getDefault();
      Iterable<Path> roots = fs.getRootDirectories();
      for(Path p : roots){ 
        System.out.println(p); 
      }
    }
  }
  
  public class TestInner extends TestInner2 {
    
    static final String temp = "";
    
    @Override
    public void test() {
      
    }
  }
  
  public void m1(List<? extends Number> list) { 
    Number n = list.get(0);
    
  }
  
  

  public static synchronized void test2() {
  
    Locale loc = new Locale("fr");
    NumberFormat formatter = DecimalFormat.getCurrencyInstance(loc);
    formatter = NumberFormat.getInstance(loc);
    
    
     
    
    Thread t = new Thread() {
      
    };
    t.start();
    
   
  }
    
  enum TTT {
    T(""),TT("");
    
    TTT(String s) {
      
    }
    
  }
  
  class BookList extends ArrayList<Book> {
    
    
  }
  
  
}

 class TestClass {
    
    public class A { }
   
    public static interface I {
    
      static Integer i = 0;
    }
    
    public static class B {
    
      Integer i = 0;
      static Integer y = 0;
    } 
   
    public void useclasses() { 
      new TestClass.A();
      B b = new B() {
        
      };
    }
    
    public void addData1(List<? super Dooby> list) {
      
      list.add(new Dooby());
      list.add(new Tooby());
      list.add(new Cooby());
      
      
    }
    
    public void addData2(List<? extends Dooby> list) {
      
      Aooby a = list.get(0);
      Booby b = list.get(0);
      Dooby d = list.get(0);
      
    }
    
    /*public List<Dooby> m3(ArrayList<? extends Dooby> strList) { 
      
      List<? extends Dooby> list = new ArrayList<>();
      list.addAll(strList); 
      return list; 
    }*/
    
    public List<? extends Dooby> m4(List<? extends Dooby> strList) { 
      
      List<Dooby> list = new ArrayList<>(); 
      list.add(new Dooby()); 
      list.addAll(strList); 
      return list; 
    }
    
    public void m5(ArrayList<? extends Dooby> strList) { 
      
      List<Dooby> list = new ArrayList<>(); 
      list.add(new Dooby()); 
      list.addAll(strList); 
    }
    
    public static void test() {
      
      new Object() {
        
      };
    }
  }




class A1 {
  
  abstract private class A11 {
  
  }
  
  static class Runner implements Runnable {

    @Override
    public void run() {
      System.out.println("In run");
    }
    
  }
  
  private void print() {
    
  }
}

class B1 extends A1 {
  
  static {
    System.out.println("In static");
  }
  
  void print() {
    
  }
  
  static class C1 extends B1 {
    
  }
  
  class D1 extends C1 {
    
  }
  
  static ConcurrentHashMap<String, Object> chm = new ConcurrentHashMap<String, Object>();
  
  public static void main(String... args) throws ParseException, SQLException, FileNotFoundException {
    
    StringBuilder sb = new StringBuilder("8");
    int i = 8;
    System.out.println("" + 8 + i + sb);
    
    /*chm.put("a", "aaa");
    chm.put("b", "bbb"); 
    chm.put("c", "ccc");  
    
    new Thread(){ 
      public void run() { 
        Iterator<Entry<String, Object>> it = B1.chm.entrySet().iterator(); 
        while(it.hasNext()) { 
          Entry<String, Object> en = it.next(); 
          if(en.getKey().equals("a") || en.getKey().equals("b")){ 
            it.remove(); 
          }
        }
      }
    }.start(); 
      
    new Thread(){ 
      public void run(){ 
        Iterator<Entry<String, Object>> it = B1.chm.entrySet().iterator();
        while(it.hasNext()){ 
          Entry<String, Object> en = it.next();
          System.out.print(en.getKey()+", "); 
        } 
        while(true){}
      } 
    }.start();*/
    
    
    Path p1 = Paths.get("/Users/tsv/text.txt");
    Path p2 = Paths.get("/text2.txt");
    
    System.out.println(p1.resolve(p2));
    System.out.println(p1.resolveSibling(p2));
    System.out.println(p1.relativize(p2));
    
    double amount = 123456.789;
    Locale fr = new Locale("fr", "FR");
    
    NumberFormat formatter = NumberFormat.getInstance(fr);
    String s = formatter.format(amount) ;
    
    formatter = NumberFormat.getInstance();
    Number amount2 = formatter.parse(s);
    
    System.out.println( amount + " " + amount2 );
    
    Pattern p = Pattern.compile("H[a-e][k-m]");
    Matcher m = p.matcher("Hello Hallo");
    boolean b = false;
    while(b = m.find()) { 
      System.out.println(m.start()+" "+m.group()); 
    }
    
 
    System.out.println(new Boolean("false"));
    
    Path myfile = Paths.get("test.txt");
    try (BufferedReader bfr = Files.newBufferedReader(myfile, Charset.forName("US-ASCII") )) {
      String line = null;
      while( (line = bfr.readLine()) != null) { 
        System.out.println(line); 
      } 
    } catch (Exception e) { 
      System.out.println(e); 
    }
    
    new C1() {
      
    };
    
    B1 b1 = new B1();
    
    
    
    boolean enabled = false; 
    assert enabled = true; 
    assert args!=null;
    
   
    Integer ii = 10;
    Double dd = new Double(ii);
    
    
    List<? extends Booby> len = new ArrayList<>();
    //Booby bby = len.get(0);
    
    List<? super Booby> lsn = new ArrayList<>();
    lsn.add(new Booby());
    lsn.add(new Dooby());
    lsn.add(new Tooby());
    
    new Thread() {
      
    }.setPriority(2);
    
    List<? extends Number> lll = new Vector<Integer>();
    lll.add(null);
   // lll.add(new Integer(0));
    
    Runtime.getRuntime().gc();
    
    TreeMap<String,Integer> mmm=null;
    
    
    Locale lc = new Locale("en");
    System.out.println(lc.getDisplayCountry());

    StringBuffer sbb= null;
    
    TreeSet<String> ts = new TreeSet<>();
    ts.add("one");
    ts.add("two");
    ts.add("three");
    ts.add("four");
    ts.add("five");
    
    NavigableSet<String> ns = ts.tailSet("three",true);
    for (String s2: ns) {
      System.out.println(s2);
    }
    
    int[][] arr = new int[][] {};
    
    int iii = 00;
    String sss = ""+iii;
   
    List<String> ls = new ArrayList();
    
    DateFormat.getInstance();
    
    for (int x=0; x<2; ) {
      x++;
    }
    
    new TreeSet<Integer>(Collections.reverseOrder());
    
    
    new ArithmeticException();
    
    new NumberFormatException();
    
  
    
    List list = new ArrayList<Number>();
    System.out.printf("%b=", "dssdf");
    
    try {
      
      iii = 100/0;
      
    //} catch (InterruptedException ie) {
      
    } catch (RuntimeException re) {
      
    } catch (Exception e) {
      
    }
    
  }
    
  final static void goOne(final int... x) {
	  System.out.print(" int... "+x[0]);
	}
  
  void ttt(Integer... i) {
    
  }
  
  void ttt(int... i) {
    
  }
  
}

abstract class AAA {
  
  abstract void test();
  
  
}

class Aooby {

  protected Number test() { return 4; }
}

class Booby extends Aooby {

  static void ttt(Object ob, Integer... i) {
    
  }
  
  /*void ttt(int... i) {
    
  }*/
  
  
  
  static void ddd() throws ArithmeticException {
    
    String s = "123";
    switch(s) {
      
    }
    
    ttt(null);
    
    
    
    
    //throw new ArrayIndexOutOfBoundsException();
   // new Booby().ttt(Integer.valueOf("1"));
  }
 
}
class Dooby extends Booby {}
class Tooby extends Dooby {}
class Cooby extends Tooby {}

interface III {
  
}

final class CCC {
  
}

enum ZZZ {
  Z1,Z2
}

abstract class Outer {
  
  class Inner {
    
  }
  
  
}
