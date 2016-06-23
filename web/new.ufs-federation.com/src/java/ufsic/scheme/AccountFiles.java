/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.scheme;

import java.util.ArrayList;
import ufsic.providers.DataSet;
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
  {
    ArrayList<AccountFile> ret = null;
    AccountFile accountFile = null;
    DataSet ds = provider.select(getViewName(), new Filter(AccountId, accountID).And(PageID, pageID));
    
    if (isNotNull(ds)) { 
      ret = new ArrayList<AccountFile>();
      for (Record r: ds) {
        //ret = ret +" - "+ r.getValue("BEFORE_BALANCE").asString();  
        accountFile = new AccountFile(this, r);
        ret.add(accountFile);
      }
    }
    return ret;
  }
}
