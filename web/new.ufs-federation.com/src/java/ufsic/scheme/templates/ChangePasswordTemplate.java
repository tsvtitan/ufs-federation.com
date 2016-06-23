/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.scheme.templates;

import java.io.StringReader;
import java.io.StringWriter;
import java.math.BigDecimal;
import java.sql.Timestamp;
import java.util.Date;
import java.util.Map;
import java.util.Random;
import ufsic.messages.MessageGate;
import ufsic.providers.FieldNames;
import ufsic.providers.Filter;
import ufsic.providers.Params;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.Message;
import ufsic.scheme.SchemeTable;
import ufsic.scheme.Template;
import ufsic.utils.Utils;
import java.util.Calendar;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonReader;
import javax.json.JsonValue;
import javax.json.JsonWriter;

//import org.apache.commons.codec.digest.DigestUtils;
/**
 *
 * @author zrv
 */
public class ChangePasswordTemplate extends Template {
  
  public ChangePasswordTemplate(SchemeTable table, Record record){
    super(table, record);
  }
  
  public enum ChangePasswordError {
    ErrorPassword, EmptyCurrentPassword, EmptyNewPassword, EmptyConfirmPassword, SamePassword, 
    NotMatchPassword, IncorrectedConfirmationCode, ActivationNotCome, ActivationExpired, AlredyActivated, NeedAuth
  }
  
  public class TemplateChangePassword {
    private String sessionID = null;
    private String activationCodeID = null;
    private String confirmationCode = null;
    private Date dateBegin = null;
    private Date dateEnd = null;
    private int activated = 0;
    
    private int stepFlow = 0;
    private String accounrID = null;
    private String currentPassword = null;
    private String newPassword = null;
    private String newPasswordConfirm = null;
    private boolean hasPhone = false;
    private boolean hasEmail = false;
    
    public TemplateChangePassword(){
      setSessionID(getScheme().getSessionId().toString());
    }
    
    public TemplateChangePassword(Record row)
    {
      //JsonObject model = Json.createReader(new StringReader(row.getValue("DATA").asString())).readObject();
      try{
        JsonReader reader = Json.createReader(new StringReader(row.getValue("DATA").asString()));
        JsonObject model = reader.readObject();
        
        setActivationCodeID(row.getValue("ACTIVATION_CODE_ID").asString());
        setSessionID(row.getValue("SESSION_ID").asString());
        setConfirmationCode(row.getValue("ACTIVATION_CODE").asString());
        setDateBegin(row.getValue("DATE_BEGIN"));
        setDateEnd(row.getValue("DATE_END"));
        setActivated(row.getValue("ACTIVATED").asInteger());        
        
        setStepFlow(Integer.parseInt(model.get("stepFlow").toString()));
        setAccounrID(model.getString("accountID"));
        setCurrentPassword(model.getString("currentPassword"));
        setNewPassword(model.getString("newPassword"));
        setNewPasswordConfirm(model.getString("newPasswordConfirm"));        
        setHasPhone(model.getString("hasPhone").equals("true"));
        setHasEmail(model.getString("hasEmail").equals("true"));
      }catch(Exception e)
      {
        //
      }
    }
    
    public int getStepFlow() {
      return stepFlow;
    }

    public final void setStepFlow(int stepFlow) {
      this.stepFlow = stepFlow;
    }

    public String getCurrentPassword() {
      return currentPassword;
    }

    public final void setCurrentPassword(String currentPassword) {
      this.currentPassword = currentPassword;
    }

    public String getNewPassword() {
      return newPassword;
    }

    public final void setNewPassword(String newPassword) {
      this.newPassword = newPassword;
    }

    public String getNewPasswordConfirm() {
      return newPasswordConfirm;
    }

    public final void setNewPasswordConfirm(String newPasswordConfirm) {
      this.newPasswordConfirm = newPasswordConfirm;
    }
    
    public String getConfirmationCode() {
      return confirmationCode;
    }

    public final void setConfirmationCode(String confirmationCode) {
      this.confirmationCode = confirmationCode;
    }

    public boolean isHasPhone() {
      return hasPhone;
    }

    public final void setHasPhone(boolean hasPhone) {
      this.hasPhone = hasPhone;
    }

    public boolean isHasEmail() {
      return hasEmail;
    }

    public final void setHasEmail(boolean hasEmail) {
      this.hasEmail = hasEmail;
    }

    public Date getDateBegin() {
      return dateBegin;
    }

    public final void setDateBegin(Date dateBegin) {
      this.dateBegin = dateBegin;
    }
    public final void setDateBegin(Value dateBegin) {
      if(dateBegin.isNotNull())
        this.dateBegin = new Date(dateBegin.asTimestamp().getTime());
      else
        this.dateBegin = null;
    }

    public Date getDateEnd() {
      return dateEnd;
    }

    public final void setDateEnd(Date dateEnd) {
      this.dateEnd = dateEnd;
    }
    public final void setDateEnd(Value dateEnd) {
      if(dateEnd.isNotNull())
        this.dateEnd = new Date(dateEnd.asTimestamp().getTime());
      else
        this.dateEnd = null;
    }

    public int getActivated() {
      return activated;
    }

    public final void setActivated(int activated) {
      this.activated = activated;
    }

    public String getSessionID() {
      return sessionID;
    }

    public final void setSessionID(String sessionID) {
      this.sessionID = sessionID;
    }
    
    public String getActivationCodeID() {
      return activationCodeID;
    }

    public final void setActivationCodeID(String activationCodeID) {
      this.activationCodeID = activationCodeID;
    }
    
    public String getAccounrID() {
      return accounrID;
    }

    public final void setAccounrID(String accounrID) {
      this.accounrID = accounrID;
    }
    
    public String asJSON()
    {
      String result;
      StringWriter stWriter;
      
      JsonObject model = Json.createObjectBuilder()
              .add("stepFlow", String.valueOf(this.getStepFlow()))
              .add("accountID", String.valueOf(getAccounrID()))
              .add("currentPassword", String.valueOf(this.getCurrentPassword()))
              .add("newPassword", String.valueOf(this.getNewPassword()))
              .add("newPasswordConfirm", String.valueOf(this.getNewPasswordConfirm()))
              //.add("confirmationCode", String.valueOf(this.getConfirmationCode()))
              .add("hasPhone", String.valueOf(this.isHasPhone()))
              .add("hasEmail", String.valueOf(this.isHasEmail()))
              .build();
      stWriter = new StringWriter();
      try (JsonWriter jsonWriter = Json.createWriter(stWriter)) {
        jsonWriter.writeObject(model);
      }
      result = stWriter.toString();
      return result;
    }
    
    protected boolean save()
    {
      boolean result;
      Record activationRecord = new Record();
      
      activationRecord.add("SESSION_ID", getSessionID());
      if(getConfirmationCode() != null)
      {
        activationRecord.add("ACTIVATION_CODE", getConfirmationCode());
      }
      if(getDateBegin() != null)
      {
        activationRecord.add("DATE_BEGIN", new Timestamp(getDateBegin().getTime()));
      }
      if(getDateEnd() != null)
      {
        activationRecord.add("DATE_END", new Timestamp(getDateEnd().getTime()));
      }
      activationRecord.add("ACTIVATED", getActivated());
      activationRecord.add("DATA", asJSON());
      
      if(getActivationCodeID() == null)
      {
        setActivationCodeID(Utils.getUniqueId());
        activationRecord.add("ACTIVATION_CODE_ID", getActivationCodeID());
        result = getProvider().insert("ACTIVATION_CODES", activationRecord);
      }else
      {
        result = getProvider().update("ACTIVATION_CODES", activationRecord, new Filter("ACTIVATION_CODE_ID", getActivationCodeID()));
      }
      return result;
    }
    
    protected boolean updateData()
    {
      Record activationRecord = new Record();
      activationRecord.add("DATA", asJSON());
      return getProvider().update("ACTIVATION_CODES", activationRecord, new Filter("ACTIVATION_CODE_ID", getActivationCodeID()));
    }
  };
  
  private TemplateChangePassword getChangePasswordData(String activationCode, String sessionID)
  {
    TemplateChangePassword changePasswordData;
    Record activationRecord;
    FieldNames fields;
    Filter filter;
    
    fields = new FieldNames("ACTIVATION_CODE_ID", "SESSION_ID","ACTIVATION_CODE","DATE_BEGIN","DATE_END","ACTIVATED","DATA");
    
    if(!activationCode.equals(""))
    {
      filter = new Filter("ACTIVATION_CODE", activationCode);
    }else
    {
      filter = new Filter("SESSION_ID", getScheme().getSessionId().toString());
    }
    
    activationRecord = getProvider().first("ACTIVATION_CODES", fields, filter);
    
    if(activationRecord != null)
    {
      changePasswordData = new TemplateChangePassword(activationRecord);
    }else
      changePasswordData = new TemplateChangePassword();
    
    return changePasswordData;
  }
  
  @Override
  public void process(Map<String, Object> vars) {
    
    super.process(vars);
    
    Account account;
    Value phone, email;
    TemplateChangePassword changePassword;    
    ChangePasswordError errorCode = null;
    Message m = null;
    MessageGate gate;
    String confirmCode = "", codeParameter;
    int addStep = 1;
    int activated;
    Object o;
    Provider provider = getProvider();
    
    Date dateBegin, dateEnd;
    Date currentDate = Calendar.getInstance().getTime();
    
    // Получаем параметр code (код активации), если передан
    o = getScheme().getPath().getParameterValue("code");
    codeParameter = o == null ? "" : o.toString() ;
    
    // Получаем данные процесса, если они были сохранены, или пустой объект
    changePassword = getChangePasswordData(codeParameter, getScheme().getSessionId().toString());
    
    // Получаем текущего пользователя
    account = getScheme().getAccount();
    
    // Если пользователь не авторизован, 
    if(isNull(account))
    {
      // то проверяем наличие параметра в URL
      if(codeParameter == null)
      {
        // и если параметра нет, выполняем редирект на страницу авторизации
        getScheme().getPath().redirect(getScheme().getPath().getRootPath("/auth"));   // TODO
        return;
      }else
      {
        // иначе проверяем параметры в таблице кодов активации
        
        // Если шаг больше 1-го
        if(changePassword.getStepFlow() > 1)
        {
          confirmCode = changePassword.getConfirmationCode();// activationRecord.getValue("ACTIVATION_CODE").asString();
          dateBegin = changePassword.getDateBegin();//new Date(activationRecord.getValue("DATE_BEGIN").asTimestamp().getTime());
          dateEnd = changePassword.getDateEnd();//new Date(activationRecord.getValue("DATE_END").asTimestamp().getTime());
          activated = changePassword.getActivated();//activationRecord.getValue("ACTIVATED").asInteger();
        
          // Если срок активации еще не наступил
          if(currentDate.before(dateBegin))
          {
            errorCode = ChangePasswordError.ActivationNotCome;
          }else
          {
            // Если срок активации уже истек
            if(currentDate.after(dateEnd))
            {
              errorCode = ChangePasswordError.ActivationExpired;
            }else
            {
              // Если активаци была выполнена
              if(activated > 0)
              {
                errorCode = ChangePasswordError.AlredyActivated;
              }
            }
          }
        }else
        {
          errorCode = ChangePasswordError.NeedAuth;
        }
        
        if(errorCode != null)
        {
          vars.put("errorCode", errorCode.toString());
        }else
        {
          if(confirmCode.equals(codeParameter))
          {
            changePassword.setConfirmationCode(confirmCode);//????
            changePassword.setStepFlow(2);
          }
        }
      }      
    }
    
    if(errorCode == null)
    {
      switch(changePassword.getStepFlow())
      {
        case 0:
          changePassword.setAccounrID(account.getAccountId().asString());
          break;
        case 1:
          // Шаг 1. Получаем данные с формы смены пароля и выполняем валидацию

          o = getScheme().getPath().getParameterValue("currentPassword");

          if (isNotNull(o) && !o.toString().equals("")) {

            changePassword.setCurrentPassword(o.toString());          
            o = getScheme().getPath().getParameterValue("newPassword");

            if (isNotNull(o) && !o.toString().equals("")) {

              changePassword.setNewPassword(o.toString());
              o = getScheme().getPath().getParameterValue("newPasswordConfirm");

              if (isNotNull(o) && !o.toString().equals("")) {

                changePassword.setNewPasswordConfirm(o.toString());

                if(!account.getPass().asString().equals(Utils.md5(changePassword.getCurrentPassword()).toUpperCase()))
                {
                  errorCode = ChangePasswordError.ErrorPassword;
                }else
                {
                  if(changePassword.getCurrentPassword().equals(changePassword.getNewPassword()))
                  {
                    errorCode = ChangePasswordError.SamePassword;
                  }else
                  {
                    if(!changePassword.getNewPassword().equals(changePassword.getNewPasswordConfirm()))
                    {
                      errorCode = ChangePasswordError.NotMatchPassword;
                    }
                  }
                }
              }else
              {
                errorCode = ChangePasswordError.EmptyConfirmPassword;
              }
            }else
            {
              errorCode = ChangePasswordError.EmptyNewPassword;
            }
          }else
          {
            errorCode = ChangePasswordError.EmptyCurrentPassword;
          }

          // В случае ошибки сообщаем об этом и остаемся на том же шаге
          if(errorCode != null)
          {
            vars.put("errorCode", errorCode.toString());
            addStep = 0;
            changePassword.setStepFlow(1);
          }else
          {
            // иначе проверяем контакты пользователя          
            phone = account.getPhone();
            email = account.getEmail();
            // Если есть телефон,
            if(phone.isNotNull())
            {
              // то отправляем код подтверждения операции по SMS
              Random rand = new Random();
              int randomNum = rand.nextInt(9998);
              confirmCode = String.format("%04d",randomNum);

              /*
              Условия отправки СМС
              SenderName = null
              SenderContact = null
              Subject - null
              Body - длина любая. Если отправлять повторно тот же текст, то провайдер позволит выполнить отправку только через 20 сек.
              SENT - null
              FROM - null или начало периода действия сообщения
              TO - null или конец периода действия сообщения
              PRIORITY - любой, в т.ч. null (самый низкий)
              DELIVERED - null
              */

              m = new Message(getScheme().getMessages());
              m.setMessageId(getProvider().getUniqueId());
              m.setCreatorId(getScheme().getApplicationId());
              m.setRecipientContact(phone.asString());
              m.setBody("Код подтверждения операции: " + confirmCode);

              //vars.put("confirmCode", confirmCode);
              vars.put("hasPhone", true);
              changePassword.setHasPhone(true);
            }else
            {
              // Если email указан,
              if(email.isNotNull())
              {
                // то отправляем письмо с ссылкой подтверждения операции по E-mail
                confirmCode = getProvider().getUniqueId().asString();

                m = new Message(getScheme().getMessages());
                m.setMessageId(getProvider().getUniqueId());
                m.setCreatorId(getScheme().getApplicationId());
                //m.setSenderName("Confirmation code");
                m.setSenderContact("mailer@ufs-federation.com");
                m.setRecipientContact(email.asString());
                m.setSubject("Confirmation code");
                m.setBody("Для завершения операции пройдите по ссылке http://" + getScheme().getURI().getHost() + ":" + getScheme().getURI().getPort() + 
                        getScheme().getPath().getRootPath("/change-password-confirmation?code=") + confirmCode);

                vars.put("hasEmail", true);
                changePassword.setHasEmail(true);
              }
            }

            if(m != null)
            {
              // Добавляем сообщение в очередь
              m.insert();

              // Уведомляем gate о необходимости выполнения проверки на наличии сообщений в очереди
              gate = getScheme().getController().getMessageGate();
              gate.checkOutgoing();

              // Сохраняем объект с данными процесса в БД            ;
              changePassword.setConfirmationCode(confirmCode);
              changePassword.setDateBegin(currentDate);
              changePassword.setDateEnd(new Date(currentDate.getTime() + 30 * 60000));
              changePassword.setActivated(0);
            }

            // Если контактов нет,
            if(isNull(account) || ( (phone.isNull()) && (email == null || email.isNull()) ))
            {
              // то меняем пароль и сразу переходим на последний шаг
              changePassword(changePassword.getAccounrID(), changePassword.getNewPassword());
              addStep = 2;
            }
          }
          break;

        case 2:

          // Проверка кода подтверждения, полученного по SMS или по e-mail

          //if (isNull(o) || (isNotNull(o) && !changePassword.getConfirmationCode().equals(o.toString())))
          if(changePassword.getConfirmationCode().equals("") || !changePassword.getConfirmationCode().equals(codeParameter))
          {
            errorCode = ChangePasswordError.IncorrectedConfirmationCode; 
          }else
          {
            // Меняем пароль
            changePassword(changePassword.getAccounrID(), changePassword.getNewPassword());
            changePassword.setActivated(1);
          }

          if(errorCode != null)
          {
            vars.put("confirmCode", changePassword.getConfirmationCode());
            if(changePassword.hasPhone) 
            {
              vars.put("hasPhone", true);
            }else
            {
              vars.put("hasEmail", true);
            }          
            vars.put("errorCode", errorCode.toString());
            addStep = 0;
            changePassword.setStepFlow(2);
          }

          break;

        case 3:
          addStep = 0;
          changePassword.setStepFlow(1);
          break;
      }
    
      changePassword.setStepFlow(changePassword.getStepFlow() + addStep);
      vars.put("step", changePassword.getStepFlow());
      
      changePassword.save();
    }
  }
  
  private void changePassword(String accountID, String newPassword){
    Params params = new Params();
    params.AddIn( "ACCOUNT_ID", new Value(accountID) );
    params.AddIn( "NEW_PASSWORD", new Value(newPassword) );
    getProvider().execute("CHANGE_PASSWORD", params);
  }
}
