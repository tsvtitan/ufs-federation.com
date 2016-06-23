package ufsic.scheme.handlers;

import ufsic.scheme.Path;

public class MultiPageHandler extends PageHandler {

  public MultiPageHandler(Path path) {
    super(path);
  }
  
  public static boolean isPathRestricted() {
    return false;
  }
}
