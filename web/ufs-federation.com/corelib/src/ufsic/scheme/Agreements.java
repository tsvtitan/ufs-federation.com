package ufsic.scheme;

public class Agreements extends SchemeTable<Agreement> {

  protected final static String TableName = "AGREEMENTS";

  protected final static String AgreementId = "AGREEMENT_ID";
  protected final static String AccountId = "ACCOUNT_ID";
  protected final static String Created = "CREATED";
  protected final static String Locked = "LOCKED";
  protected final static String Name = "NAME";
  protected final static String Description = "DESCRIPTION";
  protected final static String Priority = "PRIORITY";
  protected final static String SystemId = "SYSTEM_ID";
  protected final static String IdInSystem = "ID_IN_SYSTEM";
  
  
  public Agreements(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Agreements(Scheme scheme) {
    super(scheme,TableName);
  }
  
  public Agreements() {
    
    super();
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {

    return Agreement.class;
  }
  
}
