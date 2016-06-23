package ufsic.scheme;

import ufsic.providers.Record;
import ufsic.providers.Value;

public class PromoBanner extends SchemeRecord {

  public PromoBanner(SchemeTable table, Record record) {
    super(table, record);
  }
  
  public Value getPromoBannerId() {

    return getValue(PromoBanners.PromoBannerId);
  }
  
  public Value getPageId() {

    return getValue(PromoBanners.PageId);
  }
  
  public Value getImageId() {

    return getValue(PromoBanners.ImageId);
  }
  
  public Value getPathId() {

    return getValue(PromoBanners.PathId);
  }

  public Value getLangId() {

    return getValue(PromoBanners.LangId);
  }
  
  public Value getName() {

    return getValue(PromoBanners.Name);
  }

  public Value getDescription() {

    return getValue(PromoBanners.Description);
  }

  public Value getPriority() {

    return getValue(PromoBanners.Priority);
  }

  public Value getPath() {

    return getValue(PromoBanners.Path);
  }
  
  public Value getImagePath() {

    return getValue(PromoBanners.ImagePath);
  }
  
  public Value getLink() {

    return getValue(PromoBanners.Link);
  }

  public String getNAME() {

    return getScheme().getDictionary().replace(getName().asString());
  }

  public String getDESCRIPTION() {

    return getScheme().getDictionary().replace(getDescription().asString());
  }
  
  public String getLINK() {

    return getLink().asString();
  }

  public String getIMAGE_PATH() {

    return getImagePath().asString();
  }
  
}