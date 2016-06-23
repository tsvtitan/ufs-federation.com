package ufsic.scheme;

import ufsic.providers.FieldNames;
import ufsic.providers.Filter;
import ufsic.providers.Record;

public class Comms extends SchemeTable<Comm> {

  public final static String TableName = "COMMS";
  
  public final static String CommId = "COMM_ID";
  public final static String PathId = "PATH_ID";
  public final static String SessionId = "SESSION_ID";
  public final static String Created = "CREATED";
  public final static String Finished = "FINISHED";
  public final static String Duration = "DURATION";
  public final static String InData = "IN_DATA";
  public final static String OutData = "OUT_DATA";
  public final static String InHeaders = "IN_HEADERS";
  public final static String OutHeaders = "OUT_HEADERS";
  public final static String LangId = "LANG_ID";
  public final static String Path = "PATH";
  public final static String PathHandler = "PATH_HANDLER";
  
  public Comms(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Comms(Scheme scheme) {
    super(scheme, TableName);
  }
  
  @Override
  public Class getRecordClass() {
    return Comm.class;
  }

  public Comm getComm(Object commId) {
  
    Comm ret = null;
    FieldNames fn = new FieldNames(CommId,PathId,SessionId,Created,Finished,Duration,InHeaders,OutHeaders);
    Record r = getProvider().first(getViewName(),fn,new Filter(CommId,commId));
    if (isNotNull(r)) {
      ret = new Comm(this,r);
    }
    return ret;
  }
  
}
