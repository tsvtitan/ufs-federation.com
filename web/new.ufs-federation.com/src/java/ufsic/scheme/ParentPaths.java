package ufsic.scheme;

public class ParentPaths extends Paths {

  private final static String ViewName = "PARENT_PATHS";
  
  protected final static String LastPathId = "LAST_PATH_ID";
  protected final static String Title = "TITLE";
  
  public ParentPaths(Scheme scheme) {
    super(scheme,ViewName);
  }
  
  @Override
  public Class getRecordClass() {
    return ParentPath.class;
  }
  
}
