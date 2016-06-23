<?
class backoffice_portfolio_history_model extends Model {

  function backoffice_portfolio_history_model() {
    
    parent::Model();

    if(isset($_REQUEST['form_search'])) {
      
      $_SESSION['search'] = mysql_string($_REQUEST['search']);
     
    } else {
      
      if(!isset($_SESSION['search'])) {
        
        $_SESSION['search']='';
      }
    }
  }
    
  function add()  {
    
    }
        
    
    function edit() {
      
    }  
    
    
    function del()
    {

    }    
    
  function view() {
    
    $content['data']='';

      if($this->role_id==0 and empty($_SESSION['search'])){
        return redirect($this->uri->segment(1).'/'.$this->page_name.'/role/'.$this->global_model->GET_role_first());
      }
      
        if(isset($_SESSION['admin']->is_update)){
            $content['is_update']=$this->lang->line('admin_tpl_page_updated');
            unset($_SESSION['admin']->is_update);
        }
        
        $where='';
        
        if($this->role_id>0){
            $where.=' where `t`.`role_id`="'.$this->role_id.'"';
        }
        
        if(!empty($_SESSION['search']))
        {
          $s=' `t`.`path` like "%'.$_SESSION['search'].'%"';
          
          if ($where=="") {
            $where.=' where '.$s;
          } else {
            $where.=' and '.$s;
          }
            
        }
        
        $vals='count("t") as Rows';
        $limit=''; 
        $sql_r=$this->db->query(self::view_sql_str($vals,$where,$limit));
        
        if($sql_r->num_rows()>0){  
            $res_r=$sql_r->row();

            $show=1000;
            $rows=$res_r->Rows;
            $ret_page=((int)$this->uri->segment(6)==0)?1:(int)$this->uri->segment(6);
            $param['url']='/role/'.$this->role_id;

            $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
        
            $vals='`t`.`id`, `t`.`path`, `t`.`access`, `t`.`role_id`';
            $limit='limit '.$pages['start'].','.$pages['show']; 
            $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

                if($sql->num_rows()>0){  
                    $res=$sql->result();  

                         $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                         $res=$this->global_model->rand_css_class($res);
                         $res=$this->global_model->date_format($res);
                         

                    $content['data']=$res;
                }                
          }
          
          $content['roles']=$this->global_model->GET_roles($this->role_id,' class="sel"');
          
            
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
  }
    
}
?>