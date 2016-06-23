package ufsic.scheme.templates;

import java.lang.reflect.Constructor;
import ufsic.contexts.IVarContext;
import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.scheme.Page;
import ufsic.scheme.PageForm;
import ufsic.scheme.PageForms;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;
import ufsic.scheme.forms.Form;

public class FormTemplate extends Template {

  public FormTemplate(SchemeTable table, Record record) {
    super(table, record);
  }
 
  protected Class getFormClass() {
    return Form.class;
  }
  
  protected Form newForm(PageForm form) {
    
    Form ret = null;

    Class cls = getFormClass();
    if (isNull(cls)) {
      cls = Form.class;
    }
    if (isNotNull(cls)) {
      try {
        Constructor con = cls.getConstructor(PageForm.class);
        if (isNotNull(con)) {

          ret = (Form)con.newInstance(form);
        }
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  @Override
  public void process(IVarContext context) {
    
    super.process(context);

    boolean newForm = !context.localExists("form");
    
    /*Object obj = context.getLocalVar("form");
    if (isNotNull(obj) && (obj instanceof Form)) {
      newForm = false;
    }*/
    
    if (newForm) {
      
      PageForm form = null;
      
      Page page = getScheme().getPage(); 
      if (isNotNull(page)) {
        
        PageForms forms = page.getPageForms();
        if (isNotNull(forms) && (forms.size()>0)) {
      
          form = (PageForm)forms.get(0);
        }
      } else {

        Object pageFormId = getScheme().getPath().getParameterValue("pageFormId");
        if (isNotNull(pageFormId)) {
          
          PageForms forms = new PageForms(getScheme());
          Record r = getProvider().first(forms.getViewName(),new Filter(PageForms.PageFormId,pageFormId));
          if (isNotNull(r)) {
            form = new PageForm(forms,r);
          }
        }
      }
      
      if (isNotNull(form)) {
        
        Form fm = newForm(form);
        if (fm.process(context)) {
          context.setLocalVar("form",fm);
        }
      }
    }
  }
}