package ufsic.scheme.handlers;

import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;

import com.google.zxing.*;
import com.google.zxing.Writer;
import com.google.zxing.client.j2se.*;
import com.google.zxing.common.*;
import com.google.zxing.qrcode.*;
import java.util.HashMap;
import ufsic.scheme.Handler;

public class QRCodeHandler extends Handler {

  public QRCodeHandler(Path path) {
    super(path);
  }
  
  private void setHeaders(String type, Long size) {
    
    Path path = getPath();
    
    path.setContentHeaders(String.format("image/%s",type),size);
    path.setHeader("Content-Transfer-Encoding","binary");
  } 
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = super.process(comm);
    
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
      
      Path path = getPath();
      if (isNotNull(path)) {

        int width = path.getParameterValue("w",200);
        int height = path.getParameterValue("h",200);
        String type = path.getParameterValue("i","PNG");
        String text = path.getParameterValue("t",path.getFullPath());

        HashMap<EncodeHintType,Object> hints = new HashMap<>();
        Writer writer = new QRCodeWriter();
        try {
          BitMatrix matrix = writer.encode(text,BarcodeFormat.QR_CODE,width,height,hints);
          MatrixToImageWriter.writeToStream(matrix,type,getEcho().getBufStream());
          setHeaders(type,(long)getEcho().getBufStream().size());
          ret = true;
        } catch(Exception e) {
          logException(e);
        }
      }
    }
    return ret;
  }
  
}
