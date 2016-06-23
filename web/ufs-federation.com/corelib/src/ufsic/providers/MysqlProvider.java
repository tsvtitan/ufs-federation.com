package ufsic.providers;

import ufsic.out.Echo;
import ufsic.out.Logger;

public class MysqlProvider extends Provider {

  public MysqlProvider(Echo echo, Logger logger, String jndiName) {
    
    super(echo,logger,jndiName);
  }

  public MysqlProvider(String jndiName) {
    
    this(null,null,jndiName);
  }

  @Override
  public String quote(String s) {

    String ret = s.replaceAll("'","''");
    return super.quote(ret);
  }

  @Override
  public String quoteName(String name) {
    
    return String.format("\"%s\"",name);
  }

  @Override
  public Value getUniqueId() {
    throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
  }

  @Override
  public Value getNow() {
    throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
  }

  
}
