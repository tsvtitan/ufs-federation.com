package ufsic.out;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.nio.charset.Charset;

import ufsic.utils.Utils;

public class Out {

  private boolean first = true;
  private final Charset charset = Utils.getCharset();
  private final ByteArrayOutputStream bufStream;
  private final OutputStream outStream;
  private final boolean autoFlush;
  
  public Out(OutputStream stream, boolean autoFlush) {
    
    this.outStream = stream;
    this.bufStream = new ByteArrayOutputStream();
    this.autoFlush = autoFlush;
  }
  
  public Out(OutputStream stream) {

    this(stream,false);
  }

  public void flush() {
  
    if (Utils.isNotNull(outStream)) {
      try {
        bufStream.writeTo(outStream);
      } catch (IOException ex) {
      }
      bufStream.reset();
    }
  }
  
  public boolean write(byte[] bytes) {
    
    boolean ret = false;
    try {
      bufStream.write(bytes);
      if (autoFlush) {
        flush();
      }
      ret = true;
    } catch (Exception e) {
      //
    }
    return ret;
  }
  
  public boolean write(String s, boolean newLine, Object... args) {
  
    boolean ret = false;
    if (!s.equals("")) {
      String s1 = s.toString();
      if (newLine && !first) {
        String sep = Utils.getLineSeparator();
        s1 = sep + s1;
      } else {
        first = false;
      }
      if (Utils.isNotNull(args) && (args.length>0)) {
        ret = write(String.format(s1,args).getBytes(charset));
      } else {
        ret = write(s1.getBytes(charset));
      }
    }
    return ret;
  }

  public boolean write(String s, Object... args) {
    
    return write(s,true,args);
  }

  public ByteArrayOutputStream getBufStream() {
    return bufStream;
  }
  
  
}
