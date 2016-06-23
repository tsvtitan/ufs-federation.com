package ufsic.scheme.handlers;

import com.fasterxml.jackson.annotation.JsonInclude;
import com.fasterxml.jackson.core.JsonGenerator;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.SerializationFeature;
import javax.servlet.http.HttpServletRequest;

import ufsic.scheme.Comm;
import ufsic.scheme.Handler;
import ufsic.scheme.Path;

public class JsonHandler extends Handler {

  final private ObjectMapper writer = new ObjectMapper();
  {
    writer.configure(JsonGenerator.Feature.ESCAPE_NON_ASCII,true);
    writer.configure(SerializationFeature.WRITE_NULL_MAP_VALUES,false);
    writer.configure(SerializationFeature.WRITE_EMPTY_JSON_ARRAYS,true);
    writer.configure(SerializationFeature.WRAP_ROOT_VALUE,false);
    writer.setSerializationInclusion(JsonInclude.Include.ALWAYS);
  }
  
  final private ObjectMapper reader = new ObjectMapper();
  {
    reader.configure(JsonGenerator.Feature.ESCAPE_NON_ASCII,true);
  }

  protected class Request {
    
    public Request() {
      
    }
  }
  
  protected class Response {
    
    public Response() {
      
    }
  }
  
  protected class ResponseException extends Exception {
    
    public ResponseException(String message) {
        super(message);
    }
  }
  
  public JsonHandler(Path path) {
    super(path);
  }
  
  public static String getContentType() {
    
    return "application/json";
  }
  
  protected Class getRequestClass() {
    return Request.class;
  }
  
  private boolean writeResponse(Response response) {
    
    boolean ret = false;
    try {
      writer.writeValue(getEcho().getBufStream(),response);
      ret = true;
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  private Request readRequest(Comm comm) {
    
    Request ret = null;
    try {
      Class<Request> cls = getRequestClass();
      if (isNotNull(cls)) {
        byte[] bytes;
        if (isNotNull(comm)) {
          bytes = comm.getInData().asBytes();
        } else {
          bytes = getScheme().getRequestBody();
        }
        
        HttpServletRequest request = getPath().getRequest();
        String method = request.getMethod();
        String type = request.getContentType();
        
        boolean need = bytes.length>0 && 
                       isNotNull(method) && method.toUpperCase().equals("POST") &&
                       isNotNull(type) && (type.toLowerCase().contains(getContentType()));
        
        if (need) {
          ret = reader.readValue(bytes,cls);
        }
      }
    } catch (Exception e) {
      logException(e);
    }
    return ret;
  }
  
  protected Response prepareResponse() throws ResponseException {
    
    return null;
  }
  
  protected Response prepareResponse(Request request) throws ResponseException {
    
    return prepareResponse();
  }
  
  protected Response prepareExceptionResponse(Request request, Exception exception) {
    
    return null;
  }
  
  @Override
  public boolean process(Comm comm) {
    
    Response response;
    Request request = null;
    try {
      request = readRequest(comm);
      response = prepareResponse(request);
    } catch (Exception e) {
      response = prepareExceptionResponse(request,e);
    }
    return writeResponse(response);
  }
  
  
}