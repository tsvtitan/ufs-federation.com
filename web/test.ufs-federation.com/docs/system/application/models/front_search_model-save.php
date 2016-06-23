<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/system/libraries/sphinxapi.php');

class front_search_model extends Model{

	function front_search_model()
	{
		parent::Model();
        
        
        $this->load->model('maindb_model');
	}
    
    function view()
    {  

    	$data            = '';
    	$data['results'] = array();
    	
    	$kword           = isset($_REQUEST['searched'])?$_REQUEST['searched']:'';
    	$kword           = stripslashes($kword);
    	$data['kword']   = $kword;
    	
    	if(!empty($kword)){
			    $sphinx = new SphinxClient();			
			    // Подсоединяемся к Sphinx-серверу
			    //$sphinx->SetServer('178.210.70.134');			   
			    $sphinx->SetServer('/home/ufs-federa/sphinx/var/searchd.socket');			   
			    // Совпадение по любому слову
			    $sphinx->SetMatchMode(SPH_MATCH_ANY);			   
			    // Результаты сортировать по релевантности
			    $sphinx->SetSortMode(SPH_SORT_RELEVANCE);			   
			    // Задаем полям веса (для подсчета релевантности)
			    $sphinx->SetFieldWeights(array ('name' => 20, 'short_content' => 10));			
			    // Результат по запросу (* - использование всех индексов)
			    $result = $sphinx->Query($kword, '*');    		
	    		   if ( $result === false ) { 
		          	//echo "Query failed: " . $sphinx->GetLastError() . ".\n"; // выводим ошибку если произошла
		           }
			    //var_dump($result);
    	}

        return $this->load->view('view_search_result',$data,true);
    }

}
?>
