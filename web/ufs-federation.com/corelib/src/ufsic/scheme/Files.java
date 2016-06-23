package ufsic.scheme;

public class Files extends SchemeTable<File> {

  public final static String TableName = "FILES";
  
  public final static String FileId = "FILE_ID";
  public final static String Name = "NAME";
  public final static String Extension = "EXTENSION";
  public final static String Location = "LOCATION";
  public final static String Data = "DATA";
  
  public Files(Scheme scheme, String viewName) {
    super(scheme, viewName);
  }

  public Files(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    return File.class;
  }
  
  
}
