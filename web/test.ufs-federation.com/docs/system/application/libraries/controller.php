<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once $GLOBALS['application_folder'].'/libraries/utils.php';

class ControllerEx extends Controller {

  public function __construct() {
    
    parent::__construct();
    
    set_exception_handler(array($this,'exception_handler'));
    
    $GLOBALS['CONTROLLER_REF'] = $this;
  }
  
  public function __destruct() {
    
    //parent::_destruct();
  }
  
  private function exception_handler(Exception $e) {
    
    $this->log($e->getMessage());  
  }
  
  public function log($s) {
    
    return log_write($s,'debug',2);
  }
  
}

?>