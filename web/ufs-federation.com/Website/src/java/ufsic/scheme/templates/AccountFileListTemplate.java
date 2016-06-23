/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.scheme.templates;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Locale;
import java.util.Map;
import ufsic.contexts.IVarContext;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.AccountFile;
import ufsic.scheme.AccountFiles;
import ufsic.scheme.Path;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;

/**
 *
 * @author zrv
 */
public class AccountFileListTemplate extends Template {

  public AccountFileListTemplate(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  public void process(IVarContext context) {
    
    Account account;
    String path;
    ArrayList<AccountFile> accountFileListTemp = null;
    ArrayList<AccountFileEx> accountFileList = new ArrayList<>();
    
    super.process(context);
    
    // Получаем текущего пользователя
    account = getScheme().getAccount();
    
    // Если пользователь авторизован, 
    if(isNotNull(account))
    {
      accountFileListTemp = account.getAccountFiles(getScheme().getPage().getPageId().asString()/*, AccountFileEx.class*/);
      
      for (AccountFile accountFile : accountFileListTemp) {
        AccountFileEx accountFileEx = new AccountFileEx(new AccountFiles(getScheme()), accountFile);
        accountFileList.add(accountFileEx);
      }
      
      context.setLocalVar("accountFiles", accountFileList);
    }
    
  }
}

class AccountFileEx extends AccountFile {

  public AccountFileEx(AccountFile accountFile) {
    
  }
  
  public AccountFileEx(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getCreatedString(int dateFormat) {
    Locale loc = new Locale(getScheme().getLang().getLangId().asString());
    DateFormat df = DateFormat.getDateInstance(dateFormat, loc);
    //SimpleDateFormat sdf = new SimpleDateFormat("dd MMM yyyy", loc);
    return new Value(df.format(super.getCreated().asTimestamp().getTime()));
  }
  
  public Value getDocCreatedString(int dateFormat) {
    Locale loc = new Locale(getScheme().getLang().getLangId().asString());
    DateFormat df = DateFormat.getDateInstance(dateFormat, loc);  //DateFormat.MEDIUM
    return new Value(df.format(super.getDocCreated().asTimestamp().getTime()));
  }
}