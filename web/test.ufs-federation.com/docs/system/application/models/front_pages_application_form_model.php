<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/system/libraries/strutils.php');

class front_pages_application_form_model extends Model {

	function front_pages_application_form_model() { 
		
		parent::Model();
        
    $this->load->model('maindb_model');
	}
	
	function send_emails($emails,$subject,$body) {
		
		return $this->global_model->send_emails($emails,$subject,$body,null,'Онлайн анкета');
	}
	
	function get_num($application_form_id) {
	 
	  $ret = 1;
	  
	  $res = $this->db->query('select max(num) as num from application_forms');
	  if($res->num_rows()>0){
	  	$res = $res->result();
	  	$ret = $res[0]->num;
	  	$ret++;
	  }
	   
	  return $ret;
	}
		
	function view($type=null) {
  	  
		$data = array();

		$ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null;
		$agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:null;
		$lang = isset($_REQUEST['lang'])?$_REQUEST['lang']:null;
		if ($lang=='') {
			$lang = $this->site_lang;
		}

		$step = isset($_REQUEST['step'])?$_REQUEST['step']:1;
		$back = (isset($_REQUEST['back'])&&($_REQUEST['back']=='true'))?true:false;
		$next = isset($_REQUEST['next'])?true:false;
		
		$table = 'application_forms';
		$desc = $this->global_model->get_table_desc($table);
				
		$new = $this->global_model->get_request($_REQUEST);
		
		if ($new) {
		  if ($step==1) {
		    if (isset($new['birth_date'])) {
			    $new['birth_date'] = date('Y-m-d',strtotime($new['birth_date']));
		    }
		    if (isset($new['passport_date'])) {
			    $new['passport_date'] = date('Y-m-d',strtotime($new['passport_date']));
		    }
		  }  
		  if ($step==4) {
		  	if (!isset($new['forts'])) {
		      $new['forts'] = null;
		    }
		  	if (!isset($new['special_account'])) {
		      $new['special_account'] = null;
		    }
		    if (!isset($new['orders_in_office'])) {
		      $new['orders_in_office'] = null;
		    }
		  	if (!isset($new['orders_by_mail'])) {
		      $new['orders_by_mail'] = null;
		    }
		  	if (!isset($new['orders_by_phone'])) {
		      $new['orders_by_phone'] = null;
		    }
		  	if (!isset($new['orders_by_email'])) {
		      $new['orders_by_email'] = null;
		    }
		    if (!isset($new['agree'])) {
		      $new['agree'] = null;
		    }
		  }  
		}  
		  
		
		$application_form_id = isset($new['application_form_id'])?$new['application_form_id']:false;
		if (!$application_form_id) {
			
			if (isset($_SESSION['application_form_id'])) {
			  $application_form_id = $_SESSION['application_form_id'];
			} else {
			  $application_form_id = $this->global_model->get_unique_id();
			  $_SESSION['application_form_id'] = $application_form_id; 
			}   
		}
		
		$exists = false;
		$old = array();
		if ($application_form_id) {
		  $old = $this->global_model->get_record($table,null,array('application_form_id'=>$application_form_id));
		  $exists = is_array($old) && (sizeOf($old)>0); 
		}    
		
		$error = false;
		$message = null;
		$success = true;
		
		if ($new && !$back) {
 	    foreach($new as $k=>$v) {
			  $d = $desc[$k];
			  if (isset($d)) {
				  if ((!$d->null) && is_null($v)) {
					  $success = false;
					  $next = false;
					  $data = $new;
					  $name = $k;
					  if (trim($d->comment)!='') {
					    $name = $d->comment;
					  }
					  $message = sprintf(dictionary('Поле %s не заполнено.'),dictionary($name));
					  break;
				  }
			  }
		  } 
		}
		
		if ($success) {
		  
		  if ($exists) {
		    
		  	if ($new) {
		  	  if ($step==4) {
		  	    $new['finished'] = date('Y-m-d H:i:s');
		  	  }
			    $success = $this->global_model->update('application_forms',$new,
				    	                                   array('application_form_id'=>$application_form_id),
					                                       array('application_form_id'));
		  	}  
		  } else {
			  if ($next) {
  			  $new['application_form_id'] = $application_form_id;
	  		  $new['ip'] = $ip; 
		  	  $new['agent'] = $agent;
			    $new['lang'] = $lang;
			    $new['num'] = $this->get_num($application_form_id);
  			  $success = $this->global_model->insert($table,$new);
	  		}
  		}
		}	
			
		if (!$success) {
		  if (!isset($message)) {
				$message = $this->db->last_error();
		  }	
			$error = true;
		} else {
		
			$res = $this->global_model->get_record($table,null,array('application_form_id'=>$application_form_id));
			if ($res) {
				foreach($res as $k=>$v) {
					$data[$k] = $v;
				}
			}
			
			if ($next) {
				
				if (($step==1)||($step==4)) {

					$emails = array();
					$emails[] = 'tsv@ufs-federation.com';
					$emails[] = 'imv@ufs-federation.com';
					$emails[] = 'clientservice@ufs-federation.com';
					$emails[] = 'dbd@ufs-federation.com';
					//$emails[] = 'hav@ufs-federation.com';
					
					$fields = array();
					$subject = '';
					if ($step==1) {
					  $fields = array('num','created','lang','last_name','first_name','middle_name','email','phone','birth_date','birth_place',
					                  'inn','citizen','passport_number','passport_authority','passport_date','passport_code');
					  
					  $subject = sprintf('Потенциальный клиент%s начал заполнять анкету%s',
					                     (isset($data['first_name'])?' '.$data['first_name']:''),
					                     (isset($data['num'])?' #'.$data['num']:''));
					} else {
					   foreach($data as $k=>$v) {
					     $fields[] = $k;
					   }
					   $subject = sprintf('Потенциальный клиент%s заполнил анкету%s',
					 		                  (isset($data['first_name'])?' '.$data['first_name']:''),
					 		                  (isset($data['num'])?' #'.$data['num']:''));
					}

					$body = '<h3>'.$subject.'</h3>'."\n";
					$counter = 0;
					foreach($fields as $k) {
					  $d = $desc[$k];
					  if (isset($d)) {
					    $v = $data[$k];
					    if (trim($d->comment)!='') {
					      $name = $d->comment;
					      if (!is_null($v)) {
					        $style = 'background: #A9F5A9; margin-bottom: 2px; ';
					      } else {
					        $style = 'background: #F5BCA9; margin-bottom: 2px; ';
					      }
					      $body.= '<span style="'.$style.'">'.dictionary($name).': <b>'.$v.'</b></span><br>'."\n";
					      $counter++;
					    }
					  }
					}
					
					$this->send_emails($emails,$subject,$body);
				}
				
				$step++;
				
			} elseif ($back) {
			  $step--;
			}  
		}
		
		if (sizeOf($data)>0) {
		  switch ($step) {
			  case 1: {
				  if (isset($data['birth_date'])) {
				    $data['birth_date'] = date('d.m.Y',strtotime($data['birth_date']));
				  }
				  if (isset($data['passport_date'])) {  

				  	$data['passport_date'] = date('d.m.Y',strtotime($data['passport_date']));
				  }  
				  break;
			  }
			  case 2: {
				  $regions = $this->global_model->get_query_data('select t.* from (select region_id, name, def, '.
                                                         'case when priority is null then 99999 else priority end as priority '.
                                                         'from regions) t order by t.priority, t.name');
				  $regions = $this->global_model->data_to_class($regions);
				  $data['residence_regions'] = $regions;
				  $data['post_regions'] = $regions;
				  break;
			  }
			  case 3: {
				  if (!isset($data['bank_recipient'])) {
					  $data['bank_recipient'] = trim(sprintf('%s%s%s',
						  	                                   (isset($data['last_name']))?$data['last_name']:'',
							                                     (isset($data['first_name']))?' '.$data['first_name']:'',
							                                     (isset($data['middle_name']))?' '.$data['middle_name']:''));
				  }
				  $regions = $this->global_model->get_query_data('select t.* from (select region_id, name, def, '.
                                                         'case when priority is null then 99999 else priority end as priority '.
                                                         'from regions) t order by t.priority, t.name');
				  $regions = $this->global_model->data_to_class($regions);
				  $data['bank_regions'] = $regions;
				  break;
		    }
		  }  
		}
		
		$data['error'] = $error;
		$data['message'] = $message;
		$data['application_form_id'] = $application_form_id;
		$data['step'] = $step;
		
		return $this->load->view('view_pages_application_form',$data,true);
	}
    
}
?>
