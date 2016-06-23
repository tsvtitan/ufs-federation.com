package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class Publications extends SchemeTable {

  public final static String TableName = "PUBLICATIONS";
  
  public final static String PublicationId = "PUBLICATION_ID";
  public final static String SourceId = "SOURCE_ID";
  public final static String ImageId = "IMAGE_ID";
  public final static String Posted = "POSTED";
  public final static String StickTill = "STICK_TILL";
  public final static String Excerpt = "EXCERPT";
  public final static String Html = "HTML";
  
  public final static String Path = "PATH";
  public final static String Title = "TITLE";
  public final static String LangId = "LANG_ID";
  public final static String ImagePath = "IMAGE_PATH";
  public final static String SourceName = "SOURCE_NAME";
  public final static String SourceImagePath = "SOURCE_IMAGE_PATH";
  
  public Publications(Scheme scheme, String name) {
    super(scheme, name);
  }

  public Publications(Scheme scheme) {
    super(scheme);
    this.name = TableName;
  }
  
  @Override
  public Class getRecordClass() {
    
    return Publication.class;
  }

  public Publication getPublication(Value pageId) {
    
    Publication ret = null;
    Record r = provider.first(getViewName(),new Filter(PublicationId,pageId));
    if (isNotNull(r)) {
      ret = new Publication(this,r);
    }
    return ret;
  }
  
}
