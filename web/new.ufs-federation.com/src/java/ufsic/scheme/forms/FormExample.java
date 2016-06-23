package ufsic.scheme.forms;

import ufsic.scheme.PageForm;

public class FormExample extends Form {

  public FormExample(PageForm form) {
    super(form);
  }

  @Override
  protected boolean fieldCheck(Field field, Requirement requirement) {

    boolean ret = true;
    if (isNotNull(field)) {
      ret = requirement.check(field.getValue());
    }
    return ret;
  }
  
}
