/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.scheme.handlers;

import java.io.BufferedInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletOutputStream;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.commons.codec.binary.Base64;
import ufsic.providers.Params;
import ufsic.providers.Value;
import ufsic.scheme.Comm;
import ufsic.scheme.Handler;
import ufsic.scheme.Path;
import ufsic.utils.Utils;

/**
 *
 * @author zrv
 */
public class UploadHandler extends Handler {

  public UploadHandler(Path path) {
    super(path);
  }

  public static boolean needSession() {
    return false;
  }
  
  @Override
  public boolean process(Comm comm) {
    
    HttpServletRequest request;
    HttpServletResponse response;
    String fileLocation, fileName, fileExtension, fileCreated, resultStatusText, res;
    byte[] buffer, locationData;
    int len, pos, count, offset = 0, contentLen, resultStatusCode = -1, fileDataSize;
    Boolean uploadExecResult = false;
    Params params;
    
    request = getPath().getRequest();
    contentLen = request.getContentLength();
    
    resultStatusText = "";
    
    if(contentLen > 0)
    {
      try {
        
        
        buffer = new byte[contentLen];        
                
        try {

          try (InputStream stream = new BufferedInputStream(request.getInputStream()))
          {
            int av = stream.available();
            
            av = stream.available();
            while (offset < contentLen) {
              count = stream.read(buffer, offset, contentLen - offset);
              if (count == -1) {
                resultStatusCode = 5;
                resultStatusText = "Ошибка при загрузке файла: нет данных";
                break;
              }
              offset += count;
            }
          }
        } catch (IOException ex) {
          Logger.getLogger(UploadHandler.class.getName()).log(Level.SEVERE, null, ex);
        }
        
        if(resultStatusCode == -1)
        {
          locationData = Base64.decodeBase64(request.getHeader("FILE_LOCATION"));
          fileLocation = new String(locationData, "UTF8");
          fileName = new String(Base64.decodeBase64(request.getHeader("FILE_NAME")), "UTF8");
          fileExtension = request.getHeader("FILE_EXTENSION");
          fileDataSize = contentLen / 1024; // Размер в Кб
          fileCreated = request.getHeader("FILE_CREATED");

          try {
            params = new Params();
            params.AddIn( "FILE_LOCATION", new Value(fileLocation) ); //, Direction.IN, NVARCHAR ) );
            //params.Add( new Param("FILE_LOCATION", new Value(fileLocation), Direction.IN, NVARCHAR ) );
            params.AddIn( "FILE_NAME", new Value(fileName) ); //Direction.IN, NVARCHAR ) );
            params.AddIn( "FILE_EXTENSION", new Value(fileExtension) ); //, Direction.IN, NVARCHAR ) );
            params.AddIn( "FILE_DATA", new Value(buffer) ); //, Direction.IN, BLOB ) );
            params.AddIn( "FILE_DATA_SIZE", new Value(fileDataSize) ); //, Direction.IN, BLOB ) );            
            params.AddIn( "FILE_CREATED", new Value(fileCreated) ); //, Direction.IN, BLOB ) );
            params.AddOut( "STATUS_CODE" );
            params.AddOut( "STATUS_TEXT" );
            
            uploadExecResult = getProvider().execute("UPLOAD_ACCOUNT_FILE", params);
            
            if ( !uploadExecResult )
            {
              resultStatusText = getProvider().getLastException().getMessage();
            } else
            {
              resultStatusCode = params.find("STATUS_CODE").getValue().asInteger();
              resultStatusText = params.find("STATUS_TEXT").getValue().asString();
            }

            // 2
            //Value rs = new Value(resultStatus);
            //params.AddOut(new Param("STATUS",rs));
            //if (provider.execute("UPLOAD_ACCOUNT_FILE", params)) {
            //  resultStatus = rs.asString();
            //}

          } catch (Exception ex) {
            Logger.getLogger(UploadHandler.class.getName()).log(Level.SEVERE, null, ex);
          }
        }
      } catch (UnsupportedEncodingException ex) {
        Logger.getLogger(UploadHandler.class.getName()).log(Level.SEVERE, null, ex);
      }      
    }
    else
    {
      //TODO: обработка ситуации, когда нет контента      
      resultStatusCode = 99;
      resultStatusText = "Передан пустой файл. Действие не будет выполнено";
    }
    
    response = getPath().getResponse();
            
    response.setHeader("STATUS_CODE", String.valueOf(resultStatusCode));
    //response.setHeader("STATUS_CODE", Base64.encodeBase64String((String.valueOf(resultStatusCode)).getBytes()));

    response.setHeader("STATUS_TEXT", Base64.encodeBase64String(resultStatusText.getBytes(Utils.getCharset()))); // by tsv
    //response.setHeader("STATUS_TEXT", Base64.encodeBase64String(resultStatusText.getBytes()));
    //response.setHeader("STATUS", resultStatusText);

    ServletOutputStream responseBody; 
    try {
      response.setCharacterEncoding(Utils.getCharset().name());
      response.setContentType("text/plain");
      responseBody = response.getOutputStream();
      responseBody.print("Hello");
      responseBody.close();
    } catch (IOException ex) {
      Logger.getLogger(UploadHandler.class.getName()).log(Level.SEVERE, null, ex);
    }
    
    return super.process(comm); //To change body of generated methods, choose Tools | Templates.
    
  }
}
