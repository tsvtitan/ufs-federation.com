package ufsic.out;

import java.io.OutputStream;

public class Echo extends Out {

  public Echo(OutputStream stream, boolean autoFlush) {
    super(stream, autoFlush);
  }

  public Echo(OutputStream stream) {
    super(stream);
  }
  
}