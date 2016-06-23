package ufsic.scheme.templates;

import java.sql.Timestamp;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.concurrent.TimeUnit;
import ufsic.contexts.IVarContext;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Confirm;
import ufsic.scheme.Confirms;
import ufsic.scheme.Page;
import ufsic.scheme.PageForm;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.forms.Form;
import ufsic.scheme.forms.Requirement;
import ufsic.utils.Utils;

class ConfirmForm extends Form {

  private Confirm confirm = null;
  
  public ConfirmForm(PageForm form) {
    super(form);
  }

  private Long getTimerDuration() {

    Long ret = null;
    if (isNotNull(confirm)) {
      
      Value end = confirm.getEnd();
      if (end.isNotNull()) {
        Date d = new Date();
        Timestamp t1 =  new Timestamp(d.getTime());
        Timestamp t2 = end.asTimestamp();
        ret = t2.getTime()-t1.getTime();
        if (ret<0) {
          ret = null;
        }
      }
    }
    return ret;
    
  }
  
  public Long getTimerSeconds() {
  
    Long ret = getTimerDuration();
    if (isNotNull(ret)) {
      ret = ret / 1000;
    }
    return ret;
  }
  
  public String getConfirmId() {
    
    String ret = null;
    
    Page page = getScheme().getPage();
    if (isNotNull(page)) {
      ret = getScheme().getPath().getRestPathValue();
    } else {
      ret = getScheme().getPath().getParameterValue("confirmId",ret);
    }
    return ret;
  }
  
  protected Confirm getConfirm() {
    
    Confirm ret = null;
    if (isNull(ret)) {
      String id = getConfirmId();
      if (isNotNull(id)) {

        Confirms confirms = getScheme().getConfirms();
        
        Confirm c = (Confirm)confirms.findFirst(Confirms.ConfirmId,id);
        if (isNotNull(c) && c.getLocked().isNotNull()) {
          ret = c;
        } else {
          
          Value stamp = getProvider().getNow();

          GroupFilter gf = new GroupFilter();
          gf.And(new Filter(Confirms.ConfirmId,id));
          gf.And(new Filter().And(Confirms.Locked).IsNull());
          gf.And(new Filter().Add(Confirms.Begin).LessOrEqual(stamp).Or(Confirms.Begin).IsNull());
          gf.And(new Filter().Add(Confirms.End).GreaterOrEqual(stamp).Or(Confirms.End).IsNull());
          gf.And(new Filter().Add(Confirms.Confirmed).IsNull());
          
          Record r = getScheme().getProvider().first(confirms.getViewName(),gf);
          if (isNotNull(r)) {
            ret = confirms.newConfirm(r);
          }
        }
      }
    }
    return ret;
  }
  
  @Override
  protected boolean fieldCheck(Field field, Requirement requirement) {

    boolean ret = true;
    if (isNotNull(field)) {
      
      String name = field.getName();
      String value = field.getValue();
      
      if (name.equals("CODE") && isNull(requirement.getMethod())) {
        if (isNotNull(confirm)) {
          Value code = confirm.getCode();
          if (code.isNotNull()) {
            ret = code.same(value);
          }
        } else {
          ret = false;
        }
      } else {
        ret = requirement.check(value);
      }
    }
    return ret;
  }
  
  @Override
  public boolean process(IVarContext context) {
    
    this.confirm = getConfirm();
    
    boolean ret = super.process(context);
    if (ret) {

      boolean success = getSuccess();
      
      if (isNotNull(confirm)) {
        context.setLocalVar("EmailOrPhone",confirm.getRecipientContact().asString());
        if (success) {
          success = confirm.process();
          if (success) {
            setRedirect(confirm.getPathUrl());
          }
        }
      } else {
        success = false;
      }
      setSuccess(success);
    }
    return ret;
  }
  
}

public class ConfirmTemplate extends FormTemplate {

  public ConfirmTemplate(SchemeTable table, Record record) {
    super(table, record);
  }

  @Override
  protected Class getFormClass() {
    return ConfirmForm.class;
  }
  
}
