/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.scheme.templates;

import java.util.ArrayList;
import java.util.Map;
import ufsic.providers.Record;
import ufsic.scheme.Account;
import ufsic.scheme.AccountFile;
import ufsic.scheme.AccountFiles;
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
  public void process(Map<String, Object> vars) {
    
    Account account;
    String path;
    ArrayList<AccountFile> accountFileList = null;
    
    super.process(vars);
    
    // Получаем текущего пользователя
    account = getScheme().getAccount();
    
    // Если пользователь авторизован, 
    if(isNotNull(account))
    {
      //path = getScheme().getPath().getRootPath();
      
      accountFileList = account.getAccountFiles(getScheme().getPage().getPageId().asString());
      if (accountFileList != null) {
        vars.put("accountFiles", accountFileList);
      }
    }
    
  }
  
  
}
