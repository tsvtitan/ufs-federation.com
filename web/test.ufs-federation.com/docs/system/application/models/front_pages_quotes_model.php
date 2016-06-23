<?php
class front_pages_quotes_model extends Model{

	function front_pages_quotes_model()
	{
		parent::Model();
	}
    
	function trim_utf8($s) {
	
		return trim(preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$s));
	}
	
    function view()
    {
      $items=array();

	  $xml = '';
	  
	  try {
        $xml = @file_get_contents('http://web.ufs-federation.com/bloomberg/quotes.php');
	  } catch(Exception $e) {
	    //
      }	  
	  
	  $xml = $this->trim_utf8($xml);
	  
      if ($xml!='') {
      	
		try {
      	  $doc = new DOMDocument;
      	  $doc->preserveWhiteSpace = false;
      	  @$doc->loadXML($xml);      	
      	
      	  $dataNode = $doc->getElementsByTagName('data');
      	
      	  if (isset($dataNode) && ($dataNode->length>0)) {

      		$dataNode = $dataNode->item(0);
      		foreach ($dataNode->childNodes as $dataItem) {

      		  $item = new stdClass();
      		  $item->short_name = '';
      		  $item->security_name ='';
      		  $item->id_isin ='';
      		  $item->bid ='';
      		  $item->ask ='';
      		  $item->px_close_1d ='';
      			
      		  foreach ($dataItem->childNodes as $child) {
      			
      			if ($child->nodeName=='short_name')
      				$item->short_name = $child->nodeValue;

      			if ($child->nodeName=='security_name')
      				$item->security_name = $child->nodeValue;
      			 
      			if ($child->nodeName=='id_isin')
      				$item->id_isin = $child->nodeValue;
      			
      			if ($child->nodeName=='bid')
      				$item->bid = $child->nodeValue;
      			 
      			if ($child->nodeName=='ask')
      				$item->ask = $child->nodeValue;
      			
      			if ($child->nodeName=='px_close_1d')
      				$item->px_close_1d = $child->nodeValue;
      			
      		  }	
      			
      		  $items[] = $item;
      		}      		
		  }	
      	} catch(Exception $e) {
		
        }     	
      	
      }

      $data['data']=$items;
               
      $data['last_update']=self::last_update();
      $data['page_url']=$ret->page_url='pages/'.$this->global_model->pages_url('commodities');
                
      $ret=$this->load->view('body_content_sub_quotes',$data,true);
        
      return $ret;
    }
    

    private function last_update()
    {
       $res = array();
       $item = new stdClass();
       $item->timestamp = date('Y-m-d');
       $res[] = $item;	
       
       $res=$this->global_model->date_format($res,'d #_n_# Y');
       	 
       return $res[0]->timestamp;
    }
    
    

}
?>
