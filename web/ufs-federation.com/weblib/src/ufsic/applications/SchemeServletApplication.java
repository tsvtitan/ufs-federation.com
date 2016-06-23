package ufsic.applications;


import java.net.URI;
import java.util.Collection;

import java.util.Iterator;
import java.util.Properties;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import ufsic.gates.IMessageGateRemote;
import ufsic.out.Echo;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.AccountPage;
import ufsic.scheme.AccountPages;
import ufsic.scheme.AccountRole;
import ufsic.scheme.AccountRoles;
import ufsic.scheme.Cache;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Session;
import ufsic.utils.Utils;

public class SchemeServletApplication extends ServletApplication implements ISchemeServletApplication {

  public SchemeServletApplication(Echo echo, HttpServletRequest request, HttpServletResponse response) {
    super(echo, request, response);
  }

  @Override
  public IMessageGateRemote getMessageGate() {
    
    IMessageGateRemote ret = null;
    
    getOptions().reload();
    
    String jndi = getOption("MessageGate.Jndi",null);
    if (isNotNull(jndi)) {
      
      Properties props = new Properties(); 
      props.setProperty("java.naming.factory.initial","com.sun.enterprise.naming.SerialInitContextFactory"); 
      props.setProperty("org.omg.CORBA.ORBInitialHost",getOption("MessageGate.Host","localhost"));
      props.setProperty("org.omg.CORBA.ORBInitialPort",getOption("MessageGate.Port","3700"));

      ret = (IMessageGateRemote)contextLookup(jndi,props);
    }
      
    return ret;
  }
  
  private URI getURI() {
    
    URI ret = null;
    try {
      HttpServletRequest request = getRequest();
      if (isNotNull(request)) {
        String s = request.getRequestURL().toString();
        if (s.length()>0) {
          String end = s.substring(s.length()-1);
          if (isNotNull(end)) {
            String req = request.getRequestURI();
            if (end.equals("/") && !req.equals("/")) {
              s = s.substring(0,s.length()-end.length());
            }
          }
        }
        ret = new URI(s);
        ret = ret.normalize();
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  private String getSessionId() {

    String ret = getRequest().getRequestedSessionId();
    if (isNull(ret)) {
      HttpSession session = getRequest().getSession();
      ret = session.getId();
    }
    return Utils.md5(ret).toUpperCase();
  }
  
  private String getOutHeaders() {
  
    StringBuilder sb = new StringBuilder();
    String sep = Utils.getLineSeparator();
    
    Collection cl = getResponse().getHeaderNames();
    Iterator it = cl.iterator();
    while (it.hasNext()) {
      String name = (String)it.next();
      String value = getResponse().getHeader(name);
      sb.append(name).append(": ").append(value).append(sep);
    }
    return sb.toString().trim();
  }
  
  private byte[] getOutData() {
    
    byte[] ret = new byte[] {};
    try {
      ret = getEcho().getBufStream().toByteArray();
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  public boolean redirect(String location) {

    boolean ret = false;
    try {
      getResponse().sendRedirect(location);
      ret = true;
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  private boolean redirectTo(Scheme scheme, Path path, AccountPages.ActionType actionType) {

    boolean ret = false;
    
    if (isNotNull(scheme)) {
      
      AccountPages pages = new AccountPages(scheme);

      Filter f1 = new Filter();

      Account account = scheme.getAccount();
      if (isNull(account)) {
        f1.Add(AccountPages.AccountId).IsNull();
      } else {
        AccountRoles roles = account.getRoles();
        f1.Or(AccountPages.AccountId).Equal(account.getAccountId());
        for (Record role: roles) {
          f1.Or(AccountPages.AccountId).Equal(((AccountRole)role).getRoleId());
        }
      }
      
      Filter f2 = new Filter();
      f2.Or(AccountPages.PathId).IsNull();

      if (isNotNull(path)) {
        f2.Or(AccountPages.PathId).Equal(path.getPathId());
      }
      
      GroupFilter gf = new GroupFilter(f1).And(f2).And(new Filter(AccountPages.Action,actionType.name()));

      Record r = getProvider().first(pages.getViewName(),gf,new Orders(AccountPages.PathId));
      if (isNotNull(r)) {
        
        AccountPage page = new AccountPage(pages,r);
        String location = scheme.buildURI(null,null,null,page.getPagePath().asString(),null,null,false);
        ret = redirect(location);
      }
    }
    return ret;
  }
  
  protected void sendNotFound(Scheme scheme, Path path) {
    try {
      if (!redirectTo(scheme,path,AccountPages.ActionType.NOT_FOUND)) {
        getResponse().sendError(HttpServletResponse.SC_NOT_FOUND);
      }
    } catch (Exception e) {
      logException(e);
    }
  }
  
  protected void sendForbidden(Scheme scheme, Path path) {
    try {
      if (!redirectTo(scheme,path,AccountPages.ActionType.FORBIDDEN)) {
        getResponse().sendError(HttpServletResponse.SC_FORBIDDEN);
      }
    } catch (Exception e) {
      logException(e);
    }
  }
  
  protected void sendInternalError() {
    try {
      getResponse().sendError(HttpServletResponse.SC_INTERNAL_SERVER_ERROR);
    } catch (Exception e) {
      logException(e);
    }
  }
  
  private void processEmpty(Path path, Session session) {

    Scheme scheme = path.getScheme();
    Cache cache = path.queryCache();
    if (isNotNull(scheme) && isNull(cache)) {

      String outHeaders = null;
      byte[] outData = null;
      Comm comm = null;
 
      if (isNotNull(session) && session.needComms()) {
        comm = session.beginComm();
      }
      try {

        if (path.process(comm)) {

          if ((isNotNull(session) && session.needComms()) || path.needCache()) {
            outHeaders = getOutHeaders();
            outData = getOutData();
          }

          if (path.needCache()) {
            path.makeCache(comm,outHeaders,outData);
          }
        } else {  
          if (getEcho().getBufStream().size()>0) {
            path.setErrorHeaders();
          } else {
            sendNotFound(path.getScheme(),path);
          }
        }

      } finally {
        if (isNotNull(session) && isNotNull(comm)) {
          session.endComm(comm,outHeaders,outData);
        }
      } 

    } else {

      Comm comm = null;
      if (isNotNull(session) && session.needComms()) {
        comm = session.beginComm();
      }
      try {
        if (!cache.trySend()) {
          if (getEcho().getBufStream().size()>0) {
            path.setErrorHeaders();
          } else {
            sendNotFound(path.getScheme(),path);
          }
        }
      } finally {
        if (isNotNull(session) && isNotNull(comm)) {
          session.endComm(comm,null,null);
        }
      } 
    } 
  }
  
  private void processSession(Scheme scheme, Path path) {
    
    Session session = scheme.trySession(getSessionId());
    if (isNotNull(session)) {
      if (session.valid()) {
        if (path.canShow()) {
          processEmpty(path,session);
        } else {
          sendForbidden(scheme,path);
        }
      } else {
        HttpSession s = getRequest().getSession();
        if (isNotNull(s)) {
          s.invalidate();
          getRequest().getSession(true);
        }
        path.redirectToSelf();
      }
    } else {
      sendInternalError();
    }
  }
  
  private Value getStamp() {
    
    Value ret = null;
    
    Provider provider = getProvider();
    
    if (isNotNull(provider)) {
      ret = provider.getNow();
    }
    
    return ret;
  }
  
  private void processScheme(Scheme scheme) {
    
    String s = scheme.getURI().getPath();
    
    Path path = scheme.queryPath(s);
    if (isNotNull(path)) {

      Value stamp = getStamp(); 
      if (isNotNull(stamp)) {
      
        scheme.setStamp(stamp);
        
        if (path.needSession()) {
          processSession(scheme,path);
        } else {
          if (path.canShow()) {
            processEmpty(path,null);
          } else {
            sendForbidden(scheme,path);
          }
        }
      } else {
        sendInternalError();
      }

    } else {
      sendNotFound(scheme,null);
    }
  }
  
  @Override
  public void run() {
    
    super.run();
    
    Provider provider = getProvider();
    
    if (isNotNull(provider)) {

      Scheme scheme = new Scheme(this,getURI());
      if (isNotNull(scheme)) {
        
        processScheme(scheme);
        
      } else {
        sendNotFound(null,null);
      }
    } else {
      sendInternalError();
    }
    
  }
  
  
}
