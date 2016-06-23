package ufsic.services;

import java.lang.reflect.Constructor;
import java.net.URI;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;
import java.util.concurrent.ConcurrentHashMap;

import java.util.concurrent.ScheduledFuture;
import java.util.concurrent.TimeUnit;

import javax.ejb.EJB;
import javax.ejb.Stateless;
import javax.jws.WebMethod;
import javax.jws.WebParam;

import ufsic.applications.MessageGate;

import ufsic.out.Logger;
import ufsic.providers.Dataset;
import ufsic.providers.FieldNames;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.scheme.Messages;
import ufsic.scheme.Scheme;
import ufsic.scheme.Subscription;
import ufsic.scheme.Subscriptions;
import ufsic.scheme.messages.PatternMessage;
import ufsic.scheme.patterns.EmailDefaultPattern;
import ufsic.scheme.patterns.SmsDefaultPattern;
import ufsic.utils.Utils;


@javax.jws.WebService(serviceName = "MessageGate",name="WebService")
@Stateless
public class MessageGateWebService extends WebService {

  private static final String timestampFormat = "yyyy-MM-dd HH:mm:ss";
  
  public static class Recipient {
    public String contact = null;
    public String name = null;
    public Integer priority = null;
    
    public Recipient() {
      
    }
    
    public Recipient(String contact, String name, Integer priority) {
      this.contact = contact;
      this.name = name;
      this.priority = priority;
    }
  }
  
  public static class Recipients extends ArrayList<Recipient> {
  
  }
  
  public static class Pattern {
    public String pattern;
    public String deliveryType;
  }
  
  public static class Patterns extends ArrayList<Pattern> {
    
    public String findFirst(Subscription.DeliveryType deliveryType) {
      
      String ret = null;
      for (Pattern p: this) {

        if (Utils.isNotNull(p) && 
            deliveryType.name().equalsIgnoreCase(p.deliveryType)) {
          ret = p.pattern;
          break;
        }
      }
      return ret;
    }
    
    public String getFirstEmpty() {
      
      String ret = null;
      for (Pattern p: this) {

        if (Utils.isNotNull(p) && Utils.isNull(p.deliveryType)) {
          ret = p.pattern;
          break;
        }
      }
      return ret;
    }
    
  }
  
  public static class Keyword {
    public String keyword;
    public String deliveryType;
  }
  
  public static class Keywords extends ArrayList<Keyword> {
    
    private boolean existsEmpty(String keyword) {
      
      boolean ret = false;
      for (Keyword k: this) {
        
        if (Utils.isNotNull(k) && 
            keyword.equalsIgnoreCase(k.keyword) &&
            Utils.isNull(k.deliveryType)) {
          ret = true;
          break;
        }
      }
      return ret;
    }
    
    public boolean exists(String keyword, Subscription.DeliveryType deliveryType) {
      
      boolean ret = false;
      for (Keyword k: this) {
        
        if (Utils.isNotNull(k) && 
            keyword.equalsIgnoreCase(k.keyword) &&
            deliveryType.name().equalsIgnoreCase(k.deliveryType)) {
          ret = true;
          break;
        }
      }
      if (!ret) {
        ret = existsEmpty(keyword);
      }
      return ret;
    }
    
    @Override
    public boolean isEmpty() {
      
      boolean ret = super.isEmpty();
      if (!ret) {
        for (Keyword k: this) {
          if (Utils.isNull(k)) {
            ret = true;
            break;
          }
        }
      }
      return ret;
    }
  }
  
  public static class Value {
  
    public String name = null;
    public String value = null;
  }
  
  public static class Var {
    public String contact;
    public ArrayList<Value> values;
  }
  
  public static class Vars extends ArrayList<Var> {
    
  }
  
  public static class Attachment {
    public String name;
    public String extension;
    public byte[] data;
    public String contentType;
    public String contentId;
    public Integer size;
    
    private boolean dataDecoded() {
      return (size == data.length);
    }
    
    public byte[] getData() {
      
      if (!dataDecoded()) {
        data = Utils.decodeBase64(data);
        size = data.length;
      }
      return data;
    }
  }
  
  public static class Attachments extends ArrayList<Attachment> {
    
    @Override
    public boolean isEmpty() {
      
      boolean ret = super.isEmpty();
      if (!ret) {
        for (Attachment att: this) {
          if (Utils.isNull(att)) {
            ret = true;
            break;
          }
        }
      }
      return ret;
    }
  }
  
  public static class Header {
    
    public String name;
    public String value;
  }
  
  public static class Headers extends ArrayList<Header> {
    
  }
  
  public static class Message {
    public String channelId;
    public String senderName;
    public String senderContact;
    public Recipients recipients;
    public String subject;
    public String body;
    public String begin;
    public String end;
    public Integer priority;
    public Patterns patterns;
    public Keywords keywords;
    public Vars vars;
    public Attachments attachments;
    public Headers headers;
    public Boolean unique;
    
    private boolean bodyDecoded = false;
    
    public String getBody() {
      
      if (!bodyDecoded) {
        if (Utils.isNotNull(body))
          body = Utils.decodeBase64(body);
        bodyDecoded = true;
      }
      return body;
    }
    
    public boolean bodyExists() {
      
      return Utils.isNotNull(getBody());
    }
  }
  
  public static class StatusResult {
    public String messageId = null;
    public Integer allCount = 0;
    public Integer sentCount = 0;
    public Integer deliveredCount = 0;
    public Integer errorCount = 0;
  }
  
  public static class QueueResult {
    public String messageId = null;
    public Integer queueLength = 0;
  }
  
  public static class SendResult extends QueueResult {
    
    public SendResult() {
      super();
    }
    
    public SendResult(QueueResult result) {
      this.messageId = result.messageId;
      this.queueLength = result.queueLength;
    }
  }
  
  @EJB
  private MessageGate messageGate;
  
  @WebMethod(exclude = true)
  @Override
  public Logger getLogger() {
    
    return messageGate.getLogger();
  }

  private Map<String,Recipient> getRecipients(Scheme scheme, Recipients recipients, 
                                            String body, Keywords keywords, 
                                            Integer priority, Boolean unique) {
  
    Map<String,Recipient> ret = new HashMap<>();
    
    if (isNotNull(recipients) && !recipients.isEmpty()) {
      for (Recipient r: recipients) {
        if (isNotNull(r)) {
          if (isNotNull(unique) && unique) {
            ret.put(r.contact,r);
          } else {
            ret.put(Utils.getUniqueId(),r);
          }
        }
      }
    }
    
    if (isNotNull(keywords) && !keywords.isEmpty() && !isEmpty(body)) {
      
      ufsic.providers.Value stamp = scheme.getStamp();
      
      Subscriptions subs = new Subscriptions(scheme);
      
      FieldNames fieldNames = new FieldNames(Subscriptions.Keyword,Subscriptions.DeliveryType,
                                             Subscriptions.Email,Subscriptions.Phone,Subscriptions.Name);
      
      GroupFilter filter = new GroupFilter();
      filter.And(Subscriptions.LangId,scheme.getLangId(true));

      GroupFilter f1 = new GroupFilter();
      f1.Or(new Filter().And(Subscriptions.Started).IsNull());
      f1.Or(new Filter().Add(Subscriptions.Started).IsNotNull().And(Subscriptions.Started).LessOrEqual(stamp));
      filter.And(f1);

      GroupFilter f2 = new GroupFilter();
      f2.Or(new Filter().And(Subscriptions.Finished).IsNull());
      f2.Or(new Filter().Add(Subscriptions.Finished).IsNotNull().And(Subscriptions.Finished).Greater(stamp));
      filter.And(f2);

      GroupFilter f3 = new GroupFilter();
      f3.Or(Subscriptions.DeliveryType,Subscription.DeliveryType.EMAIL.name());
      f3.Or(Subscriptions.DeliveryType,Subscription.DeliveryType.SMS.name());
      filter.And(f3);
      
      if (subs.open(fieldNames,filter)) {
        
        body = body.toLowerCase();
        
        for (Subscription sub: subs) {
         
          String keyword = sub.getKeyword().asString();
          if (!isEmpty(keyword)) {
            
            String email = sub.getEmail().asString();
            String phone = sub.getPhone().asString();
            
            Subscription.DeliveryType deliveryType = Subscription.DeliveryType.valueOf(sub.getDeliveryType().asString());
            if (isNotNull(deliveryType)) {
              
              boolean check = (Utils.isEmail(email) && deliveryType==Subscription.DeliveryType.EMAIL) ||
                              (Utils.isPhone(phone) && deliveryType==Subscription.DeliveryType.SMS);
              
              if (check) {
                
                if (keywords.exists(keyword,deliveryType) || body.contains(keyword.toLowerCase())) {
                  
                  switch (deliveryType) {
                    case EMAIL: {
                      if (!ret.containsKey(email)) {
                        ret.put(email,new Recipient(email,sub.getName().asString(),priority));
                      }
                      break;
                    }
                    case SMS: {
                      if (!ret.containsKey(phone)) {
                        ret.put(phone,new Recipient(email,sub.getName().asString(),priority));
                      }
                      break;
                    }
                  }
                  
                }
              }
            }
          }
        }
      }
      
    }
    return ret;
  }
  
  private Map<String,Object> getVars(Vars vars, String contact) {
    
    Map<String,Object> ret = new HashMap<>();
    if (isNotNull(vars)) {
      for (Var v: vars) {

        if (isNotNull(v) && contact.equalsIgnoreCase(v.contact)) {

          for (Value vl: v.values) {
            ret.put(vl.name,vl.value);
          }
        }
      }
    }
    return ret;
  }
  
  private String getEmailHeaders(Headers headers) {
    
    String ret = null;
    if (isNotNull(headers)) {
      StringBuilder sb = new StringBuilder();
      for (Header h: headers) {

        if (isNotNull(h) && !isEmpty(h.name) && !isEmpty(h.value)) {

          String s = String.format("%s: %s",h.name,h.value);
          sb.append((sb.length()==0)?"":Utils.getLineSeparator()).append(s);
        }
      }
      if (sb.length()!=0) {
        ret = sb.toString();
      }
    }
    return ret;
  }
  
  private ufsic.scheme.Pattern newPattern(Scheme scheme, Class cls, String patternId, boolean exists) {
    
    ufsic.scheme.Pattern ret = null;
    if (isNotNull(cls)) {
      
      ret = scheme.getPatterns().findFirst(ufsic.scheme.Patterns.PatternId,cls.getSimpleName());
      if (isNull(ret)) {
        try {
          if (!exists) {
            
            Constructor con = cls.getConstructor(Scheme.class,boolean.class);
            if (isNotNull(con)) {

              ret = (ufsic.scheme.Pattern)con.newInstance(scheme,false);
              if (isNotNull(ret)) {
                ret.load(isEmpty(patternId)?cls.getSimpleName():patternId);
              }
            }
          } else {
            
            Constructor con = cls.getConstructor(Scheme.class);
            if (isNotNull(con)) {

              ret = (ufsic.scheme.Pattern)con.newInstance(scheme);
            }
          }
        } catch (Exception e) {
          logException(e);
        }
      }
    }
    return ret;
  }
  
  private void setMessageAttachments(Provider provider, ufsic.scheme.Message message, Attachments attachments) {
    
    ufsic.scheme.Attachments atts = message.getAttachments(false);
    if (isNotNull(atts) && isNotNull(attachments)) {
      
      for (Attachment ai: attachments) {
        
        if (isNotNull(ai) && !isEmpty(ai.name) && !isEmpty(ai.data)) {
          
          ufsic.scheme.Attachment att = new ufsic.scheme.Attachment(atts);
          att.setAttachmentId(provider.getUniqueId());
          att.setName(ai.name);
          att.setExtension(ai.extension);
          att.setData(ai.getData());
          att.setContentId(ai.contentId);
          att.setContentType(ai.contentType);
          atts.add(att);
          
          if (message.getMessageId().isNotNull()) {
            att.setMessageId(message.getMessageId());
            att.insert();
          }
        }
      }
    }
  }

  private String getMessageId(Scheme scheme, Provider provider, Message message,
                             boolean parentNeeded) {
    
    String ret = null;

    if (parentNeeded) {
      
      ufsic.scheme.Message msg = new ufsic.scheme.Message(scheme.getMessages());

      msg.setMessageId(provider.getUniqueId());
      msg.setCreatorId(messageGate.getAccountId());
      msg.setSenderName(message.senderName);
      msg.setSenderContact(message.senderContact);
      msg.setSubject(message.subject);
      msg.setBody(message.getBody());
      msg.setBegin(Utils.toTimestamp(message.begin,timestampFormat));
      msg.setEnd(Utils.toTimestamp(message.end,timestampFormat));
      msg.setPriority(message.priority);
                    
      msg.setLocked(scheme.getStamp());

      if (msg.insert()) {

        ret = msg.getMessageId().asString();
        if (!isEmpty(ret)) {

          setMessageAttachments(provider,msg,message.attachments);
        }
      }
      
    } else {
      
      ret = provider.getUniqueId().asString();
    }
    return ret;
  }

  private class QueueThread extends Thread {

    private ScheduledFuture future = null;
    private Timestamp begin = null;
    private Timestamp end = null;
    private Timestamp locked = null;
    
    public boolean isCanceled() {
      return isNotNull(future) && future.isCancelled();
    }
    
    public boolean cancel() {
      
      boolean ret = false;
      
      if (isNotNull(future)) {
        try {
          future.cancel(true);
          future.get();
          ret = true;
        } catch (Exception e) {
          //
        }
      }
      return ret;
    }
    
    public void setFuture(ScheduledFuture future) {
      this.future = future;
    }
    
    public Timestamp getBegin() {
      return begin;
    } 
    
    public void setBegin(Timestamp begin) {
      this.begin = begin;
    }
    
    public Timestamp getEnd() {
      return end;
    } 
    
    public void setEnd(Timestamp end) {
      this.end = end;
    }
    
    public Timestamp getLocked() {
      return locked;
    }
    
    public void setLocked(Timestamp locked) {
      this.locked = locked;
    }
    
  }
  
  private static class QueueTasks extends ConcurrentHashMap<String,QueueThread> {
    
    public boolean cancel(String parentId) {
    
      boolean ret = false;
      QueueThread t = get(parentId);
      if (Utils.isNotNull(t)) {
        ret = t.cancel();
      }
      return ret;
    }
    
    public void add(String parentId, QueueThread thread) {
      
      cancel(parentId);
      put(parentId,thread);
    }
    
    public void setBegin(String parentId, Timestamp begin) {
      
      QueueThread t = get(parentId);
      if (Utils.isNotNull(t)) {
        t.setBegin(begin);
      }
    }
    
    public void setEnd(String parentId, Timestamp end) {
      
      QueueThread t = get(parentId);
      if (Utils.isNotNull(t)) {
        t.setEnd(end);
      }
    }
    
    public void setLocked(String parentId, Timestamp end) {
      
      QueueThread t = get(parentId);
      if (Utils.isNotNull(t)) {
        t.setLocked(end);
      }
    }
  }
          
  private static final QueueTasks queueTasks =  new QueueTasks();
  
  @WebMethod
  public QueueResult queue(@WebParam(name = "message") final Message message) {
    
    QueueResult ret = new QueueResult();
    
    Provider provider = messageGate.newProvider();
    try {
      
      final URI uri = getURI();
      Scheme schemeParent = new Scheme(messageGate,uri,provider);
      final Map<String,Recipient> recipients = getRecipients(schemeParent,message.recipients,message.getBody(),
                                                             message.keywords,message.priority,message.unique);
      if (!recipients.isEmpty()) {

        final boolean parentNeeded = recipients.size()>1;

        final String messageId = getMessageId(schemeParent,provider,message,parentNeeded);
        
        if (isNotNull(messageId)) {
          
          ret.messageId = messageId;
          ret.queueLength = recipients.size();

          QueueThread thread = new QueueThread() {

            @Override
            public void run() {

              Provider p = messageGate.newProvider();
              try {

                Scheme schemeChild = new Scheme(messageGate,uri,p);

                Class emailCls = null;
                Class phoneCls = null;

                String emailClsName = null;
                String phoneClsName = null;

                if (isNotNull(message.patterns)) { 
                  String pkg = "ufsic.scheme.patterns";

                  emailClsName = message.patterns.findFirst(Subscription.DeliveryType.EMAIL);
                  emailClsName = isNotNull(emailClsName)?emailClsName:message.patterns.getFirstEmpty();
                  emailCls = messageGate.getClass(pkg,emailClsName);

                  phoneClsName = message.patterns.findFirst(Subscription.DeliveryType.SMS);
                  phoneClsName = isNotNull(phoneClsName)?phoneClsName:message.patterns.getFirstEmpty();
                  phoneCls = messageGate.getClass(pkg,phoneClsName);
                }

                Timestamp begin = Utils.toTimestamp(message.begin,timestampFormat);
                Timestamp end = Utils.toTimestamp(message.end,timestampFormat);

                ufsic.scheme.Pattern emailPtn = newPattern(schemeChild,emailCls,emailClsName,isNotNull(emailCls));
                ufsic.scheme.Pattern phonePtn = newPattern(schemeChild,phoneCls,phoneClsName,isNotNull(phoneCls));

                ufsic.providers.Value cid = new ufsic.providers.Value(message.channelId);
                ufsic.providers.Value pid = new ufsic.providers.Value(parentNeeded?messageId:null);

                for (Entry<String,Recipient> entry: recipients.entrySet()) {

                  Recipient recipient = entry.getValue();

                  ufsic.scheme.Pattern ptn = null;

                  if (Utils.isEmail(recipient.contact)) {
                    ptn = isNull(emailPtn)?newPattern(schemeChild,EmailDefaultPattern.class,emailClsName,isNotNull(emailCls)):emailPtn;
                  }
                  if (Utils.isPhone(recipient.contact)) {
                    ptn = isNull(phonePtn)?newPattern(schemeChild,SmsDefaultPattern.class,phoneClsName,isNotNull(phoneCls)):phonePtn;
                  }

                  if (isNotNull(ptn)) {

                    ptn.setVar("subject",message.subject);
                    ptn.setVar("body",message.getBody());
                    ptn.setVar("name",recipient.name);
                    ptn.setVar("contact",recipient.contact);
                    ptn.setVars(getVars(message.vars,recipient.contact),false);

                    PatternMessage msg = new PatternMessage(ptn);
                    msg.setBodyExists(message.bodyExists());

                    if (!parentNeeded) {
                      setMessageAttachments(p,msg,message.attachments);
                      msg.setMessageId(new ufsic.providers.Value(messageId));
                    }
                    
                    String headers = null;
                    if (Utils.isEmail(recipient.contact)) {
                      headers = getEmailHeaders(message.headers);
                    }

                    Timestamp b = isNotNull(getBegin())?getBegin():begin;
                    Timestamp e = isNotNull(getEnd())?getEnd():end;
                    Integer priority = isNotNull(recipient.priority)?recipient.priority:message.priority;
                    
                    msg.setLocked(getLocked());
                            
                    msg.queue(null,message.senderName,message.senderContact,null,
                              recipient.name,recipient.contact,
                              message.subject,b,e,priority,
                              cid,ptn.getPatternId(),pid,headers);

                  }

                  if (isCanceled()) {
                    break;
                  }
                }
              } finally {
                p.disconnect();
                queueTasks.remove(messageId);
              }
            }
          };
          ScheduledFuture f = messageGate.getExecutor().schedule(thread,0,TimeUnit.SECONDS);
          if (isNotNull(f)) {
            thread.setFuture(f);
            queueTasks.add(messageId,thread);
          }
        }
      }
    } finally {
      provider.disconnect();
    }
    
    return ret;
  }
  
  @WebMethod
  public SendResult send(@WebParam(name = "message") Message message) {
    
    QueueResult ret = queue(message);
    if (ret.queueLength>0) {
      messageGate.checkOutgoing(message.channelId);
    }
    return new SendResult(ret);
  }
  
  private boolean cancelOrSuspend(ArrayList<String> messageIds, boolean cancel) {
    
    boolean ret = false;
    
    if (isNotNull(messageIds) && !messageIds.isEmpty()) {
      
      Provider provider = messageGate.newProvider();
      try {
        
        ufsic.providers.Value stamp = provider.getNow();
        
        GroupFilter filter = new GroupFilter();
        filter.And(Messages.Sent).IsNull();
        filter.And(Messages.Locked).IsNull();
        
        GroupFilter f1 = new GroupFilter();
        f1.Or(Messages.End).IsNull();
        f1.Or(new Filter().And(Messages.End).IsNotNull().And(Messages.End).Greater(stamp));
        filter.And(f1);
        
        Filter f2 = new Filter();
        for (String id: messageIds) {
          queueTasks.setLocked(id,stamp.asTimestamp());
          if (cancel) {
            queueTasks.cancel(id);
          }
          f2.Or(Messages.MessageId,id);
          f2.Or(Messages.ParentId,id);
        }
        filter.And(f2);
        
        Record r = new Record();
        r.add(Messages.Locked,stamp);
        
        ret = provider.update(Messages.TableName,r,filter);
        
      } finally {
        provider.disconnect();
      }
    }
    return ret;
  }
  
  @WebMethod
  public boolean cancel(@WebParam(name = "messageIds") ArrayList<String> messageIds) {
    
    return cancelOrSuspend(messageIds,true);
  }
  
  @WebMethod
  public boolean suspend(@WebParam(name = "messageIds") ArrayList<String> messageIds) {
    
    return cancelOrSuspend(messageIds,false);
  }
  
  @WebMethod
  public boolean resume(@WebParam(name = "messageIds") ArrayList<String> messageIds) {
    
    boolean ret = false;
    
    if (isNotNull(messageIds) && !messageIds.isEmpty()) {
      
      Provider provider = messageGate.newProvider();
      try {
        
        ufsic.providers.Value stamp = provider.getNow();
        
        GroupFilter filter = new GroupFilter();
        filter.And(Messages.Sent).IsNull();
        filter.And(Messages.Locked).IsNotNull();
        filter.And(Messages.Locked).Less(stamp);
        
        Filter f1 = new Filter();
        f1.Or(Messages.RecipientName).IsNotNull();
        f1.Or(Messages.RecipientContact).IsNotNull();
        filter.And(f1);
        
        Filter f2 = new Filter();
        for (String id: messageIds) {
          queueTasks.setLocked(id,null);
          f2.Or(Messages.MessageId,id);
          f2.Or(Messages.ParentId,id);
        }
        filter.And(f2);
        
        Record r = new Record();
        r.add(Messages.Locked,null);
        
        ret = provider.update(Messages.TableName,r,filter);
        
      } finally {
        provider.disconnect();
      }
    }
    return ret;
  }
  
  @WebMethod
  public boolean accelerate(@WebParam(name = "messageIds") ArrayList<String> messageIds) {
    
    boolean ret = false;
    
    if (isNotNull(messageIds) && !messageIds.isEmpty()) {
      
      Provider provider = messageGate.newProvider();
      try {
        
        ufsic.providers.Value stamp = provider.getNow();
        
        GroupFilter filter = new GroupFilter();
        filter.And(Messages.Sent).IsNull();
        filter.And(Messages.Locked).IsNull();
        
        GroupFilter f1 = new GroupFilter();
        f1.And(Messages.Begin).IsNotNull();
        f1.And(Messages.Begin).Greater(stamp);
        filter.And(f1);
        
        Filter f2 = new Filter();
        for (String id: messageIds) {
          queueTasks.setBegin(id,stamp.asTimestamp());
          f2.Or(Messages.MessageId,id);
          f2.Or(Messages.ParentId,id);
        }
        filter.And(f2);
        
        Record r = new Record();
        r.add(Messages.Begin,stamp);
        
        ret = provider.update(Messages.TableName,r,filter);
        if (ret) {
          ret = messageGate.checkOutgoing();
        }
        
      } finally {
        provider.disconnect();
      }
    }
    return ret;
  }
  
  @WebMethod
  public ArrayList<StatusResult> getStatus(@WebParam(name = "messageIds") ArrayList<String> messageIds) {
    
    ArrayList<StatusResult> ret = new ArrayList<>();
    if (isNotNull(messageIds) && !messageIds.isEmpty()) {
      
      Provider provider = messageGate.newProvider();
      try {
        
        Filter filter = new Filter();
        for (String id: messageIds) {
          filter.Or(Messages.MessageId,id);
        }
        
        Dataset<Record> ds = provider.select("V_MESSAGES_STATUSES",filter);
        if (isNotNull(ds)) {
          
          for (Record r: ds) {
            
            StatusResult result = new StatusResult();
            
            result.messageId = r.getValue("MESSAGE_ID").asString();
            result.allCount = r.getValue("ALL_COUNT").asInteger();
            result.sentCount = r.getValue("SENT_COUNT").asInteger();
            result.deliveredCount = r.getValue("DELIVERED_COUNT").asInteger();
            result.errorCount = r.getValue("ERROR_COUNT").asInteger();
            
            ret.add(result);
          }
        }
                
      } finally {
        provider.disconnect();
      }
    }
    return ret;
  }
  
}