package ufsic.scheme;

import java.util.ArrayList;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.utils.Utils;

public class Account extends SchemeRecord {

  private AccountRoles roles = null;
  private AccountMenus menus = null;
  private AccountPages pages = null;
  private Agreements agreements = null;
  private AccountFiles files = null;
  
  public Account(SchemeTable table, Record record) {
    
    super(table, record);
    
    this.roles = new AccountRoles(getScheme());
    refreshRoles();
    
    this.menus = new AccountMenus(getScheme());
    this.pages = new AccountPages(getScheme());
    this.agreements = new Agreements(getScheme());
    this.files = new AccountFiles(getScheme());
  }

  public Account(SchemeTable table) {
    
    this(table,null);
  }
  
  private void refreshRoles() {
    
    if (isNotNull(roles)) {
      roles.open(null,new Filter(AccountRoles.AccountId,getAccountId()),null);
    }
  }
  
  public Value getAccountId() {

    return getValue(Accounts.AccountId);
  }

  public void setAccountId(Value accountId) {
    
    if (!setValue(Accounts.AccountId,accountId)) {
      add(Accounts.AccountId,accountId);
    }
  }
  
  public Value getSurname() {

    return getValue(Accounts.Surname);
  }

  public Value getName() {

    return getValue(Accounts.Name);
  }

  public Value getPatronymic() {

    return getValue(Accounts.Patronymic);
  }
  
  public Value getLogin() {

    return getValue(Accounts.Login);
  }

  public Value getPass() {

    return getValue(Accounts.Pass);
  }

  protected void setPass(Value pass) {
    
    if (!setValue(Accounts.Pass,pass)) {
      add(Accounts.Pass,pass);
    }
  }
  
  public Value getPhone() {

    return getValue(Accounts.Phone);
  }

  public Value getEmail() {

    return getValue(Accounts.Email);
  }
  
  public Value getLocked() {

    return getValue(Accounts.Locked);
  }
  
  public AccountRoles getRoles() {
    
    return roles;
  }
  
  public AccountMenus getMenus(boolean refresh) {

    if (refresh) {

      Filter f = new Filter();
      f.Add(AccountMenus.AccountId).Equal(getAccountId());
      for (Record r: roles) {
        f.Or(AccountMenus.AccountId).Equal(((AccountRole)r).getRoleId());
      }
      
      GroupFilter gf = new GroupFilter(f);
      gf.And(new Filter(AccountMenus.LangId,getScheme().getLang().getLangId()).
                     Or(AccountMenus.LangId).IsNull());

      menus.open(null,gf,new Orders(AccountMenus.Priority));
    }
    return menus;
  }
  
  public AccountMenus getMenus() {
    
    return getMenus(menus.isEmpty());
  }
  
  public AccountPages getPages(boolean refresh) {
    
    if (refresh) {
      
      Filter f = new Filter();
      f.Add(AccountPages.AccountId).Equal(getAccountId());
      for (Record r: roles) {
        f.Or(AccountPages.AccountId).Equal(((AccountRole)r).getRoleId());
      }
      f.Or(AccountPages.AccountId).IsNull();
      
      pages.open(null,f,null);
    }
    return pages;
  }
  
  public AccountPages getPages() {
    
    return getPages(pages.isEmpty());
  }
  
  private AccountPage getAccountPage(String action) {
    
    AccountPage ret = null;
    for (Record r: getPages()) {
      AccountPage page = (AccountPage)r;
      if (page.getAction().same(action)) {
        ret = page;
        break;
      }
    }
    return ret;
  }
  
  public AccountPage getLoginSuccessPage() {
    
    return getAccountPage("LOGIN_SUCCESS");
  }

  public AccountPage getLogoutSuccessPage() {
    
    return getAccountPage("LOGOUT_SUCCESS");
  }

  public AccountPage getAuthFailedPage() {
    
    return getAccountPage("AUTH_FAILED");
  }

  public AccountPage getConfirmationPage() {
    
    return getAccountPage("CONFIRMATION");
  }
  
  public String getLoginName() {
  
    StringBuilder sb = new StringBuilder();
    
    Value v = getName();
    if (v.isNotNull()) {
      sb.append(v.asString()).append(" ");
    }
    v = getSurname();
    if (v.isNotNull()) {
      sb.append(v.asString()).append(" ");
    }
    
    String s = sb.toString().trim();
    if (s.equals("")) {
      
      v = getEmail();
      if (v.isNotNull()) {
        sb.append(v.asString());
      } else {
        sb.append(getLogin().asString());
      }
    }
    
    return sb.toString().trim();
  }
 
  public Agreements getAgreements(boolean refresh) {
    
    if (refresh) {
      
      agreements.open(new Filter(Agreements.AccountId,getAccountId()).And(Agreements.Locked).IsNull(),
                      new Orders(Agreements.Priority));
    }
    return agreements;
  }
  
  public Agreements getAgreements() {
    
    return getAgreements(agreements.isEmpty());
  }
  
  public AccountFiles getAccountFiles() {
    
    return files;
  }
  
  public ArrayList<AccountFile> getAccountFiles(String pageID) {
    
    return files.getAccountFiles(getAccountId().asString(), pageID);
  }
  
  public boolean changePassword(String password) {
    
    boolean ret = false;
    Value accountId = getAccountId();
    if (accountId.isNotNull()) {

      Value pass = new Value(null);
      if (Utils.isNotNull(password)) {
        pass.setObject(Utils.md5(password).toUpperCase());
      }
      setPass(pass);
      ret = update(new Filter(Accounts.AccountId,accountId));
    }
    return ret;
  }
}