package ufsic.scheme.handlers;

import java.util.Map;
import java.util.Map.Entry;
import ufsic.scheme.Path;

public class TestHandler extends HtmlHandler {

  public TestHandler(Path path) {
    super(path);
  }

  public static boolean isPathRestricted() {
    
    return false;
  }
  
  private void outputPaths(StringBuilder output) {
    
    Path p = getPath();
    
    output.append("<br>");
    
    output.append(String.format("full=%s<br>",p.getFullPath()));
    output.append(String.format("address=%s<br>",p.getAddress()));
    output.append(String.format("path=%s<br>",p.getPathString()));
    output.append(String.format("root=%s<br>",p.getRootPath()));
    output.append(String.format("base=%s<br>",p.getBasePath()));
    output.append(String.format("middle=%s<br>",p.getMiddlePath()));
    output.append(String.format("prev=%s<br>",p.getPrevPath()));
    output.append(String.format("relative=%s<br>",p.getRelativePath()));
    output.append(String.format("path-root=%s<br>",p.getRestOfRootPath(p.getPathString())));
    output.append(String.format("rest=%s<br>",p.getRestPath()));
    output.append(String.format("restValue=%s",p.getRestPathValue()));
    
  }
  
  private void outputParams(StringBuilder output) {
    
    Path p = getPath();
    
    Map<String,String[]> values = p.getParameterValues();
    for (Entry<String,String[]> entry: values.entrySet()) {
      output.append("<br><br>");
      output.append(entry.getKey());
      output.append("<br>");
      output.append("==============================");
      int i = 0;
      for (String s: entry.getValue()) {
        output.append(String.format("<br>%s[%d] = %s",entry.getKey(),i,s));
        i++;
      }
    }

  }
  
  @Override
  protected String getHtml() {
    
    StringBuilder output = new StringBuilder();
    
    output.append(getScheme().getRequestHeaders("<br>"));
    
    outputPaths(output);
    outputParams(output);
    
    return output.toString();
  }
  
  
}