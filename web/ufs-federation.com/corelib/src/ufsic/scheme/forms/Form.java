package ufsic.scheme.forms;

import java.util.ArrayList;
import java.util.Properties;
import ufsic.contexts.IVarContext;
import ufsic.providers.Filter;
import ufsic.providers.Params;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.PageForm;
import ufsic.scheme.PageForms;
import ufsic.scheme.PageTableForm;
import ufsic.scheme.PageTableForms;
import ufsic.scheme.Scheme;
import ufsic.scheme.SchemeObject;
import ufsic.utils.Utils;

public class Form extends SchemeObject {

  public class Lists extends ArrayList<List> {

    public Lists() {
      super();
    }
    
    public Lists(Value lists) {

      this();

      if (lists.isNotNull()) {

        String[] rqs = lists.asString().split(Utils.getLineSeparator());
        if (rqs.length>0) {

          for (String s: rqs) {
            this.add(new List(scheme,s.trim()));
          }
        }
      }
    }
    
    public List findFirst(String name) {
      
      List ret = null;
      for (List r: this) {
        
        String n = r.getName();
        if (isNotNull(n) && n.equals(name)) {
          ret = r;
          break;
        }
      }
      return ret;
    }
    
  }
  
  public class Defaults extends ArrayList<Default> {

    public Defaults() {
      super();
    }
    
    public Defaults(Value defaults) {

      this();

      if (defaults.isNotNull()) {

        String[] rqs = defaults.asString().split(Utils.getLineSeparator());
        if (rqs.length>0) {

          for (String s: rqs) {
            this.add(new Default(scheme,s.trim()));
          }
        }
      }
    }
    
    public Default findFirst(String name) {
      
      Default ret = null;
      for (Default r: this) {
        
        String n = r.getName();
        if (isNotNull(n) && n.equals(name)) {
          ret = r;
          break;
        }
      }
      return ret;
    }
    
  }
  
  public class Requirements extends ArrayList<Requirement> {

    public Requirements(Value requirements) {

      super();

      if (requirements.isNotNull()) {

        String[] rqs = requirements.asString().split(Utils.getLineSeparator());
        if (rqs.length>0) {

          for (String s: rqs) {
            this.add(new Requirement(getSelf(),s.trim()));
          }
        }
      }
    }
    
    public Requirement findFirstByMethod(String name, String method) {
      
      Requirement ret = null;
      for (Requirement r: this) {
        
        String n = r.getName();
        Method m = r.getMethod();
        
        if (isNotNull(n) && n.equals(name) && isNotNull(m)) {
          
          String mn = m.getName();
          if (isNotNull(mn) && mn.equals(method)) {
            
            ret = r;
            break;
          }
        }
      }
      return ret;
    }
  }

  public class Transforms extends ArrayList<Transform> {

    public Transforms() {
      super();
    }
    
    public Transforms(Value transforms) {

      this();

      if (transforms.isNotNull()) {

        String[] rqs = transforms.asString().split(Utils.getLineSeparator());
        if (rqs.length>0) {

          for (String s: rqs) {
            this.add(new Transform(scheme,s.trim()));
          }
        }
      }
    }
    
    public Transforms getList(String name) {
      
      Transforms ret = new Transforms();
      for (Transform t: this) {
        
        String n = t.getName();
        if (isNotNull(n) && n.equals(name)) {

          ret.add(t);
        }
      }
      return ret;
    }
  }
  
  private class Element {

    private String caption = null;
    private String name = null;
    private String template = null;

    public Element(String ident) {
      
      String[] tmp = ident.split(":");
      if (tmp.length>0) {
        
        String s = null;
        if (tmp.length>1) {
          this.caption = tmp[0].trim();
          s = tmp[1];  
        } else {
          this.caption = ident;
        }
        
        if (isNotNull(s)) {
          
          String[] nt = s.split("=");
          if (nt.length>1) {
            
            this.name = nt[0].trim();
            this.template = nt[1].trim();
            
          } else {
            this.name = s;
          }
        }
      }
    }
    
    public String getCaption() {
      return scheme.getDictionary().replace(this.caption);
    }
    
    public String getName() {
      return name;
    }
    
    public String getTemplate() {
      return template;
    }
    
    public String getId() {
      
      String id = String.format("%s_%s",getPageFormId(),name);
      return Utils.md5(id).toUpperCase();
    }
  }
  
  public class Field extends Element {

    private String def = null;
    private String maxLength = null;
    private String placeHolder = null;
    private boolean needListEmpty = false;
    private List list = null;
    private String style = null;
    private boolean error = false;
    private Value value = new Value(null);

    public Field(String ident) {
      super(ident);
    }
    
    private void setDefault(String def) {
      this.def = def;
    }
    
    public String getDefault() {
      return scheme.getDictionary().replace(this.def);
    }
    
    public boolean getChecked() {
      
      boolean ret = false;
      if (value.isNotNull()) {
        String s = value.asString().toLowerCase();
        ret = s.equals("on") || s.equals("yes") || s.equals("true") || s.equals("1");
      }
      return ret;
    }

    private void setMaxLength(String maxLength) {
      this.maxLength = maxLength;
    }
    
    public String getMaxLength() {
      return maxLength;
    }

    private void setPlaceHolder(String placeHolder) {
      this.placeHolder = placeHolder;
    }
    
    public String getPlaceHolder() {
      return scheme.getDictionary().replace(this.placeHolder);
    }

    private void setList(List list, Requirement requirement) {
      
      needListEmpty = isNull(requirement);
      this.list = list;
    }
    
    public Items getItems() {
      
      Items ret = null;
      if (isNotNull(list)) {
        ret = list.getItems(needListEmpty);
      }
      return ret;
    }

    private void setStyle(String style) {
      this.style = style;
    }
    
    public String getStyle() {
      return scheme.getDictionary().replace(this.style);
    }
    
    public void setError(boolean error) {
      this.error = error;
    }
    
    public boolean getError() {
      return error;
    }
    
    public void setValue(Object value) {

      if (isNotNull(list)) {
        list.setDefault(value);
      }
      this.value.setObject(value);
    }
    
    public String getValue() {
      return value.asString();
    }
    

  }
  
  public class Fields extends ArrayList<Field> {

    public Fields() {
      super();
    }
    
    public Field findField(String name) {
      
      Field ret = null;
      
      for (Field field: this) {
        
        String n = field.getName();
        if (n.equals(name)) {
          ret = field;
          break;
        }
      }
      return ret;
    }
    
    void setDefaults() {
    
      for (Field field: this) {
        field.setValue(field.getDefault());
      }
    }
  }
  
  public class Button extends Element {

    public Button(String caption) {
      super(caption);
    }
    
  }
  
  public class Buttons extends ArrayList<Button> {

    public Buttons() {
      super();
    }
  
    private Button findButton(String name) {
      
      Button ret = null;
      
      for (Button button: this) {
        
        String n = button.getName();
        if (n.equals(name)) {
          ret = button;
          break;
        }
      }
      return ret;
    }

  }
  
  public class Message {
  
    private String text = null;
    private boolean isError = false;

    public Message(String text, boolean isError) {
      
      this.text = text;
      this.isError = isError;
    }
    
    public String getText() {
      return scheme.getDictionary().replace(this.text);
    }
  }
  
  public class Messages extends ArrayList<Message> {

    public Messages() {
      super();
    }
  }
  
  final private Fields fields = new Fields();
  final private Buttons buttons = new Buttons();
  final private Messages messages = new  Messages();
  private boolean success = false;
  private String redirect = null;
  private PageForm form = null;
  private Scheme scheme = null;
  private Provider provider = null;
  private PageForms parents = null;
  private Params params = null;

  public Form(PageForm form) {

    super(form.getScheme());
    this.form = form;
    this.scheme = form.getScheme();
    this.provider = form.getProvider();
    this.parents = new PageForms(form.getScheme());
    
    parents.open(new Filter(PageForms.LastPageFormId,form.getPageFormId()).
                        And(PageForms.PageFormId).NotEqual(form.getPageFormId()));

  }

  private Value getFormValue(String name) {
    
    Value ret = form.getValue(name);
    if (ret.isNull()) {
      for (Record r: parents) {
        Value v = r.getValue(name);
        if (v.isNotNull()) {
          ret = v;
          break;
        }
      }
    }
    return ret;
  }

  public String getFieldId(String name) {
    
    String ret = null;
    Field field = findField(name);
    if (isNotNull(field)) {
      ret = field.getId();
    }
    return ret;
  }
  
  private Form getSelf() {
    return this;
  }
  
  protected Messages getMessages() {
    return messages;
  }

  protected PageForm getForm() {
    return form;
  }

  public String getPageFormId() {
    return form.getPageFormId().asString();
  }

  public String getName() {
    return scheme.getDictionary().replace(form.getName().asString());
  }

  public String getDescription() {
    return scheme.getDictionary().replace(form.getDescription().asString());
  }

  public Fields getFields() {
    return fields;
  }

  public Buttons getButtons() {
    return buttons;
  }

  public String getTemplate() {
    return getFormValue(PageForms.TemplateId).asString();
  }

  public boolean getAsync() {
    return form.getAsync().asBoolean(); 
  }

  public boolean getSuccess() {
    return success;
  }

  public void setSuccess(boolean success) {
    this.success = success;
    scheme.getPath().setFormSuccess(getPageFormId(),success);
  }

  public String getRedirect() {
    return redirect;
  }

  public void setRedirect(String redirect) {
    
    boolean async = getAsync();
    if (async) {
      this.redirect = redirect;
    } else {
      scheme.getPath().redirect(redirect);
    }
  }
  
  public String getPathId() {
    String def = scheme.getPath().getPathId().asString();
    return scheme.getPath().getParameterValue("pathId",def);
  }
  
  public ArrayList<String> getErrors() {

    ArrayList<String> ret = new ArrayList<>();
    for (Message m: messages) {
      if (m.isError) {
        ret.add(m.getText());
      }
    }
    return ret;
  }

  public Field findField(String name) {
    return fields.findField(name);
  }

  public Button findButton(String name) {
    return buttons.findButton(name);
  }

  public ArrayList<String> getTableIds() {

    ArrayList<String> ret = new ArrayList<>();
    PageTableForms tables = form.getPageTableForms();
    for (Record r: tables) {
      PageTableForm table = (PageTableForm)r; 
      ret.add(table.getPageTableId().asString());
    }
    return ret;
  }
  
  protected Provider getProvider() {
    return provider;
  }
  
  protected Params getProviderParams() {
    return params;
  }

  protected boolean fieldCheck(Field field, Requirement requirement) {

    boolean ret = true;
    if (isNotNull(field)) {
      ret = requirement.check(field.getValue());
    }
    return ret;
  }

  protected Object fieldConvert(Field field, Transform transform, Object def) {

    Object ret = def;
    if (isNotNull(field)) {
      ret = transform.convert(field.getValue());
    }
    return ret;
  }
  
  protected void fieldDefault(Field field, Default def) {

    if (isNotNull(field)) {
      Object obj = def.getValue();
      if (isNotNull(obj)) {
        field.setDefault(obj.toString());
      }
    }
  }
  
  public boolean process(IVarContext context) {

    boolean ret = false;
    
    if (isNotNull(form)) {

      Properties maxLengths = getFormValue(PageForms.MaxLengths).asProperties();
      Properties placeHolders = getFormValue(PageForms.PlaceHolders).asProperties();
      Properties styles = getFormValue(PageForms.Styles).asProperties();

      Defaults defaults = new Defaults(getFormValue(PageForms.Defaults));
      Lists lists = new Lists(getFormValue(PageForms.Lists));       
      Requirements requirements = new Requirements(getFormValue(PageForms.Requirements));
      Transforms transforms = new Transforms(getFormValue(PageForms.Transforms));

      fields.clear();
      Value fs = getFormValue(PageForms.Fields);

      if (fs.isNotNull()) {

        String[] fls = fs.asString().split(Utils.getLineSeparator());
        if (fls.length>0) {

          for (String s: fls) {

            String s1 = s.trim();
            if (!s1.startsWith("//")) {

              Field field = new Field(s1);

              String name = field.getName();
              Default def = defaults.findFirst(name);
              if (isNotNull(def)) {
                fieldDefault(field,def);
              }
              field.setMaxLength(maxLengths.getProperty(name));
              field.setPlaceHolder(placeHolders.getProperty(name));
              field.setList(lists.findFirst(name),requirements.findFirstByMethod(name,"isNotEmpty"));
              field.setStyle(styles.getProperty(name));

              fields.add(field);
            }
          }
        }
      }

      buttons.clear();
      Value bs = getFormValue(PageForms.Buttons);

      if (bs.isNotNull()) {

        String[] bts = bs.asString().split(Utils.getLineSeparator());
        if (bts.length>0) {

          for (String s: bts) {

            Button button = new Button(s.trim());

            buttons.add(button);
          }
        }
      }

      boolean posted = scheme.getPath().parameterExists("pageFormId"); 
      if (!posted) {

        fields.setDefaults();
        ret = true;

      } else {  

        for (Field field: fields) {
          Object v = scheme.getPath().getParameterValue(field.getName());
          field.setValue(v);
        }

        messages.clear();
        success = true;

        for (Requirement r: requirements) {

          String name = r.getName();
          Field field = fields.findField(name);
          if (isNotNull(field)) {

            if (!fieldCheck(field,r)) {
              messages.add(new Message(r.getMessage(),true));
              field.setError(true);
              success = false;
            }
          }
        }

        params = null;
        boolean flagSuccess = success;
        
        if (flagSuccess) {

          Value procName = getFormValue(PageForms.ProcName);
          if (procName.isNotNull()) {

            Params ps = new Params();
            for (Field field: fields) {

              Object obj;
              String v = field.getValue();
              Transforms trans = transforms.getList(field.getName());
              if (trans.size()>0) {
                obj = v;
                for (Transform t: trans) {
                  obj = fieldConvert(field,t,obj);
                }
              } else {
                obj = v;
              }
              ps.AddIn(field.getName(),obj);
            }
            
            if (!ps.isEmpty()) {
              ps.AddIn("SESSION_ID",scheme.getSessionId());
              ps.AddIn("PAGE_FORM_ID",form.getPageFormId());
            }

            boolean r = provider.execute(procName.asString(),ps);
            if (r) {

              params = ps;

            } else {
              Exception e = provider.getLastException();
              if (isNotNull(e)) {
                messages.add(new Message(e.getMessage(),true));
                flagSuccess = false;
              }
            }
          }
        }
        setSuccess(flagSuccess);
        ret = true;
      }
    }
    return ret;
  }
}
