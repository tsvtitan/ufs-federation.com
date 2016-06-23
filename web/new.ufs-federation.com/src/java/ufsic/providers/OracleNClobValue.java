package ufsic.providers;

import oracle.sql.NCLOB;

public class OracleNClobValue extends StringValue {

  public OracleNClobValue(Object object) {
    super(object);
  }
  
  @Override
  public String asString() {
    
    String ret = "";
    if (isNotNull()) {
      if (sameClass(NCLOB.class)) {
        
        NCLOB blob = (NCLOB)getObject();
        try {
          int len = (int)blob.length();
          ret = blob.getSubString(1,len);
         // ret = new String(ret.getBytes(Utils.getCharset().name())); // ???
        } catch (Exception e) {
          // 
        }
      } else {
        ret = super.asString();
      }
    }
    return ret;
  }
  
  /*@Override
  public byte[] asBytes() {
    
    byte[] ret = new byte[] {};
    if (isNotNull()) {
      if (sameClass(NCLOB.class)) {
        NCLOB blob = (NCLOB)getObject();
        try {
          int len = (int)blob.length();
          ret = blob.getBytes();
          blob.
        } catch (Exception e) {
          //
        }
      } else {
        ret = super.asBytes();
      }
    }
    return ret;
  }*/
  
}
