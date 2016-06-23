package ufsic.scheme;

public class Tokens extends SchemeTable<Token> {
  
  public final static String TableName = "TOKENS";
  
  public final static String TokenId = "TOKEN_ID";
  public final static String DeviceId = "DEVICE_ID";
  public final static String SessionId = "SESSION_ID";
  public final static String Created = "CREATED";
  public final static String Expired = "EXPIRED";
  
  public Tokens(Scheme scheme, String viewName) {
    super(scheme, viewName);
  }

  public Tokens(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    return Token.class;
  }
  
}
