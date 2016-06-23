<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_CBRF {
		
  private $soap = null;
  const WSDL = 'http://www.cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?wsdl'; 
  
	function MY_CBRF() {

	  $this->soap = new SoapClient(MY_CBRF::WSDL);
	}
	
	private function BicToIntCode($bic) {
	 
	  $ret = false;
	  if ($this->soap) {
	    $params['BicCode'] = $bic;
	    $r = $this->soap->BicToIntCode($params);
      if (isset($r) && isset($r->BicToIntCodeResult)) {
        $r = $r->BicToIntCodeResult;
        if ($r!=-1) {
          $ret = $r;
        }
      }
	  }  
	  return $ret;
	}
	
	function getBankByBic($bic) {
	 
	  $ret = false;
	  if ($this->soap && $bic) {
	    
	    $code = $this->BicToIntCode($bic);
	    if ($code) {
	      
	      $p1['InternalCode'] = $code; 
	      $r = $this->soap->CreditInfoByIntCodeXML($p1);
	      if (isset($r) && isset($r->CreditInfoByIntCodeXMLResult) && isset($r->CreditInfoByIntCodeXMLResult->any)) {
	         
	        $xml = @simplexml_load_string($r->CreditInfoByIntCodeXMLResult->any);
	        if ($xml) {
	          $ret = new StdClass();
	          $ret->bic = $bic;
	          if (isset($xml->CO)) {
	            $ret->name = (string)$xml->CO->OrgName;
	            $ret->region = (string)$xml->CO->RegCode;
	            $ret->address = (string)$xml->CO->FactAdr;
	            $ret->offices = array();
	            
	            $p2['IntCode'] = $code;
	            $r = $this->soap->GetOfficesXML($p2);
	            if (isset($r) && isset($r->GetOfficesXMLResult) && isset($r->GetOfficesXMLResult->any)) {
	              
	              $xml = @simplexml_load_string($r->GetOfficesXMLResult->any);
	              if ($xml) {

	                foreach($xml->Offices as $office) {
	                  
	                  $r1 = new stdClass();
	                  $r1->num = (int)$office->cregnum;
	                  $r1->name = (int)$office->cname;
	                  $r1->address = (string)$office->straddrmn;
	                  $ret->offices[] = $r1;
	                }
	              }
	            }
	          }  
	        }
	      } 
	    }
	    
	  }
	  return $ret;
	}
	

}
?>