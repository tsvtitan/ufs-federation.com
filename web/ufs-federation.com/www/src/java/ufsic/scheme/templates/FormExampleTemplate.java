package ufsic.scheme.templates;

import ufsic.providers.Record;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.forms.FormExample;

public class FormExampleTemplate extends FormTemplate {

  public FormExampleTemplate(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  protected Class getFormClass() {
    return FormExample.class;
  }
  
}
