<?php
class backoffice_popup_case_model extends Model{

	function backoffice_popup_case_model()
	{
		parent::Model();
	}
    
    
    function add($table,$content,$page_name,$numbtype,$nat_sort)
    {
        if(isset($_REQUEST['submit'])){
            $error='';
            $content['data']='';

            if(empty($_REQUEST['name'])){
                $error[]='Empty name';
            }

            if(empty($error)){
                $ret='';
                $ret['name']=mysql_string(($numbtype==true)?preg_replace('/\,/','.',$_REQUEST['name']):$_REQUEST['name']);

                $this->db->insert($table,$ret);

                redirect($this->subdir_redirect.$this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));
            }else{
                $content['error']=$error;
            }
        }
        return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
    }

    
    function edit($table,$content,$page_name,$numbtype,$nat_sort)
    {
        if(isset($_REQUEST['submit'])){
            $error='';
            $content['data']='';

                if(empty($_REQUEST['name'])){
                    $error[]='Empty name';
                }

                if(empty($error)){

                    $ret='';
                    $ret['id']=$_REQUEST['id'];
                    $ret['name']=mysql_string(($numbtype==true)?preg_replace('/\,/','.',$_REQUEST['name']):$_REQUEST['name']);

                        $this->db->query('update `'.$table.'` set
                                          `name`="'.$ret['name'].'"
                                          where `id`="'.$ret['id'].'";');

                    redirect($this->subdir_redirect.$this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));

                }else{
                    $content['error']=$error;
                }
        }

            $sql=$this->db->query('select * from `'.$table.'` where `id`="'.$this->uri->segment(5).'";');
            if($sql->num_rows>0){
                $res=$sql->result();
                $res=$res[0];

                    $res->name=stripslashes($res->name);

                    if($numbtype==true){
                        $res->name=preg_replace('/\./',',',stripslashes($res->name));
                    }

                $content['data']=$res;
            }else{
                $content['data']='';
            }
                
        return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);  
    }
    
    
    function del($table,$content,$page_name,$numbtype,$nat_sort)
    {
        $this->db->query('delete from `'.$table.'` where `id`="'.$this->uri->segment(5).'";');

        return redirect($this->subdir_redirect.$this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));  
    }
    
    
    function view($table,$content,$page_name,$numbtype,$nat_sort)
    {  
        $nat_sort=($nat_sort==true)?'+0':'';
        $sql=$this->db->query('select * from `'.$table.'` order by `name`'.$nat_sort.',`id` asc;');
        if($sql->num_rows>0){
            $res=$sql->result();
            $x=0;
            for($i=0;$i<count($res);$i++){

                $res[$i]->name=stripslashes($res[$i]->name);

                if($numbtype==true){
                    $res[$i]->name=preg_replace('/\./',',',$res[$i]->name);
                }

                if($x==1){
                    $res[$i]->css_class=' class="itembg"';
                    $x=0;
                }else{
                    $res[$i]->css_class='';
                    $x=1;
                }
            }
            $content['data']=$res;
        }else{
            $content['data']='';
        }

        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
}
?>
