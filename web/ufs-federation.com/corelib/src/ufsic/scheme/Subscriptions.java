package ufsic.scheme;

public class Subscriptions extends SchemeTable<Subscription> {

  public final static String TableName = "SUBSCRIPTIONS";
  
  public final static String SubscriptionId = "SUBSCRIPTION_ID";
  public final static String AccountId = "ACCOUNT_ID";
  public final static String LangId = "LANG_ID";
  public final static String DeliveryType = "DELIVERY_TYPE";
  public final static String Created = "CREATED";
  public final static String Started = "STARTED";
  public final static String Finished = "FINISHED";
  public final static String Keyword = "KEYWORD";
  
  public final static String Login = "LOGIN";
  public final static String Email = "EMAIL";
  public final static String Phone = "PHONE";
  public final static String Name = "NAME";
  
  public Subscriptions(Scheme scheme, String name) {
    
    super(scheme, name);
  }

  public Subscriptions(Scheme scheme) {
    
    super(scheme,TableName);
  }
  
  @Override
  public Class getRecordClass() {

    return Subscription.class;
  }
}
