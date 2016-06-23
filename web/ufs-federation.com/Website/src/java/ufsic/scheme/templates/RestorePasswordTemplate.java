package ufsic.scheme.templates;

import java.sql.Timestamp;
import ufsic.contexts.IVarContext;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.AccountPage;
import ufsic.scheme.Accounts;
import ufsic.scheme.Confirms;
import ufsic.scheme.PageForm;
import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.confirms.RestorePasswordConfirm;
import ufsic.scheme.forms.Form;
import ufsic.scheme.messages.EmailMessage;
import ufsic.scheme.messages.SmsMessage;
import ufsic.scheme.patterns.EmailPasswordRestorePattern;
import ufsic.scheme.patterns.SmsPasswordRestorePattern;
import ufsic.utils.PasswordBuilder;
import ufsic.utils.PasswordGenerator;
import ufsic.utils.Utils;

class RestorePasswordForm extends Form {

  public RestorePasswordForm(PageForm form) {
    super(form);
  }
  
  @Override
  public boolean process(IVarContext context) {

    boolean ret = false;
    
    Scheme scheme = getScheme();
    RestorePasswordConfirm confirm = new RestorePasswordConfirm(scheme);
    
    String confirmId = scheme.getPath().getRestPathValue();
    if (isNotNull(confirmId)) {
      
      /*GroupFilter gf = new GroupFilter();
          gf.And(new Filter(Confirms.ConfirmId,id));
          gf.And(new Filter().And(Confirms.Locked).IsNull());
          gf.And(new Filter().Add(Confirms.Begin).LessOrEqual(stamp).Or(Confirms.Begin).IsNull());
          gf.And(new Filter().Add(Confirms.End).GreaterOrEqual(stamp).Or(Confirms.End).IsNull());
          gf.And(new Filter().Add(Confirms.Confirmed).IsNull());*/
          
      ret = confirm.select(new Filter(Confirms.ConfirmId,confirmId));
      if (ret) {
        ret = confirm.getConfirmed().isNotNull();
        if (ret) {
          context.setLocalVar("confirmId",confirmId);
        }
      }
    }
     
    if (!ret) {
      
      ret = super.process(context);
      if (ret) {

        boolean success = getSuccess();
        if (success) {

          Form.Fields fields = getFields();

          Form.Field field = fields.findField("EMAIL_OR_PHONE");
          if (isNotNull(field)) {

            String value = field.getValue();

            Provider provider = getProvider();
            Timestamp stamp = provider.getNow().asTimestamp();

            Account account = new Account(scheme.getAccounts());

            GroupFilter filter = new GroupFilter();
            filter.And(new Filter(Accounts.Email,value).Or(Accounts.Phone,value));
            filter.And(new Filter().Add(Accounts.Locked).IsNull());

            success = account.select(new Filter(Accounts.Email,value).Or(Accounts.Phone,value));
            if (success) {

              AccountPage page = account.getConfirmationPage();
              if (isNotNull(page)) {

                String code = new PasswordBuilder().digits(6).shuffle().build();

                confirm.setSessionId(scheme.getSessionId());
                confirm.setAccountId(account.getAccountId());
                confirm.setMessageId(null);
                confirm.setBegin(stamp);
                if (Utils.isEmail(value)) {
                  confirm.setEnd(Utils.addMinutes(stamp,30));
                } else if (Utils.isPhone(value)) { 
                  confirm.setEnd(Utils.addMinutes(stamp,10));
                }
                confirm.setLocked(stamp);
                confirm.setCode(code);
                confirm.setPathId(getPathId());

                String password = PasswordGenerator.generate(6,6,2,1,1);
                
                confirm.addParam("password",password);

                success = confirm.save();
                if (success) {

                  Value messageId = null;
                  String url = confirm.getUrl(page.getPagePath().asString());

                  if (Utils.isEmail(value)) {

                    EmailPasswordRestorePattern pattern = new EmailPasswordRestorePattern(getScheme());
                    pattern.setCode(code);
                    pattern.setUrl(url);
                    pattern.setPassword(password);

                    EmailMessage message = new EmailMessage(pattern);
                    message.setBegin(confirm.getBegin());
                    message.setEnd(confirm.getEnd());

                    success = message.send(account);
                    messageId = message.getMessageId();

                  } else if (Utils.isPhone(value)) { 

                    SmsPasswordRestorePattern pattern = new SmsPasswordRestorePattern(getScheme());
                    pattern.setCode(code);
                    pattern.setPassword(password);

                    SmsMessage message = new SmsMessage(pattern);
                    message.setBegin(confirm.getBegin());
                    message.setEnd(confirm.getEnd());

                    success = message.send(account);
                    messageId = message.getMessageId();
                  }

                  if (success) {

                    confirm.setMessageId(messageId);
                    confirm.setLocked((Timestamp)null);
                    success = confirm.save();

                    if (success) {
                      setRedirect(url);
                    }
                  } else {
                    getMessages().add(new Message("Error",true)); //???
                  }
                }
              }
            }
            setSuccess(success);
          }
        }
      }
    }
    return ret;
  }
  
}

public class RestorePasswordTemplate extends FormTemplate {

  public RestorePasswordTemplate(SchemeTable table, Record record) {
    super(table, record);
  }
  
  @Override
  protected Class getFormClass() {
    return RestorePasswordForm.class;
  }
  
}
