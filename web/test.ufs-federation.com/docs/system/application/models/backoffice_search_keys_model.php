<?php

class backoffice_search_keys_model extends Model {

  private $table = 'search_keys';
  private $empty_group = 'Без группы';
  
  function backoffice_search_keys_model() {
    
    parent::Model();

    if(isset($_REQUEST['form_search'])) {
      
      $_SESSION['search'] = mysql_string($_REQUEST['search']);
     
    } else {
      
      if(!isset($_SESSION['search'])) {
        
        $_SESSION['search']='';
      }
    }
  }
  
  function from_group($group) {
    
    return ($group!=$this->empty_group)?$group:null;
  }
  
  function to_group($group) {
    
    return (!isset($group) || is_null($group) || trim($group)=='')?$this->empty_group:$group;
  }
  
  function add() {
    
    if (isset($_REQUEST['submit'])) {

      $new = $this->global_model->get_request($_REQUEST);
      if ($new) {
        $new['lang'] = $this->site_lang;
        $this->global_model->insert($this->table,$new);
      }
      $group = $this->to_group($new['group']);

      return redirect($this->uri->segment(1).'/'.$this->page_name.'/group/'.$group);

    } else {

      $data->group = $this->from_group($this->uri->segment(4));
      $content['group'] = $this->uri->segment(4);
      $content['data'] = $data;

      return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
    }
  }
  
  function edit() {
    
    if (isset($_REQUEST['submit'])) {

      $search_key_id = isset($_REQUEST['search_key_id'])?$_REQUEST['search_key_id']:false;
      if ($search_key_id) {
        $new = $this->global_model->get_request($_REQUEST);
        if ($new) {
          $new['lang'] = $this->site_lang;
          $this->global_model->update($this->table,$new,array('search_key_id'=>$search_key_id));
        }
        $group = $this->to_group($new['group']);
      } else {
        $group = $this->uri->segment(4);
      }

      return redirect($this->uri->segment(1).'/'.$this->page_name.'/group/'.$group);

    } else {

      $search_key_id = (int)$this->uri->segment(5);
      $content = array();
      
      $data = $this->global_model->get_table_data_as_class($this->table,null,array('search_key_id'=>$search_key_id),null,1);
      if (is_array($data) && sizeOf($data)>0) {
        $data = $data[0];
        $data->group = $this->from_group($data->group);
        $content['data'] = $data;
      }
      $content['group'] = $this->uri->segment(4);

      return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
    }
  }
    
  function del() {

    $group = $this->uri->segment(4);
    $search_key_id = (int)$this->uri->segment(5);
    
    $this->global_model->delete($this->table,array('search_key_id'=>$search_key_id));
    
    return redirect($this->uri->segment(1).'/'.$this->page_name.'/group/'.$group);
  }
    
  private function get_groups($group=null) {

    $ret = false;
    
    $sql = sprintf('select (case 
                             when t.name is null then "%s"
                             else t.name
                            end) as name
                      from (select distinct(`group`) as name from %s where lang="%s" order by 1) t',
                   $this->empty_group,$this->table,$this->site_lang);
    
    $r = $this->global_model->get_query_data_as_class($sql);
    if (is_array($r) && sizeOf($r)>0) {
      
      if (!is_null($group)) {
        $r = $this->global_model->set_prop_for($r,array('sel'=>' class="sel"'),array('name'=>$group));
      }
      $ret = $r;
    }
    return $ret;
  }
  
  private function get_first_group_name() {
    
    $ret = false;
    
    $r = $this->get_groups(null);
    if ($r) {
      $ret = $r[0]->name;
    }
    return $ret;
  }

  
  function view($group=null) {
    
    $content['data'] = '';

    $group = is_null($group)?false:$group;
    
    if (!$group) {
      
      $group = $this->get_first_group_name(); 
      if ($group) {
        return redirect($this->uri->segment(1).'/'.$this->page_name.'/group/'.$group);
      }
    }
      
    if (isset($_SESSION['admin']->is_update)) {

      $content['is_update'] = $this->lang->line('admin_tpl_page_updated');
      unset($_SESSION['admin']->is_update);
    }
    
    $where = array('lang'=>$this->site_lang);
    if ($group!=$this->empty_group) {
      $where['group'] = $group;
    } else {
      $where['group'] = null;
    }
    
    if (!empty($_SESSION['search'])) {
      $where['text'] = array('like'=>sprintf('%s',$_SESSION['search']).'%');
    }
    
    $r = $this->global_model->get_table_data_as_class($this->table,array('count(*) as cnt'),$where,null,1);
    if (is_array($r) && sizeOf($r)>0) {
      
      $count = 30;
      $rows = $r[0]->cnt;
      if ($rows>0) {
        
        $page = ((int)$this->uri->segment(6)==0)?1:(int)$this->uri->segment(6);
        $param['url'] = '/group/'.$group;
        $pages = $this->global_model->Pager($count,$rows,$param,$page);
        
        $data = array();
        $r = $this->global_model->get_table_data_as_class($this->table,null,$where,array('priority'),$count,($page-1)*$count);
        if (is_array($r) && sizeOf($r)>0) {
          $data = $r;
        }
        
        $content['data'] = $this->global_model->rand_css_class($data);
        $content['pages'] = $pages['html'];
      }
    }
    
    $content['group'] = $group;
    $content['groups'] = $this->get_groups($group);
    
    return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    
  }
  
}
?>