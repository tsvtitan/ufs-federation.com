package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class Publication extends SchemeRecord {
  
  public Publication(SchemeTable table, Record record) {
    super(table, record);
  }

  public Value getPublicationId() {
    
    return getValue(Publications.PublicationId);
  }

  public Value getSourceId() {
    
    return getValue(Publications.SourceId);
  }

  public Value getImageId() {
    
    return getValue(Publications.ImageId);
  }

  public Value getPosted() {
    
    return getValue(Publications.Posted);
  }

  public String getPosted(String format) {
    
    return getScheme().valueFormat(getPosted(),format);
  }
  
  public Value getStickTill() {
    
    return getValue(Publications.StickTill);
  }

  public Value getExcerpt() {
    
    return getValue(Publications.Excerpt);
  }

  public Value getHtml() {
    
    return getValue(Publications.Html);
  }

  public Value getPath() {
    
    return getValue(Publications.Path);
  }

  public Value getTitle() {
    
    return getValue(Publications.Title);
  }
  
  public Value getImagePath() {
    
    return getValue(Publications.ImagePath);
  }

  public Value getSourceName() {
    
    return getValue(Publications.SourceName);
  }
  
  public Value getSourceImagePath() {
    
    return getValue(Publications.SourceImagePath);
  }

  public String getEXCERPT() {
    
    return getExcerpt().asString();
  }
  
  public String getHTML() {
    
    return getHtml().asString();
  }

  public String getTITLE() {
    
    return getScheme().getDictionary().replace(getTitle().asString());
  }
  
  public String getPATH() {
    
    return getPath().asString();
  }

  public String getIMAGE_PATH() {
    
    return getImagePath().asString();
  }

  public String getSOURCE_NAME() {
    
    return getSourceName().asString();
  }
  
  public String getSOURCE_IMAGE_PATH() {
    
    return getSourceImagePath().asString();
  }
  
}
