<?php

require_once 'consts.php';

class Log
{

  private $INFO = 0;
  private $WARN = 1;
  private $ERROR = 2;
  
  private $enabled = false;
  private $file;
  
  public function __construct($file_name,$enabled=true) {
  
    $s = date('Ymd_His');
    $f = sprintf($file_name,$s);
    $this->enabled = $enabled;
    
    if ($this->enabled) { 
      $this->file = @fopen($f,'c+');
      
      if (! $this->file)
        throw new Exception("Could not open $f");
    }  
  
  }
  
  public function __destruct() {
  
    @fclose($this->file);
    unset($this->file);
  }
  
  public function write($message,$type) {

    if ($this->enabled) {
      
      $types = array ($this->INFO => 'I', $this->WARN => 'W', $this->ERROR => 'E');
    
      list($msec,$sec) = explode(' ',microtime());
      $msec = substr((string)$msec,1,4);
      $s1 = sprintf('%s%s',date('d.m.Y H:i:s'),$msec);
      $s2 = $types[$type];
      $s3 = $message;
      $s = sprintf(LOG_FORMAT,$s1,$s2,$s3);
      @fwrite($this->file,"$s\n");
    
      echo $s."<br>";
    }
  }
    
  public function writeInfo($message) {

    $this->write($message,$this->INFO);
  }
  
  public function writeWarn($message) {

    $this->write($message,$this->WARN);
  }
  
  public function writeError($message) {

    $this->write($message,$this->ERROR);
  }
  
  public function writeException($exception) {
    
    $this->writeError($exception->getMessage());
  }
  
}

?>
