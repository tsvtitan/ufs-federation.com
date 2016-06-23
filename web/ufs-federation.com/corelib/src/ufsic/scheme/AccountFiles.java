/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.scheme;

import java.util.ArrayList;
import ufsic.providers.Dataset;
import ufsic.providers.Filter;
import ufsic.providers.Record;

/**
 *
 * @author zrv
 */
public class AccountFiles extends SchemeTable {
  public final static String TableName = "ACCOUNT_FILES";
  
  public final static String AccountFileId = "ACCOUNT_FILE_ID";
  public final static String AccountId = "ACCOUNT_ID";
  public final static String Name = "NAME";
  public final static String Extension = "EXTENSION";
  public final static String Location = "LOCATION";
  public final static String Created = "CREATED";
  public final static String Modified = "MODIFIED";
  public final static String Data = "DATA";
  public final static String PageID = "PAGE_ID";
  public final static String DocCreated = "DOC_CREATED";
  public final static String FileDownloaded = "FILE_DOWNLOADED";  
  public final static String FileDataSize = "FILE_DATA_SIZE";
  
  public final static String PathName = "PATH_NAME";
  public final static String FileName = "FILE_NAME";
  
  public AccountFiles(Scheme scheme, String viewName) {
    super(scheme, viewName);
  }

  public AccountFiles(Scheme scheme) {
    super(scheme,TableName);
  }

  @Override
  public Class getRecordClass() {
    return File.class;
  }
  
  public ArrayList<AccountFile> getAccountFiles(String accountID, String pageID)
  //public <T extends AccountFile> ArrayList<T> getAccountFiles(String accountID, String pageID, Class<T> type)
  {
    //ArrayList<T> ret = null;
    //T accountFile = null;
    ArrayList<AccountFile> ret = null;
    AccountFile accountFile = null;
    Dataset<Record> ds = getProvider().select(getViewName(), new Filter(AccountId, accountID).And(PageID, pageID));
    
    if (isNotNull(ds)) { 
      //ret = new ArrayList<T>();
      ret = new ArrayList<>();
      for (Record r: ds) {      
        /*Constructor con;
        try {          
          con = type.getConstructor(type.getEnclosingClass(), SchemeTable.class, Record.class);
          accountFile = (T)con.newInstance(this, r);
          ret.add(accountFile);
        } catch (Exception ex) {
          logInfo(ex);
        }
        accountFile = type.cast(new AccountFile(this, r));*/
        accountFile = new AccountFile(this, r);
        ret.add(accountFile);
      }
    }
    return ret;
  }
}
