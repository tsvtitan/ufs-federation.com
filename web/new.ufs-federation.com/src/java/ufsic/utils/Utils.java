package ufsic.utils;

import java.io.ByteArrayOutputStream;
import java.io.PrintWriter;
import java.nio.charset.Charset;
import java.sql.Timestamp;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Random;
import java.util.UUID;
import java.util.concurrent.TimeUnit;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.commons.codec.digest.DigestUtils;

public class Utils {
  
  public static boolean isNull(Object obj) {
    return (obj == null);
  }
  
  public static boolean isNotNull(Object obj) {
    return (obj != null);
  }
  
  public static boolean isClass(Object obj, Class cls) {

    boolean ret = false; 
    if (isNotNull(obj) && isNotNull(cls)) {
      
      String cls1 = obj.getClass().getName();
      String cls2 = cls.getName();
      ret = cls1.equals(cls2);
    }
    return ret;
  }
  
  public static boolean equals(Object o1, Object o2) {
      
    if (isNull(o1)) {
    
      return isNull(o2); 
    
    } else if (isNull(o2)) {
      
      return false;
    }
    return o1.equals(o2);
  }  
  
  public static String getProperty(String name, String def) {

    String ret = def;
    try {
      ret = System.getProperty(name);
    } catch (Exception e) {
      ret = def;
    }
    return ret.toString();
  }
  
  public static String getLineSeparator() {
    
    return getProperty("line.separator","\\n");
  }
  
  public static Charset getCharset() {

    return Charset.forName("UTF-8");
  }
  
  public static String getPathDelimeter() {
    
    return getProperty("file.separator","/");
  }

  public static String md5(String s) {
    
    return DigestUtils.md5Hex(s);
  }
  
  public static String getExceptionStack(Exception e) {

    ByteArrayOutputStream buf = new ByteArrayOutputStream();
    PrintWriter pw = new PrintWriter(buf);
    e.printStackTrace(pw);
    pw.flush();
    return buf.toString();
  }
  
  public static String getUniqueId() {
    
    String ret = UUID.randomUUID().toString();
    ret = ret.replace("-","");
    return ret.toUpperCase();
  }
  
  
  public static String formatDate(String format, Date date) {

    String ret = "";
    if (isNotNull(date)) {
      Calendar cal = Calendar.getInstance();
      cal.setTimeInMillis(date.getTime());

      SimpleDateFormat sdf = new SimpleDateFormat(format);
      ret = sdf.format(cal.getTime());
    }
    return ret;
  }
  
  public static boolean isInteger(String s) {
  
    boolean ret = false;
    if (isNotNull(s) && !s.trim().equals("")) {
      String s1 = s.trim();
      for (int i=0;i<s1.length();i++) {
        char ch = s1.charAt(i);
        if (i==0 && s1.length()>1) {
          if (!(ch=='-' || ch=='+' || Character.isDigit(ch))) {
            return false;
          }
        } else if (!Character.isDigit(ch)) {
          return false;
        }
      }
      ret = true;
    }
    return ret;
  }
  
  public static boolean isInteger(Object o) {
    
    boolean ret = false;
    if (isNotNull(o)) {
      if (o instanceof String) {
        ret = isInteger((String)o);
      } else {
        ret = isInteger(o.toString());
      }
    }
    return ret;
  }
  
  public static int toInt(String s, int def) {
    
    int ret = def;
    if (isInteger(s)) {
      ret = Integer.parseInt(s);
    }
    return ret;
  }

  public static int toInt(String s) {
    
    return toInt(s,0);
  }
  
  public static int toInt(Object o, int def) {
    
    int ret = def;
    if (isNotNull(o)) {
      if (o instanceof String) {
        ret = toInt((String)o);
      } else {
        ret = toInt(o.toString());
      }
    }
    return ret;
  }

  public static int toInt(Object o) {
    
    return toInt(o,0);
  }
  
  public static Integer toInteger(String s, Integer def) {
    
    Integer ret = def;
    if (isInteger(s)) {
      ret = new Integer(s);
    }
    return ret;
  }

  public static Integer toInteger(String s) {
    
    return toInteger(s,null);
  }
  
  public static Integer toInteger(Object o, Integer def) {
    
    Integer ret = def;
    if (isNotNull(o)) {
      if (o instanceof String) {
        ret = toInteger((String)o);
      }
    }
    return ret;
  }

  
  public static Integer toInteger(Object o) {
    
    return toInteger(o,null);
  }
  
  public static boolean isDouble(String s) {
  
    boolean ret = false;
    if (isNotNull(s) && !s.trim().equals("")) {
      
      String s1 = s.trim();
      try {
        Double d = Double.parseDouble(s);
        ret = true;
      } catch (Exception e) {
        //
      }
    }
    return ret;
  }
  
  public static boolean isDouble(Object o) {
    
    boolean ret = false;
    if (isNotNull(o)) {
      if (o instanceof String) {
        ret = isDouble((String)o);
      } else {
        ret = isDouble(o.toString());
      }
    }
    return ret;
  }

  public static Double toDouble(String s, Double def) {
    
    Double ret = def;
    if (isDouble(s)) {
      ret = new Double(s);
    }
    return ret;
  }

  public static Double toDouble(String s) {
    
    return toDouble(s,null);
  }
  
  public static Double toDouble(Object o, Double def) {
    
    Double ret = def;
    if (isNotNull(o)) {
      if (o instanceof String) {
        ret = toDouble((String)o);
      }
    }
    return ret;
  }

  
  public static Double toDouble(Object o) {
    
    return toDouble(o,null);
  }
  
  
  public static boolean isDate(String s, String format) {
    
    boolean ret = false;
    if (isNotNull(s) && !s.trim().equals("")) {
      
      try {
        SimpleDateFormat sdf;
        if (isNull(format)) {
          sdf = new SimpleDateFormat();
        } else {
          sdf = new SimpleDateFormat(format);
        }
        Date date = sdf.parse(s);
                
        ret = true;
      } catch (Exception e) {
        //
      }
    }
    return ret;
  }

  public static boolean isDate(String s) {
    
    return isDate(s,null);
  }
  
  public static boolean isDate(Object o, String format) {
    
    boolean ret = false;
    if (isNotNull(o)) {
      if (o instanceof String) {
        ret = isDate((String)o,format);
      }
    }
    return ret;
  }

  public static boolean isDate(Object o) {
    
    return isDate(o,null);
  }
  
  public static Date toDate(String s, String format) {
    
    Date ret = null;
    if (isDate(s,format)) {
      
      try {
        SimpleDateFormat sdf;
        if (isNull(format)) {
          sdf = new SimpleDateFormat();
        } else {
          sdf = new SimpleDateFormat(format);
        }
        ret = sdf.parse(s);
        
      } catch (Exception e) {
        //
      }
    }
    return ret;
  }
  
  public static Date toDate(Object o, String format) {
    
    Date ret = null;
    if (isNotNull(o)) {
      if (o instanceof String) {
        ret = toDate((String)o,format);
      } else {
        ret = toDate(o.toString(),format);
      }
    }
    return ret;
  }

  public static Timestamp toTimestamp(String s, String format) {
    
    Timestamp ret = null;
    if (isDate(s,format)) {
      Date date = toDate(s,format);
      ret = new Timestamp(date.getTime());
    }
    return ret;
  }
  
  public static Timestamp toTimestamp(Object o, String format) {
    
    Timestamp ret = null;
    if (isNotNull(o)) {
      if (o instanceof String) {
        ret = toTimestamp((String)o,format);
      } else {
        ret = toTimestamp(o.toString(),format);
      }
    }
    return ret;
  }
  
  
  public static boolean isEmail(String s) {
  
    boolean ret = false;
    if (isNotNull(s) && !s.trim().equals("")) {
      Pattern ep = Pattern.compile("^[(a-zA-Z-0-9-\\_\\+\\.)]+@[(a-z-A-z)]+\\.[(a-zA-z)]{2,3}$");
      Matcher matcher = ep.matcher(s);
      ret = matcher.matches();
    }
    return ret;
  }

  public static boolean isPhone(String s) {
  
    boolean ret = false;
    if (isNotNull(s) && !s.trim().equals("")) {
      //Pattern ep = Pattern.compile("^\\+[0-9]{2,3}+-[0-9]{10}$");
      Pattern ep = Pattern.compile("^\\+[0-9]{11}$");
      Matcher matcher = ep.matcher(s);
      ret = matcher.matches();
    }
    return ret;
  }
  
  
  public static char[] generateChars(short from, short count) {

    char[] ret = new char[count];
    for(short i=0;i<ret.length;i++) {
      ret[i] = (char)(from+i);
    }
    return ret;    
  }
  
  private static Timestamp add(Timestamp stamp, int field, int amount) {

    Timestamp ret = stamp;
    if (isNotNull(ret)) {
      Calendar cal = Calendar.getInstance();
      cal.setTime(new Date(ret.getTime()));
      cal.add(field,amount);
      ret = new Timestamp(cal.getTime().getTime());
    }
    return ret;
  }

  public static Timestamp addSeconds(Timestamp stamp, int seconds) {
    
    return add(stamp,Calendar.SECOND,seconds);
  }
  
  public static Timestamp addMinutes(Timestamp stamp, int minutes) {
    
    return add(stamp,Calendar.MINUTE,minutes);
  }
  
  public static Timestamp addHours(Timestamp stamp, int hours) {
    
    return add(stamp,Calendar.HOUR,hours);
  }
  
  public static String msecondsToMMSS(long millisecs) {
    
    long mins = TimeUnit.MILLISECONDS.toMinutes(millisecs) - TimeUnit.HOURS.toMinutes(TimeUnit.MILLISECONDS.toHours(millisecs));
    long secs = TimeUnit.MILLISECONDS.toSeconds(millisecs) - TimeUnit.MINUTES.toSeconds(TimeUnit.MILLISECONDS.toMinutes(millisecs));
    return String.format("%02d:%02d",mins,secs);
  }

  public static String secondsToMMSS(long seconds) {
    
    long mins = TimeUnit.SECONDS.toMinutes(seconds) - TimeUnit.HOURS.toMinutes(TimeUnit.SECONDS.toHours(seconds));
    long secs = TimeUnit.SECONDS.toSeconds(seconds) - TimeUnit.MINUTES.toSeconds(TimeUnit.SECONDS.toMinutes(seconds));
    return String.format("%02d:%02d",mins,secs);
  }
  
  public static String toMMSS(long seconds) {
    
    return secondsToMMSS(seconds);
  }
  
  /*private static Integer randInteger(int pads) {
    
    Integer ret = 0;
    if (pads>0) {
      Random rn = new Random();
      double a = 10, b = pads-1;
      int min = (int)Math.pow(a,b);
      ret = min + rn.nextInt(min);
    }
    return ret;
  }*/
  
}
