package ufsic.scheme.handlers;

import java.awt.Color;
import java.awt.image.BufferedImage;
import javax.imageio.ImageIO;
import nl.captcha.Captcha;
import nl.captcha.Captcha.Builder;
import nl.captcha.gimpy.*;
import nl.captcha.noise.CurvedLineNoiseProducer;
import nl.captcha.text.producer.DefaultTextProducer;
import nl.captcha.text.renderer.*;
import ufsic.scheme.Comm;
import ufsic.scheme.Handler;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.utils.Utils;

public class CaptchaHandler extends Handler {

  public CaptchaHandler(Path path) {
    super(path);
  }
  
  public static boolean isPathRestricted() {
    return false;
  }
  
  private void setHeaders(String type, Long size) {
    
    Path path = getPath();
    
    path.setContentHeaders(String.format("image/%s",type),size);
    path.setHeader("Content-Transfer-Encoding","binary");
  } 
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = false;
    
    Scheme scheme = getScheme();
    if (isNotNull(scheme)) {
      
      Path path = getPath();
      if (isNotNull(path)) {

        int width = path.getParameterValue("w",200);
        int height = path.getParameterValue("h",50);
        String type = path.getParameterValue("i","png");
        
        String id = path.getRestPathValue();
        if (isNotNull(id)) {
          
          try {
            Builder builder = new Captcha.Builder(width,height);
            
            char[] letters = Utils.generateChars((short)97,(short)25);
            char[] numbers = Utils.generateChars((short)48,(short)10);
            char[] full = new char[35];
                    
            System.arraycopy(letters,0,full,0,letters.length);
            System.arraycopy(numbers,0,full,letters.length,numbers.length);
            
            builder.addText(new DefaultTextProducer(6,full),new ColoredEdgesWordRenderer());
            //builder.addText(new NumbersAnswerProducer(2));
            builder.addNoise(new CurvedLineNoiseProducer(Color.LIGHT_GRAY,3));
            //builder.gimp(new RippleGimpyRenderer());
            //builder.gimp(new BlockGimpyRenderer());
            builder.gimp(new DropShadowGimpyRenderer());
            

            Captcha captcha = builder.build();
            BufferedImage image = captcha.getImage();
            
            ret = ImageIO.write(image,type,getEcho().getBufStream());
            if (ret) {
              path.setCaptcha(id,captcha.getAnswer());
              setHeaders(type,(long)getEcho().getBufStream().size());
            }
            
          } catch (Exception e) {
            logException(e);
          }
        }
      }
    }
    return ret;
  }
  
  
}
