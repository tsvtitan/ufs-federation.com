package ufsic.scheme;

public class Dirs extends SchemeTable<Dir> {
  
  public final static String TableName = "DIRS";
  
  public final static String DirId = "DIR_ID";
  public final static String Location = "LOCATION";
  public final static String Description = "DESCRIPTION";

  public Dirs(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Dirs(Scheme scheme) {
    super(scheme,TableName);
  }
  
  
}
