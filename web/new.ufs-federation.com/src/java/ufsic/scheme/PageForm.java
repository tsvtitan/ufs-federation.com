package ufsic.scheme;

import ufsic.providers.Filter;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.providers.Value;

public class PageForm extends SchemeRecord {

  private final PageTableForms pageTableForms;
  
  public PageForm(SchemeTable table, Record record) {
    
    super(table, record);
    
    this.pageTableForms = new PageTableForms(getScheme());
  }
  
  public Value getPageFormId() {
    
    return getValue(PageForms.PageFormId);
  }
  
  public Value getPageId() {
    
    return getValue(PageForms.PageId);
  }

  public Value getLangId() {

    return getValue(PageForms.LangId);
  }

  public Value getTemplateId() {

    return getValue(PageForms.TemplateId);
  }
  
  public Value getName() {

    return getValue(PageForms.Name);
  }

  public Value getDescription() {

    return getValue(PageForms.Description);
  }
  
  public Value getProcName() {
    
    return getValue(PageForms.ProcName);
  }

  public Value getFields() {
    
    return getValue(PageForms.Fields);
  }
  
  public Value getAsync() {
    
    return getValue(PageForms.Async);
  }
  
  public Value getPriority() {
    
    return getValue(PageForms.Priority);
  }
  
  public Value getDefaults() {
    
    return getValue(PageForms.Defaults);
  }
  
  public Value getRequirements() {
    
    return getValue(PageForms.Requirements);
  }

  public Value getPlaceHolders() {
    
    return getValue(PageForms.PlaceHolders);
  }

  public Value getButtons() {
    
    return getValue(PageForms.Buttons);
  }

  public Value getMaxLengths() {
    
    return getValue(PageForms.MaxLengths);
  }

  public Value getLists() {
    
    return getValue(PageForms.Lists);
  }

  public Value getStyles() {
    
    return getValue(PageForms.Styles);
  }

  public Value getTransforms() {
    
    return getValue(PageForms.Transforms);
  }

  public Value getParentId() {
    
    return getValue(PageForms.ParentId);
  }

  public Value getCaptchaType() {
    
    return getValue(PageForms.CaptchaType);
  }
  
  public Value getLastPageFormId() {
    
    return getValue(PageForms.LastPageFormId);
  }
  
  /*public Value getPath() {
    
    return getValue(PageForms.Path);
  }*/

  public PageTableForms getPageTableForms(boolean refresh) {
    
    if (refresh) {
      pageTableForms.open(new Filter(PageTableForms.PageFormId,getPageFormId()),new Orders(PageTableForms.Priority));
    }
    return pageTableForms;
  }
  
  public PageTableForms getPageTableForms() {
    
    return getPageTableForms(pageTableForms.isEmpty());
  }
  
}
