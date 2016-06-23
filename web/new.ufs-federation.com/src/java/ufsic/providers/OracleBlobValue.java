package ufsic.providers;

import oracle.sql.BLOB;

public class OracleBlobValue extends BlobValue {
  
  public OracleBlobValue(Object object) {
    super(object);
  }
  
  @Override
  public byte[] asBytes() {
    
    byte[] ret = new byte[] {};
    if (isNotNull()) {
      if (sameClass(BLOB.class)) {
        BLOB blob = (BLOB)getObject();
        try {
          int len = (int)blob.length();
          ret = blob.getBytes(1,len);
        } catch (Exception e) {
          //
        }
      } else {
        ret = super.asBytes();
      }
    }
    return ret;
  }
  
  @Override
  public int length() {
  
    int ret = 0;
    if (isNotNull()) {
      if (sameClass(BLOB.class)) {
        try {
          ret = (int)((BLOB)getObject()).length();
        } catch (Exception e) {
          //
        }
      } else {
        ret = super.length();
      }
    }
    return ret;
  }
  
}
