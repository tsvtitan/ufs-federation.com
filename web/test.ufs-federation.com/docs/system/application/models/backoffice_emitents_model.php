<?php
class backoffice_emitents_model extends Model {

  private $table = 'emitents';
  
  function backoffice_emitents_model() {
    
    parent::Model();

    if(isset($_REQUEST['form_search'])) {
      
      $_SESSION['search'] = mysql_string($_REQUEST['search']);
     
    } else {
      
      if(!isset($_SESSION['search'])) {
        
        $_SESSION['search']='';
      }
    }
  }
  
  function add() {
    
    $data = array();
    
    if (isset($_REQUEST['submit'])) {
      
      $error = false;
      
      $new = $this->global_model->get_request($_REQUEST);
      if ($new) {
        
        if (empty($new['name'])) {
          $error[] = $this->lang->line('admin_pages_popup_error_name');
        }
        
        if (!$error) {
        
          $visible = isset($_REQUEST['visible'])?true:false;
          
          $new['emitent_id'] = $this->global_model->get_unique_id();
          $new['finished'] = ($visible)?null:date('Y-m-d H:i:s');
          $new['lang'] = $this->site_lang;
        
          $this->global_model->insert($this->table,$new);
        
          return redirect($this->uri->segment(1).'/'.$this->page_name);
        }
      }
      
      if ($error) {
        $data['error'] = $error;
      }
    }
    
    return $this->load->view('backoffice_'.$this->page_name.'_edit',$data,true);
  }
  
  function edit() {
    
    $data = array();
    $emitent_id = $this->uri->segment(4);
    
    if (isset($_REQUEST['submit'])) {
    
      $error = false;
    
      $new = $this->global_model->get_request($_REQUEST);
      if ($new) {
    
        if (empty($new['name'])) {
          $error[] = $this->lang->line('admin_pages_popup_error_name');
        }
    
        if (!$error) {
    
          $visible = isset($_REQUEST['visible'])?true:false;
          $new['finished'] = ($visible)?null:date('Y-m-d H:i:s');
          
          $this->global_model->update($this->table,$new,array('emitent_id'=>$emitent_id));
    
          return redirect($this->uri->segment(1).'/'.$this->page_name);
        }
      }
    
      if ($error) {
        $data['error'] = $error;
       }
    
     } else {
    
       $old = $this->global_model->get_table_data($this->table,null,array('emitent_id'=>$emitent_id),null,1);
       if ($old && is_array($old) && sizeOf($old)>0) {
         
         $old = $old[0];
         foreach($old as $ok=>$ov) {
           $data[$ok] = $ov;
        }
      }
    }
    
    return $this->load->view('backoffice_'.$this->page_name.'_edit',$data,true);
  }
  
  function del() {

    $emitent_id = $this->uri->segment(4);
    
    $order_emitents = $this->global_model->get_table_data_as_class('order_emitents',array('count(*) as cnt'),array('emitent_id'=>$emitent_id),null,1);
    if (is_array($order_emitents) and sizeOf($order_emitents)==1) {
      
      if ($order_emitents[0]->cnt==0) {
        
        $this->global_model->delete('emitents',array('emitent_id'=>$emitent_id));
      }
    }
    
    return redirect($this->uri->segment(1).'/'.$this->page_name);
  }
    
  function view() {
    
    $content['data'] = '';

    if (isset($_SESSION['admin']->is_update)) {

      $content['is_update'] = $this->lang->line('admin_tpl_page_updated');
      unset($_SESSION['admin']->is_update);
    }
    
    $where = array();

    if(!empty($_SESSION['search'])) {
      
      $where[] = 'e.name like "%'.$_SESSION['search'].'%"';
    }
    
    $where[] = 'e.lang='.$this->global_model->quote($this->site_lang); 
    $w = '';
    
    if (sizeOf($where)>0) {
      $w = ' where '.implode(' and ',$where); 
    }
    
    $r = $this->global_model->get_query_data_as_class('select e.*, '.
                                                             'case when t1.cnt is null then 0 else t1.cnt end as order_count, '.
                                                             'case when e.finished is null then "да" else "нет" end as visible '.
                                                        'from '.$this->table.' e '.
                                                        'left join (select emitent_id, count(*) as cnt '.
                                                                     'from order_emitents '.
                                                                    'group by 1) t1 on t1.emitent_id=e.emitent_id '.$w.
                                                       'order by e.priority, e.name ');
    if (is_array($r) && sizeOf($r)>0) {
        
      $r = $this->global_model->rand_css_class($r);
      $content['data'] = $r;
    }
    
    return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
  }

}
?>