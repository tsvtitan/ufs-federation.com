package ufsic.applications;

import javax.annotation.PostConstruct;
import javax.annotation.PreDestroy;

public class BeanApplication extends DatabaseApplication {
  
  @Override
  @PostConstruct
  public void startUp() {
    
    super.startUp();
  }

  @Override
  @PreDestroy
  public void shutDown() {
    
    super.shutDown();
  }
  
  
}
