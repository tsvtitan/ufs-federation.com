/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.scheme;

import java.text.SimpleDateFormat;
import ufsic.providers.Record;
import ufsic.providers.Value;

/**
 *
 * @author zrv
 */
public class AccountFile extends SchemeRecord {

  public AccountFile() {
    super(null,null);
  }

  public AccountFile(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getAccountFileId() {
    return getValue(AccountFiles.AccountFileId);
  }
  
  public Value getAccountId() {
    return getValue(AccountFiles.AccountId);
  }
  
  public Value getName() {
    return getValue(AccountFiles.Name);
  }
  
  public Value getExtension() {
    return getValue(AccountFiles.Extension);
  }

  public Value getLocation() {
    return getValue(AccountFiles.Location);
  }
  
  public Value getCreated() {
    return getValue(AccountFiles.Created);
  }
  
  public Value getModified() {
    return getValue(AccountFiles.Modified);
  }

  public Value getData() {
    return getValue(Files.Data);
  }
  
  public Value getPageID() {
    return getValue(AccountFiles.PageID);
  }
  
  public Value getPathName() {
    return getValue(AccountFiles.PathName);
  }

  public Value getFileName() {
    return getValue(AccountFiles.FileName);
  }
  
  public void setData(Object data) {
    if (!setValue(Files.Data,data)) {
      add(Files.Data,data);
    }
  }
  
  public Value getDocCreated() {
    return getValue(AccountFiles.DocCreated);
  }
  
  public Value getFileDownloaded() {
    return getValue(AccountFiles.FileDownloaded);
  }
  
  public Value getFileDataSize() {
    return getValue(AccountFiles.FileDataSize);
  }
}
