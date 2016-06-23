package ufsic.controllers;

import java.io.ByteArrayOutputStream;
import java.net.URI;
import java.util.Collection;
import java.util.Enumeration;
import java.util.Iterator;
import javax.servlet.ServletInputStream;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import ufsic.out.Echo;
import ufsic.out.Logger;
import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
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
import ufsic.gates.MessageGate;

public class DefaultController extends Controller {

  private long startStamp = System.nanoTime();
  private URI uri = null;
  
  public DefaultController(Logger logger, Echo echo, Provider provider, HttpServlet servlet,
                             HttpServletRequest request, HttpServletResponse response, Options options,
                             MessageGate messageGate) {
    super(logger, echo, provider, servlet, request, response, options, messageGate);
  }

  private void setUri() {
    try {
      uri = new URI(request.getRequestURL().toString());
      uri = uri.normalize();
    } catch (Exception e) {
      logException(e);
    }
  }
  
  @Override
  protected void beforeExecute() {
   
    startStamp = System.nanoTime();
    super.beforeExecute();
    provider.connect();
    setUri();
  }
  
  @Override
  protected void afterExecute() {

    provider.disconnect();
    super.afterExecute();
  }
  
  private double getDuration() {
    
    return (System.nanoTime()-startStamp)/(Math.pow(10,9));
  }
  
  private String getLangId() {
    
    String ret = "";
    if (isNotNull(uri)) {
      String host = uri.getHost();
      String parts[] = host.split("[\\.]");
      if (parts.length>2) {
        ret = parts[0].toUpperCase();
      }
    }
    return ret;
  }
  
  private String getSessionId() {

    String id = request.getRequestedSessionId();
    if (isNull(id)) {
      HttpSession session = request.getSession();
      id = session.getId();
    }
    return Utils.md5(id).toUpperCase();
  }
  
  private String getInHeaders() {
  
    StringBuilder sb = new StringBuilder();
    String sep = Utils.getLineSeparator();
    Enumeration en = request.getHeaderNames();
    while (en.hasMoreElements()) {
      String name = (String)en.nextElement();
      String value = request.getHeader(name);
      sb.append(name).append(": ").append(value).append(sep);
    }
    return sb.toString().trim();
  }
  
  private byte[] getInData() {
  
    byte[] ret = new byte[] {};
    try {
      ServletInputStream stream = request.getInputStream();
      if (isNotNull(stream)) {
        try (ByteArrayOutputStream b = new ByteArrayOutputStream()) {
          byte[] buf = new byte[1024];
          int count = 0;
          while ((count = stream.read(buf))>0) {
            b.write(buf,0,count);
          }
          ret = b.toByteArray();
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  } 
  
  private String getOutHeaders() {
  
    StringBuilder sb = new StringBuilder();
    String sep = Utils.getLineSeparator();
    
    Collection cl = response.getHeaderNames();
    Iterator it = cl.iterator();
    while (it.hasNext()) {
      String name = (String)it.next();
      String value = response.getHeader(name);
      sb.append(name).append(": ").append(value).append(sep);
    }
    return sb.toString().trim();
  }
  
  private byte[] getOutData() {
    
    byte[] ret = new byte[] {};
    try {
      ret = echo.getBufStream().toByteArray();
    } catch (Exception e) {
      
    }
    return ret;
  }
  
  public boolean redirect(String location) {

    boolean ret = false;
    try {
      response.sendRedirect(location);
      ret = true;
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  private boolean redirectTo(Scheme scheme, String action) {

    boolean ret = false;
    if (isNotNull(scheme)) {
      
      AccountPages pages = new AccountPages(scheme);

      Filter f = new Filter();

      Account account = scheme.getAccount();
      if (isNull(account)) {
        f.Add(AccountPages.AccountId).IsNull();
      } else {
        AccountRoles roles = account.getRoles();
        f.Or(AccountPages.AccountId).Equal(account.getAccountId());
        for (Record role: roles) {
          f.Or(AccountPages.AccountId).Equal(((AccountRole)role).getRoleId());
        }
      }
      GroupFilter gf = new GroupFilter(f).And(new Filter(AccountPages.Action,action));

      Record r = provider.first(pages.getViewName(),gf);
      if (isNotNull(r)) {
        
        AccountPage page = new AccountPage(pages,r);
        //String location = scheme.buildURI(null,null,null,String.format("%s/%s",request.getContextPath(),page.getPath().asString()),null,null,false);
        String location = scheme.buildURI(null,null,null,page.getPath().asString(),null,null,false);
        ret = redirect(location);
      }
    }
    return ret;
  }
  
  private void sendNotFound(Scheme scheme) {
    try {
      if (!redirectTo(scheme,"NOT_FOUND")) {
        response.sendError(HttpServletResponse.SC_NOT_FOUND);
      }
    } catch (Exception e) {
      logException(e);
    }
  }
  
  private void sendForbidden(Scheme scheme) {
    try {
      if (!redirectTo(scheme,"FORBIDDEN")) {
        response.sendError(HttpServletResponse.SC_FORBIDDEN);
      }
    } catch (Exception e) {
      logException(e);
    }
  }
  
  private void sendInternalError() {
    try {
      response.sendError(HttpServletResponse.SC_INTERNAL_SERVER_ERROR);
    } catch (Exception e) {
      logException(e);
    }
  }

  private void processEmpty(Value stamp, Path path, Session session) {

    Cache cache = path.queryCache(stamp);
    if (isNull(cache)) {

      String outHeaders = null;
      byte[] outData = null;
      Comm comm = null;

      if (isNotNull(session) && session.needComms()) {
        comm = session.beginComm(stamp,getInHeaders(),getInData());
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
          if (echo.getBufStream().size()>0) {
            path.setErrorHeaders();
          } else {
            sendNotFound(path.getScheme());
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
        comm = session.beginComm(stamp,getInHeaders(),getInData());
      }
      try {
        if (!cache.trySend()) {
          if (echo.getBufStream().size()>0) {
            path.setErrorHeaders();
          } else {
            sendNotFound(path.getScheme());
          }
        }
      } finally {
        if (isNotNull(session) && isNotNull(comm)) {
          session.endComm(comm,null,null);
        }
      } 
    } 
  }
  
  private void processSession(Value stamp, Scheme scheme, Path path) {
    
    Session session = scheme.trySession(getSessionId());
    if (isNotNull(session)) {
      if (session.valid(stamp)) {
        if (path.canShow()) {
          processEmpty(stamp,path,session);
        } else {
          sendForbidden(scheme);
        }
      } else {
        HttpSession s = request.getSession();
        if (isNotNull(s)) {
          s.invalidate();
          request.getSession(true);
        }
        path.redirectToSelf();
      }
    } else {
      sendInternalError();
    }
  }
  
  @Override
  protected void onExecute() {

    if (provider.isConnected()) {

      Value stamp = provider.getNow(); 
      
      Scheme scheme = new Scheme(this,getLangId(),uri);
      if (isNotNull(scheme)) {
        
        Path path = scheme.queryPath(request.getPathInfo());
        if (isNotNull(path)) {
          
          if (path.needSession()) {
            processSession(stamp,scheme,path);
          } else {
            if (path.canShow()) {
              processEmpty(stamp,path,null);
            } else {
              sendForbidden(scheme);
            }
          }
          
        } else {
          sendNotFound(scheme);
        }
      } else {
        sendNotFound(null);
      }
    } else {
      sendInternalError();
    }
  }

}
