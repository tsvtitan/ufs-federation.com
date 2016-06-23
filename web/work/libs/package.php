<?php

class Item {
  
  public $name = '';
  public $value = null;
  public $childs = null;
  public $attributes = array();
  
  public function __construct($name) {
    $this->name = $name;
    $this->childs = new Items();
  }
   
}

class Items extends ArrayObject {
  
  public function find($name) {
  
    $ret = false;
    foreach ($this as $l) {
      if ($l->name==$name) {
        $ret = $l; 
        break;
      }
    }
    return $ret;
  }
  
  public function add($name,$unique=true) {

    $ret = false;
    if ($unique) {
      $ret = $this->find($name);
    }  
    if (!$ret) {
      $ret = new Item($name); 
      $this[] = $ret;
    }
    return $ret;
  }
  
  protected function getXml($doc,$node) {
    
    foreach ($this as $item) {
      $n = $doc->createElement($item->name);
      $n->nodeValue = $item->value;
      if (sizeof($item->attributes)>0) {
        foreach ($item->attributes as $a => $v) {
          $n->setAttribute($a,$v);
        }
      }  
      $node->appendChild($n);
      $item->childs->getXml($doc,$n);
    }
  }
  
  protected function setXml($doc,$node) {
    
    if (sizeof($node->childNodes)>0) {
      
      foreach ($node->childNodes as $cn) {

        $item = false;
        if (!$item) {
          $item = new Item($cn->nodeName);
          $this[] = $item;
        }
        $item->value = $cn->nodeValue;
        if (sizeof($cn->attributes)>0) {
          foreach ($cn->attributes as $a) {
            $item->attributes[$a->nodeName] = $a->nodeValue;
          }
        }  
        $item->childs->setXml($doc,$cn);
      }    
    }
  }
}

class Package extends Items {

  public $method = null;
  public $stamp = null;
  public $version = '1.0';
  
  public function __construct($method='') {
    $this->method = $method;
  }
  
  public function saveXml() {
    
    $ret = false;
    if (sizeof($this)>0) {
      $doc = new DOMDocument('1.0','UTF-8');
      $doc->preserveWhiteSpace = false;
      $doc->formatOutput = true;
      $root = $doc->createElement('package');
      $root->setAttribute('method',$this->method);
      
      $this->stamp = time();
      $root->setAttribute('date',date('Y-m-d',$this->stamp));
      $root->setAttribute('time',date('H:i:s',$this->stamp));
      $root->setAttribute('version',$this->version);
      $doc->appendChild($root);

      $this->getXml($doc,$root);
      $ret = $doc->saveXML();
    }
    return $ret;
  }
  
  public function loadXml($xml) {
    
    $ret = false;
    if ($xml) {
      $doc = new DOMDocument;
      $doc->preserveWhiteSpace = false;
      @$doc->loadXML($xml);

      $root = $doc->getElementsByTagName('package');
      if (isset($root)) {
        $root = $root->item(0);
        if (isset($root)) {
          $this->method = $root->getAttribute('method');
         
          $date = $root->getAttribute('date');
          $time = $root->getAttribute('time');
          $this->stamp = strtotime(sprintf('%s %s',$date,$time));
          $this->version = $root->getAttribute('version');
          if ($this->version=='1.0') {
            $this->setXml($doc,$root);
          } 
        }    
      }
    }
    return $ret;
  }

}
?>