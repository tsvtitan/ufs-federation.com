package ufsic.scheme.forms;

import ufsic.scheme.SchemeObject;

public class FormObject extends SchemeObject {

  private Form form;
  
  public FormObject(Form form) {
  
    super(form.getScheme());
    this.form = form;
  }
  
  public Form getForm() {
    
    return this.form;
  }
  
}
