package ufsic.scheme.handlers;

import java.io.File;
import java.util.ArrayList;
import java.util.Random;
import ufsic.scheme.Comm;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.utils.Location;

public class RandomFileHandler extends FileHandler {

  public RandomFileHandler(Path path) {
    super(path);
  }
  
  public static boolean isPathRestricted() {
    return true;
  }
  
  public static boolean needSession() {
    return false;
  }
  
  @Override
  public boolean process(Comm comm) {
    
    boolean ret = super.process(comm);
    if (!ret) {
      Scheme scheme = getScheme();
      if (isNotNull(scheme)) {

        Path path = getPath();
        
        StringBuilder builder = new StringBuilder();
        builder.append(scheme.getDefaultDir());
        builder.append(path.getRestOfRootPath(path.getPath().asString()));

        File dir = new File(builder.toString());
        if (dir.exists() && dir.isDirectory()) {

          ArrayList<String> list = new ArrayList<>();
          File[] files = dir.listFiles();
          for (File f: files) {
            if (f.isFile() && f.exists() && !f.isHidden()) {
              list.add(f.getPath());
            }
          }
          
          if (!list.isEmpty()) {
            
            Random rn = new Random();
            int index = rn.nextInt(list.size());
            
            Location loc = new Location(list.get(index));
            if (loc.exists()) {
              File f = loc.getFile();
              setHeaders(f.getName(),f.length());
              ret = loc.write(getEcho().getBufStream());
            }
          }
        }
      }
    }
    return ret;
  }
  
}
