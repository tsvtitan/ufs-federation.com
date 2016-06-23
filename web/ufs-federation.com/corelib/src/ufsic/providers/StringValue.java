package ufsic.providers;

public class StringValue extends Value {

  public StringValue(Object object) {
    super(object);
  }
  
  @Override
  public Object asObject() {
    
    return asString();
  }
  
}
