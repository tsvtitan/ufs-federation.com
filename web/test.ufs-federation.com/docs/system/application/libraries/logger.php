<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once $GLOBALS['application_folder'].'/libraries/utils.php';

class Logger {

  public function __construct() {
  
  }
  
  public function __destruct() {
    
  }
  
  public function log($s) {

    return log_write($s,'debug',3);
  }
  
}

?>