<?php
class front_disclosure_of_information_download_pdf_model extends Model{

	function front_disclosure_of_information_download_pdf_model()
	{
		parent::Model();
	}
    
    function download()
    {    
       $url=mysql_string($this->uri->segment(3));

            $sql=$this->db->query('select `url`,`_file`
                                   from `'.$this->page_name.'`
                                   where `lang`="'.$this->site_lang.'"
                                   and `url`="'.$url.'"
                                   limit 1;');

                if($sql->num_rows()>0){
                    $res=$sql->row();

                    $filetype=explode('.',$res->_file);
                    $filename=$res->url.'.'.$filetype[count($filetype)-1];
                    $download_url=$_SERVER['DOCUMENT_ROOT'].'/upload/disclosure_of_information/'.$res->_file;

                    header("Content-type: application/force-download");
                    header("Content-Disposition: attachment; filename=".$filename);
                    header("Content-Transfer-Encoding: binary");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Pragma: public");
                    header("Content-Length: ".filesize($download_url));
                    readfile($download_url);
                }

                
    }
    
}
?>
