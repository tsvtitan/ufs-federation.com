package ufsic.scheme.mobile;

import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.SchemeRecord;
import ufsic.scheme.SchemeTable;

public class MobileFile extends SchemeRecord {

  public MobileFile(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public MobileFile(SchemeTable table) {
    super(table, null);
  }
  
  public Value getMobileFileId() {
    
    return getValue(MobileFiles.MobileFileId);
  }

  public void setMobileFileId(Value mobileFileId) {

    if (!setValue(MobileFiles.MobileFileId,mobileFileId)) {
      add(MobileFiles.MobileFileId,mobileFileId);
    }
  }
   
  public Value getTokenId() {
    
    return getValue(MobileFiles.TokenId);
  }

   public void setTokenId(Value tokenId) {

    if (!setValue(MobileFiles.TokenId,tokenId)) {
      add(MobileFiles.TokenId,tokenId);
    }
  } 
   
  public Value getCacheId() {
    
    return getValue(MobileFiles.CacheId);
  }

  public void setCacheId(Value cacheId) {

    if (!setValue(MobileFiles.CacheId,cacheId)) {
      add(MobileFiles.CacheId,cacheId);
    }
  } 
  
  public void setCacheId(String cacheId) {

    if (!setValue(MobileFiles.CacheId,cacheId)) {
      add(MobileFiles.CacheId,cacheId);
    }
  }
  
  public Value getCreated() {
  
    return getValue(MobileFiles.Created);
  }
  
  public void setCreated(Value created) {

    if (!setValue(MobileFiles.Created,created)) {
      add(MobileFiles.Created,created);
    }
  }
  
  public Value getLocation() {
    
    return getValue(MobileFiles.Location);
  }
  
  public void setLocation(Value location) {

    if (!setValue(MobileFiles.Location,location)) {
      add(MobileFiles.Location,location);
    }
  }
  
  public void setLocation(String location) {

    if (!setValue(MobileFiles.Location,location)) {
      add(MobileFiles.Location,location);
    }
  }
  
  public Value getName() {
    
    return getValue(MobileFiles.Name);
  }
  
  public void setName(Value name) {

    if (!setValue(MobileFiles.Name,name)) {
      add(MobileFiles.Name,name);
    }
  }
  
  public void setName(String name) {

    if (!setValue(MobileFiles.Name,name)) {
      add(MobileFiles.Name,name);
    }
  }  
  
  public Value getExtension() {
    
    return getValue(MobileFiles.Extension);
  }

  public void setExtension(Value extension) {

    if (!setValue(MobileFiles.Extension,extension)) {
      add(MobileFiles.Extension,extension);
    }
  }
  
  public void setExtension(String extension) {

    if (!setValue(MobileFiles.Extension,extension)) {
      add(MobileFiles.Extension,extension);
    }
  }  
  
  public Value getContentType() {
    
    return getValue(MobileFiles.ContentType);
  } 

  public void setContentType(Value contentType) {

    if (!setValue(MobileFiles.ContentType,contentType)) {
      add(MobileFiles.ContentType,contentType);
    }
  }
  
  public void setContentType(String contentType) {

    if (!setValue(MobileFiles.ContentType,contentType)) {
      add(MobileFiles.ContentType,contentType);
    }
  }  
  
  public Value getFileSize() {
    
    return getValue(MobileFiles.FileSize);
  } 

  public void setFileSize(Value fileSize) {

    if (!setValue(MobileFiles.FileSize,fileSize)) {
      add(MobileFiles.FileSize,fileSize);
    }
  }
  
  public void setFileSize(Integer fileSize) {

    if (!setValue(MobileFiles.FileSize,fileSize)) {
      add(MobileFiles.FileSize,fileSize);
    }
  }
  
  public Value getCacheData() {
    
    return getValue(MobileFiles.CacheData);
  }
  
  public Value getCacheSize() {
    
    return getValue(MobileFiles.CacheSize);
  }
  
  public Value getCacheExpired() {
    
    return getValue(MobileFiles.CacheExpired);
  }
  
}
