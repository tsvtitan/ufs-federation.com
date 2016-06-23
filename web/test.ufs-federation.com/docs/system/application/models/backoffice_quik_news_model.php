<?php
class backoffice_quik_news_model extends Model{

	function backoffice_quik_news_model()
	{
		parent::Model();
	}
    
	private function send($file_name,$local_name)
	{
        $error="";
      	$ftp_server='195.151.220.201';
      	//$ftp_server='10.1.2.1';
      	$ftp_user='backup';
      	$ftp_pass='1q2w3e4r';
      	$remote_name='Quik_News/'.$file_name;
      	
        $conn_id=ftp_connect($ftp_server);
           
        if ($conn_id) {
          
        	if (ftp_login($conn_id,$ftp_user,$ftp_pass)) {
          
          		$ret=ftp_put($conn_id,$remote_name,$local_name,FTP_ASCII);
          
          		if ($ret) {
          			 
      	            $error='1';
          			 
          		} else {
          
          			$error="Не удается отправить данные в QUIK";
          			 
          		}
          		 
          	} else {
          
          		$error="Не удается отправить данные в QUIK";
          	}
          
          	ftp_close($conn_id);
          
        } else {
         
        	$error="Не удается подключиться к QUIK";
          
        }
          
        return $error;      	
	   
	}
    
    function view()
    { 
      $content['data']='';
      
      $error='';
      
      if(isset($_POST['submit'])) {
      	
      	$link=$_POST['lnk'];
      	$short_url = json_decode(file_get_contents("http://api.bit.ly/v3/shorten?login=ufsic&apiKey=R_633ce6ab637030878e5cc4c2813ee998&longUrl=".urlencode($link)."&format=json"))->data->url;
      	
      	$id=rand(1, 13754);
      	
      	ini_set('mbstring.substitute_character',"none");
		
      	$anounce=$_POST['message'];
      	$anounce=mb_convert_encoding($anounce,'UTF-8','UTF-8');
//      	$anounce=substr($anounce,0,19456);
      	
      	$subj=$_POST['subj'];
      	$subj=mb_convert_encoding($subj,'UTF-8','UTF-8');
      //	$subj=substr($subj,0,99);
      	
		$file_name=$id.'.txt';
      	$local_name='/home/ufs-federa/backup/'.$file_name;
        //$local_name='/var/www/html/website/backup/'.$file_name;
		
      	$f=fopen($local_name,"w+");
      	
      	if ($f) {
      		
		  $data='INDEX='.$id."\r\n".
		        'AGENCY=FILEN'."\r\n".
				'IMPORTANCE=1'."\r\n".
				'DATETIME='.date("d.m.Y H:i:00")."\r\n".
				'SUBJECT='.$subj."\r\n\r\n".
                $anounce."\r\n\r\n".
				'Прочесть полную версию у нас на сайте: '.$short_url;
			 
  	      $data=mb_convert_encoding($data,'WINDOWS-1251','UTF-8');	
                           	
		  fwrite($f,$data);
          fclose($f);

          $ret=$this->send($file_name,$local_name);
          //$ret='1';
          if ($ret=="1") {
		  
		    $error='Новость создана успешно.';
			
			unlink($local_name);
			
		  } else {
		  
		    $error=$ret;
			
		  }
          
      	} else {
      		
      	  $error='Не удается создать новость QUIK';	
      	}  
      	
      	$content['subj']=$subj;
      	$content['lnk']=$link;
      	$content['message']=$anounce;
      	$content['error']=$error;
      	 
      } else {
      	
      	$content['subj']='Заголовок';
      	$content['lnk']='Ссылка на обзор';
      	$content['message']='Напишите анонс обзора';
      	$content['error']='';
      	 
      }
        
      return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
    
}
?>
