<?php
class backoffice_popup_case_portfolios_model extends Model{

  function backoffice_popup_case_portfolios_model() {
    
    parent::Model();
  }
    
  function add($table,$content,$page_name) {
    
    $data = $content;
    
    $desc = $this->global_model->get_table_desc($table);
    
    if (isset($_REQUEST['submit'])) {
    
      $error = false;
      
      $new = $this->global_model->get_request($_REQUEST);
      if ($new) {
        
        
        if (empty($new['name'])) {
          $error[] = $this->lang->line('admin_pages_popup_error_name');
        } 
        if (empty($new['currency_id'])) {
          $error[] = $this->lang->line('admin_pages_popup_error_currency');
        }
        
        if (!$error) {
          
          $new['lang'] = $this->site_lang;
          
          $this->global_model->insert($table,$new);
          
          return redirect($this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));
          
        }
      }
      
      if ($error) {
        $data['error'] = $error;
      }
    }
    
    $data['currency_id'] = ($desc)?$desc['currency_id']->default:null;
    $data['currency'] = $this->global_model->get_table_data_as_class('currency',null,null,array('name'));
    
    return $this->load->view('backoffice_'.$this->page_name.'_edit_portfolios',$data,true); 
  }

  function edit($table,$content,$page_name) {
    
    $data = $content;
    $portfolio_id = (int)$this->uri->segment(5);
    
    if (isset($_REQUEST['submit'])) {
      
      $error = false;
      
      $new = $this->global_model->get_request($_REQUEST);
      if ($new) {
        
        if (empty($new['name'])) {
          $error[] = $this->lang->line('admin_pages_popup_error_name');
        }
        if (empty($new['currency_id'])) {
          $error[] = $this->lang->line('admin_pages_popup_error_currency');
        }
        
        if (!$error) {
          
          $this->global_model->update($table,$new,array('portfolio_id'=>$portfolio_id));
          
          return redirect($this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));
        }
      }
      
      if ($error) {
        $data['error'] = $error;
      }
      
    } else {
      
      $old = $this->global_model->get_table_data($table,null,array('portfolio_id'=>$portfolio_id),null,1);
      if ($old && is_array($old) && sizeOf($old)>0) {
        
        $old = $old[0];
        foreach($old as $ok=>$ov) {
          $data[$ok] = $ov;
        }
      }
    }
    
    $data['currency'] = $this->global_model->get_table_data_as_class('currency',null,null,array('name'));
    
    return $this->load->view('backoffice_'.$this->page_name.'_edit_portfolios',$data,true);
  }
    
  function del($table,$content,$page_name) {
    
    $portfolio_id = (int)$this->uri->segment(5);

    $this->global_model->delete($table,array('portfolio_id'=>$portfolio_id));
    
    return redirect($this->subdir_redirect.$this->uri->segment(1).'/'.$this->page_name.'/'.$this->uri->segment(3));
    
  }
    
  function view($table,$content,$page_name) {
        
    $data = array();
    
    $r = $this->global_model->get_table_data_as_class($table,null,array('lang'=>$this->site_lang),array('created'));
    if (is_array($r) && sizeOf($r)>0) {
      
      $data = $this->global_model->adjustment_of_results($r,$table);
      $data = $this->global_model->rand_css_class($data);
    }
    $content['data'] = $data;

    return $this->load->view('backoffice_'.$this->page_name.'_view_portfolios',$content,true);
  }
  
}
?>