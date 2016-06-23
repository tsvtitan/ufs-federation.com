package ufsic.providers;

public class BlobValue extends Value {
  
  public BlobValue(Object object) {
    super(object);
  }

  @Override
  public Object asObject() {
    return asBytes();
  }
  
  @Override
  public int length() {
    return asBytes().length;
  }
}
