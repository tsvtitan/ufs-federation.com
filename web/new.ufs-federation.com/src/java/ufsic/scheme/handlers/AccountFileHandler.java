/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.scheme.handlers;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.nio.charset.Charset;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.mail.internet.MimeUtility;
import javax.servlet.ServletOutputStream;
import org.apache.commons.codec.binary.Base64;
import ufsic.providers.Filter;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.scheme.Account;
import ufsic.scheme.AccountFile;
import ufsic.scheme.AccountFiles;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.utils.Utils;

/**
 *
 * @author zrv
 */
public class AccountFileHandler extends Handler {
  
  private final static Charset charset = Utils.getCharset(); 
  
  public AccountFileHandler(Path path) {
    super(path);
  }
  
  public String getContentDispositionFileName(String fileName)
  {
    try {
      if(getPath().getRequest().getHeader ( "user-agent" ).contains ( "MSIE" ))
      {
        fileName = URLEncoder.encode ( fileName, "utf-8");
      }else
        fileName = MimeUtility.encodeWord ( fileName, charset.name(), "Q" );
    } catch (UnsupportedEncodingException ex) {
        Logger.getLogger(AccountFileHandler.class.getName()).log(Level.SEVERE, null, ex);
    }
    return fileName;
  }
  
  public void setHeaders(String fileName, String fileExtension, Long size) {
    
    Path path = getPath();
    String mimeType, contentDisposition;
    
    if(fileExtension.equals("pdf"))
    {
      mimeType = fileExtension;
    }else
      if(fileExtension.equals("doc") || fileExtension.equals("docх"))
      {
        mimeType = "msword";
      }else
        if(fileExtension.equals("xls") || fileExtension.equals("xlst"))
        {
          mimeType = "excel";
        }else
        {
          mimeType = "octet-stream";
        }
    
    path.setContentHeaders("application/" + mimeType, size);

    //byte[] bytes = Base64.encodeBase64(fileName.getBytes(charset));
    //String newFileName = String.format("=?%s?B?%s?=", charset.name(), new String(bytes));
    //path.setHeader("Content-Disposition", String.format("attachment; filename=\"%s\"", newFileName));

    //TODO: временный вариант, в IE вместо пробелов будут +
    fileName = getContentDispositionFileName(fileName);
    
    path.setHeader("Content-Disposition", String.format("attachment; filename=\"%s\"", fileName));
    path.setHeader("Content-Transfer-Encoding", "binary");
  } 

  @Override
  public boolean process(Comm comm) {
    
    //Path path;
    Object o;
    Record r;
    String ext;
    AccountFile f;
    
    boolean ret =  super.process(comm);
    
    Scheme scheme = getScheme();
    Account account = scheme.getAccount();
    
    if (isNotNull(scheme) && isNotNull(account)) {
      o = scheme.getPath().getParameterValue("file");
      r = getProvider().first(AccountFiles.TableName, null, new Filter(AccountFiles.AccountFileId, o));
      
      if (isNotNull(r)) {
        
        f = new AccountFile(account.getAccountFiles(), r);
        
        ext = f.getExtension().isNull() ? "":"." + f.getExtension().asString();
        setHeaders(String.format("%s%s", f.getName().asString(), ext),
          f.getExtension().asString(), new Long(f.getData().length()));
        
        ByteArrayOutputStream out = getEcho().getBufStream();
        try {
          out.write(f.getData().asBytes());
          ret = true;
        } catch (IOException ex) {
          Logger.getLogger(AccountFileHandler.class.getName()).log(Level.SEVERE, null, ex);
          ret = false;
        }
      }
    }
    
    return ret;
  }
  
  
}
