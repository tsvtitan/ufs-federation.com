package ufsic.scheme;

import java.lang.reflect.Constructor;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Confirms extends SchemeTable {
  
  public final static String TableName = "CONFIRMS";
  
  public final static String ConfirmId = "CONFIRM_ID";
  public final static String SessionId = "SESSION_ID";
  public final static String AccountId = "ACCOUNT_ID";
  public final static String MessageId = "MESSAGE_ID";
  public final static String Created = "CREATED";
  public final static String Begin = "BEGIN";
  public final static String End = "END";
  public final static String Locked = "LOCKED";
  public final static String Code = "CODE";
  public final static String ConfirmType = "CONFIRM_TYPE";
  public final static String Params = "PARAMS";
  public final static String Confirmed = "CONFIRMED";
  public final static String PathId = "PATH_ID";
  
  public final static String RecipientContact = "RECIPIENT_CONTACT";
  public final static String Path = "PATH";
  
  public Confirms(Scheme scheme) {
    super(scheme);
    this.name = TableName;
  }

  public Confirms() {
    
    super();
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {

    return Confirm.class;
  }
  
  public Confirm newConfirm(Record record, Value confirmType) {
    
    Confirm ret = null;
    Class cls = Confirm.class;
    try {
      String cType = "";
      if (isNotNull(confirmType) && confirmType.isNotNull()) {
        cType = confirmType.asString().trim();
      }
      if (isNotNull(record) && cType.equals("")) {
        cType = record.getValue(Confirms.ConfirmType).asString();
      }
      if (isNotNull(cType)) {
        String pkg = Templates.class.getPackage().getName();
        String name = String.format("%s.confirms.%s",pkg,cType);
        cls = Templates.class.getClassLoader().loadClass(name);
      }
      if (isNotNull(cls)) {
        Constructor con = cls.getConstructor(SchemeTable.class,Record.class);
        if (isNotNull(con)) {
          ret = (Confirm)con.newInstance(this,record);
          if (isNotNull(con)) {
            this.add(ret);
          }
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  public Confirm newConfirm(Record record) {
    
    return newConfirm(record,null);
  }
  
}
