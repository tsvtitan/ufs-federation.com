<?php

define ('WORK_OR_TEST',true); // !!+!!

set_include_path(':/var/www/ufs-federation.com/docs/system:'.get_include_path());

require_once $_SERVER['DOCUMENT_ROOT'].'/system/libraries/strutils.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/system/libraries/simple_html_dom.php';

require_once $GLOBALS['application_folder'].'/libraries/MessageGate.php';
require_once $GLOBALS['application_folder'].'/libraries/Service.php';

class backoffice_analytics_reviews_model extends Model{

  function backoffice_analytics_reviews_model()
  {
    parent::Model();
        
        if(isset($_REQUEST['form_search']))
        {
            $_SESSION['search']=mysql_string($_REQUEST['search']);
        } else {
          if(!isset($_SESSION['search'])){
            $_SESSION['search']='';
          }
        }
        $this->load->model('maindb_model');
  }
    
  
  function pcgbasename($param, $suffix=null) {
    
    if ( $suffix ) {
      $tmpstr = ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);
      if ( (strpos($param, $suffix)+strlen($suffix) )  ==  strlen($param) ) {
      return str_ireplace( $suffix, '', $tmpstr);
      } else {
      return ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);
      }
    } else {
      return ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);
    }
  }
  
  function load_local_file($file) {
    
    $ret = false;
    if (file_exists($file)) {
      $ret = @file_get_contents($file);
    }
    return $ret;    
  }

  function load_remote_file($file) {
  
    $ret = false;
    $handle = @fopen($file,"r");
    if ($handle) {
      $ret = '';
      while (!feof($handle)) {
        $ret.=@fread($handle,4096);
      }
      @fclose($handle);
    }
    return $ret;
  }
  
  function get_hash($email,$lang) {
  
    $ret = $lang.':'.$email;
    return md5($ret);
  }
  
  function http_exists($url) {
    $ret = false;
    $s = strtolower(substr($url,0,strlen('http')));
    if ($s=='http') {
      $ret = true;
    }
    return $ret; 
  }
  
  function get_image_url($image,$host) {
    
    $ret = 'cid:'.$image['name'].'@'.$host;
    if (!$image['internal']) {
      if ($this->http_exists($image['url'])) {
        $ret = $image['url'];
      } else {
        $ret = sprintf('http://%s/%s',$host,$image['url']);
      }        
    }
    return $ret;
  }
  
  function replace_image_links($internal,$body,&$images,$host) {
    
    $ret = $body;
    
    $html = str_get_html($body);
    if ($html) {
      
      foreach($html->find('img') as $img) {
        
        $src = trim($img->src);
        if (trim($src)!='') {

          $type = 'image/jpg';
          $ext = pathinfo($src,PATHINFO_EXTENSION);
          $s = $ext;
          if (trim($s)!='') {
            $type = 'image/'.$s;
            $s = '.'.$s;
          }
          $name = md5(date('r', time())).$s;
          if ($this->http_exists($src)) {
            $url = $src;
          } else {
            $url = sprintf('http://%s/%s',$host,$src);
          }
          
          $data = $this->load_remote_file($url);
          if ($data) {
              
            $image = array('name'=>$name,'type'=>$type,
                           'data'=>$data,'internal'=>$internal,
                           'url'=>$src);
            $images[] = $image;
            $url = $this->get_image_url($image,$host);
            $ret = str_replace($src,$url,$ret);
          }  
        }
      }
    }
    
    return $ret;
  }
  
  function get_body($lang,$host,$date,$page,$title,$subject,$body,$extra_body,$unsubscribe_link,
                    $use_template=1,$company='UFS',$mailing_url=null) {
  
    $internal_images = false;
    $image_header = false;
    $image_footer = false;
    
    $images = array();
    
    if($use_template == 1) {
      
      $url = '/images/mailing/'.strtolower($company).'/header.jpg'; 
      
      $image_header = $this->load_local_file($_SERVER['DOCUMENT_ROOT'].$url);
      if ($image_header) {
        $images[] = array('name'=>'header.jpg','type'=>'image/jpg',
                          'data'=>$image_header,'internal'=>$internal_images,
                          'url'=>$url);
      }
      
      $url = '/images/mailing/'.strtolower($company).'/footer.jpg';
      
      $image_footer = $this->load_local_file($_SERVER['DOCUMENT_ROOT'].$url);
      if ($image_footer) {
        $images[] = array('name'=>'footer.jpg','type'=>'image/jpg',
                          'data'=>$image_footer,'internal'=>$internal_images,
                          'url'=>$url);
      }
      
    }
    
    $ret ='<html>'."\n";
    $ret.='<head>'."\n";
    $ret.='<meta charset="utf-8">'."\n";
    $ret.='<meta http-equiv=Content-Type content="text/html; charset=utf-8">'."\n";
    $ret.='</head>'."\n";
    
    if ($use_template == 1) {
      
      $ret.='<body width=1024>'."\n";
      $ret.='<table width=1024 cellspacing=0 cellpadding=0 border=0 style="display:block;max-width:1024px">'."\n";
      if ($image_header) {
        $ret.='<tr><td><img src="'.$this->get_image_url($images[0],$host).'"></td></tr>'."\n";
      }
      if ($title != '')
      {
        $ret.='<tr><td style="padding-left:10px;padding-bottom:10px">';

        $s = '';
        if (trim($page)!='') {
          $s = '<span style="color:silver;"> / </span><span>'.$page.'</span>';
        }
        
        if (trim($mailing_url)!='') {
          $ret.='<h3><span>'.$date.'</span><span style="color:silver;"> / </span><span><a href="'.$mailing_url.'">'.$title.'</a></span>'."\n";
        } else {
          $ret.='<h3><span>'.$date.'</span>'.$s.'<span style="color:silver;"> / </span><span>'.$title.'</span></h3></td></tr>'."\n";
        }
        
        if (trim($subject)!='') {
          $ret.='<tr><td style="padding-left:10px; padding-bottom:10px; font-style:italic; font-weight:bold;"><p style="margin-top:0px;">'.$subject.'</p></td></tr>'."\n";
        }
      }
      $ret.='<tr><td><table width=100% cellspacing=0 cellpadding=0 border=0>'."\n";
    
      $s = (trim($body)!='') ? '<br>' : '';
      $s = (trim($extra_body)!='') ? $s.$extra_body : '';
      $ret.='<tr><td style="padding-left:20px">'.$this->replace_image_links($internal_images,$body.$s,$images,$host).'</td></tr>'."\n";

      $ret.='</table></td></tr>'."\n";
      
      if ($company=='UFS') {
        
        $unsubscribe = 'В случае, если подписка была оформлена не Вами или Вы не желаете получать письма по ней, перейдите по этой ссылке';
        $regards = 'С уважением,<br>UFS Investment Company';

        if ($lang=='en') {
          $unsubscribe = 'If it were not you who made the subscription and you do not want to receive letters on it, click the link';
          $regards = 'Best regards,<br>UFS Investment Company';
        } elseif ($lang=='de') {
          $unsubscribe = 'Falls jemand anderer die Suskription gemacht hat und Sie sie nicht erhalten wollen, klicken auf den Link';
          $regards = 'Best regards,<br>UFS Investment Company';
        }
        
        $ret.='<tr><td style="padding-top:10px"><hr></td></tr><br><br><br>';
        $ret.='<tr><td style="padding-left:10px">'.$regards.'</td></tr>'."\n<br>";
        //$ret.='<tr><td style="padding-left:10px"><a style="font-size:12px" href="http://'.$_SERVER['HTTP_HOST'].'/subscribe.html?unsubscribe"><br>'.$unsubscribe.'</a></td></tr>';
        $ret.='<tr><td style="padding-left:10px"><a style="font-size:12px" href="'.$unsubscribe_link.'"><br>'.$unsubscribe.'</a></td></tr>';
      
        
      } else {
        
        /*$unsubscribe = 'В случае, если подписка была оформлена не Вами или Вы не желаете получать письма по ней, перейдите по этой ссылке';
        $regards = 'С уважением,<br>Premier Royal Investment Company';

        if ($lang=='en') {
          $unsubscribe = 'If it were not you who made the subscription and you do not want to receive letters on it, click the link';
          $regards = 'Best regards,<br>Premier Royal Investment Company';
        } elseif ($lang=='de') {
          $unsubscribe = 'Falls jemand anderer die Suskription gemacht hat und Sie sie nicht erhalten wollen, klicken auf den Link';
          $regards = 'Best regards,<br>Premier Royal Investment Company';
        }
        
        $ret.='<tr><td style="padding-top:10px"><hr></td></tr><br><br><br>';
        $ret.='<tr><td style="padding-left:10px">'.$regards.'</td></tr>'."\n<br>";
        
        $ulink = $unsubscribe_link;//.'='+*/
        
        $ret.='<tr><td style="padding-top:20px"></td></tr>';
        $ret.='<tr><td style="padding-left:10px" nowrap style="font-size:14px"><b>Полная версия обзора на сайте</b>&nbsp;<a href="http://www.premier–broker.ru">http://www.premier–broker.ru</a>&nbsp;<b>и в мобильном приложении P-Research</b></td></tr>';
      }
      
      if ($image_footer) {
        $ret.='<tr><td td style="padding-top:10px"><img src="'.$this->get_image_url($images[1],$host).'"></td></tr>'."\n";
      }  
      $ret.='</table>'."\n";
      $ret.='</body>'."\n";
    } else {
      $ret.=$extra_body;
    }
    $ret.='</html>';
    
    return $ret;
  }

  private function set_service_not_found() {
    $this->global_model->set_message($this->lang->line('admin_service_not_found'));
  }
  
  private function set_service_cannot_send() {
    $this->global_model->set_message($this->lang->line('admin_service_cannot_send'));
  }

  private function set_service_error($error) {
    $this->global_model->set_message($this->lang->line('admin_service_error').' '.$error);
  }

  private function set_service_cannot_cancel() {
    $this->global_model->set_message($this->lang->line('admin_service_cannot_cancel'));
  }
  
  private function set_service_cannot_accelerate() {
    $this->global_model->set_message($this->lang->line('admin_service_cannot_accelerate'));
  }
  
  function send_message($review_id,$subscribers,$delay=null,$duration=null,$prefix=null,$test=true) {
    
    $ret = false;
    
    if (isset($review_id) && is_array($subscribers)) {
      
      $service = new service\Service();
      if ($service->connect()) {

        $review = array();
        $page = '';

        $sql = $this->db->query(sprintf('select * from analytics_reviews where id=%d',$review_id));
        if ($sql->num_rows()>0) {

          $review = $sql->result();
          $review = $review[0];

          $mailing = new service\Mailing();

          $sql = $this->db->query(sprintf('select p1.name as page_name 
                                             from analytics_reviews_pages arp
                                             join pages p on p.id=arp.page_id  
                                             left join pages p1 on p1.id=p.parent_id
                                            where arp.analitic_review_id=%d',
                                            $review_id));
          if ($sql->num_rows()>0) {
            $arr = $sql->result();
            $page = trim_utf8($arr[0]->page_name); 
          }

          $sql = $this->db->query(sprintf("select * from analytics_reviews_files where debt_market_id=%d",$review_id));
          if ($sql->num_rows()>0) {

            $rfiles = $sql->result(); 

            $names = array();
            $count = 1;
            foreach ($rfiles as $rf) {

              $name = sprintf('%s-%s-%s',date('Ymd',strtotime($review->timestamp)),$page,$review->name); 

              $ext = pathinfo($rf->_file,PATHINFO_EXTENSION);
              while (true) { 
                $found = in_array($name,$names);
                if (!$found) {
                  break;
                } else {
                  $name[] = $name;
                  $name = $name.$count;
                  $count++;
                }
              }
              
              $data = $this->load_local_file($_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$rf->_file);
              if ($data) {
                $mailing->addAttachment($name,$ext,$data,'application/'.$ext);
              }  
            }
          }

          if (is_null($prefix)) {
            
            $sql = $this->db->query(sprintf('select ak.name 
                                               from analytics_reviews_keywords ark
                                               left join analytics_keywords ak on ak.keyword_id=ark.keyword_id
                                              where ark.review_id=%d
                                              order by ark.priority',$review_id));
            if ($sql->num_rows()>0) {

              $keywords = $sql->result();
              foreach ($keywords as $keyword) {
                $mailing->addKeyword($keyword->name);
              }
            }
          }
        }

        if (sizeof($review)>0) {

          $host = $_SERVER['HTTP_HOST'];
          $arr = explode('.',$host);
          $l = sizeOf($arr);
          if ($l>1) {
            $host = $review->lang.'.'.$arr[$l-2].'.'.$arr[$l-1];
          }

          $date = date('d.m.Y',strtotime($review->timestamp));
          $rname = $review->name;
          $rsubject = trim($review->subject);

          $subject = sprintf('%s%s%s%s',
                             $date,
                             (($rname!='')?' / '.$rname:''),
                             (($rsubject!='')?' / '.$rsubject:''),
                             (($page!='')?' / '.$page:''));
          
          $senderEmail = $review->company=='UFS'?'mailer@lists.ufs-financial.ch':'mailer@lists.premier-broker.ru';
          $senderName = $review->company=='UFS'?'UFS IC Research':'Premier Research';
                  
          $sender = $mailing->setSender($senderEmail,$senderName); 

          foreach ($subscribers as $subscriber) {
            $mailing->addRecipient($subscriber->email,$subscriber->name,$subscriber->priority);
          }
          
          if (is_null($prefix)) {
            $mailing->subject = $subject;
          } else {
            $mailing->subject = sprintf('%s%s',$prefix,$subject);
          }
          
          $unsubscribe_link = '';
          if ($review->company=='UFS') {
            $unsubscribe_link = sprintf('http://%s/subscribe.html?unsubscribe',$host);
          } else {
            $unsubscribe_link = 'http://www.premier-broker.ru/subscribe.php?unsubscribe';
          }
                  
          $mailing->body = $this->get_body($review->lang,$host,$date,$page,$review->name,$review->subject,
                                           $review->short_content,$review->extra_mailing_text,
                                           $unsubscribe_link,$review->use_template,$review->company,
                                           $review->mailing_url);

          $mailing->delay = $delay;
          $mailing->duration = $duration; 
          $mailing->priority = 10;
          $mailing->test = $test;
          
          $mailing->pattern = $review->company=='UFS'?'EmailUFSResearchPattern':'EmailPremierResearchPattern';
          
          $mailing->channel = $review->company=='UFS'?'UFSExchangeOutgoing':'PremierExchangeOutgoing';

          $replyEmail = $review->company=='UFS'?'research@ufs-federation.com':'research@premier-broker.ru';
          
          $mailing->addHeader('Reply-To',$sender->name.' <'.$replyEmail.'>');
          $mailing->addHeader('List-Unsubscribe',sprintf('<%s>',$unsubscribe_link));
          
          $r = $service->sendMailing($mailing);
          if ($r) {
            
            if (!$r->error) {
              
              $ret = $r;
              
            } else {
              $this->set_service_error($r->error);
            }
            
          } else {
            $this->set_service_cannot_send();
          }
        }  
      } else {
        $this->set_service_not_found();
      } 
    }
    return $ret;
  }
  
  private function make_subscriber($email,$name=null,$priority=null) {
    
    $ret = new stdClass();
    $ret->email = $email;
    $ret->name = $name;
    $ret->priority = $priority;
    return $ret;
  }
  
  function test() {
 
    $review_id = (int)$this->uri->segment(4);

    $subscribers = array(); 

    $subscribers[] = $this->make_subscriber('tsv@ufs-financial.ch');
    
    
    if (WORK_OR_TEST) {
      $subscribers[] = $this->make_subscriber('sbscrb@ufs-federation.com');
      //$subscribers[] = $this->make_subscriber('vpa@ufsic.com');
      $subscribers[] = $this->make_subscriber('tsv@ufsic.com');
      $subscribers[] = $this->make_subscriber('tua@ufsic.com');
    } else {
      //$subscribers[] = $this->make_subscriber('zrv@ufs-financial.ch');
    }
            
    $this->send_message($review_id,$subscribers,0,5,'TEST: ',true);
    
    return redirect($this->uri->segment(1).'/'.$this->page_name);
  } 
  
  private function try_mailing($review_id,$minutes=5) {
    
    $ret = false;

    $subscribers = array();
    
    if (WORK_OR_TEST) {
      $sql = $this->db->query(sprintf('select distinct(email) as email, name, priority
                                         from mailing_subscriptions 
                                        where mailing_section_id in (select mailing_section_id 
                                                                       from analytics_reviews_sections 
                                                                      where analytics_review_id=%d)
                                          and started<=current_timestamp and finished is null
                                        order by priority, started',
                                      $review_id)); 
      if ($sql->num_rows()>0) {
        $subscribers = $sql->result();
      }
    } else {
      
      
      for ($i=0;$i<10;$i++) { 
        $subscribers[] = $this->make_subscriber('test7@ufs-gold.com');
      }
    }
    
    $time = strtotime(sprintf('+%d minutes',$minutes),time());
    $begin = date(GATE_DATETIME_FMT,$time);
    $end = date(GATE_DATETIME_FMT,strtotime('+30 minutes',$time));

    $r = $this->send_message($review_id,$subscribers,$minutes*60,30,null,false);
    if ($r && $r->messageId && $r->mailingId) {

      $ret = $this->db->query(sprintf('insert into analytics_reviews_messages (analytics_review_id,message_id,mailing_id,begin,end,all_count) values (%s,%s,%s,%s,%s,%s);',
                                      $review_id,"'".$r->messageId."'","'".$r->mailingId."'","'".$begin."'","'".$end."'",$r->allCount));
    }
    return $ret;
  }
  
  function make() {
    
    $id = (int)$this->uri->segment(4);
    
    $this->try_mailing($id);
    
    return redirect($this->uri->segment(1).'/'.$this->page_name);
  }

  function send() {
    
    $review_id = (int)$this->uri->segment(4);
    
    $sql = $this->db->query(sprintf('select message_id, mailing_id
                                       from analytics_reviews_messages
                                      where analytics_review_id=%s
                                        and begin is not null
                                        and begin>current_timestamp
                                        and all_count>0
                                        and sent_count<(all_count-error_count)
                                        and created>=adddate(current_timestamp, interval -1 day)
                                        and mailing_id is not null
                                        and message_id is not null
                                        and canceled is null
                                        and interrupted is null
                                      order by created desc',
                                    $review_id));
    if ($sql->num_rows()>0) {
      
      $messages = $sql->result();
      
      $service = new service\Service();
      if ($service->connect()) {

        $flag = true;

        foreach ($messages as $message) {

          $r = $service->accelerate($message->mailing_id);
          if ($r && $r->accelerated) {

            $this->db->query(sprintf('update analytics_reviews_messages
                                         set begin=current_timestamp
                                       where analytics_review_id=%s
                                         and message_id=%s
                                         and mailing_id=%s;',
                                     $review_id,"'".$message->message_id."'","'".$message->mailing_id."'"));
          } else {
            $flag = false;
          }
        }

        if (!$flag) {
          $this->set_service_cannot_accelerate();
        }

      } else {
        $this->set_service_not_found();
      }
    }
    
    return redirect($this->uri->segment(1).'/'.$this->page_name);
  }
  
  private function try_cancel($review_id,$field) {
    
    if (isset($review_id)) {
      
      $sql = $this->db->query(sprintf('select message_id, mailing_id
                                         from analytics_reviews_messages
                                        where analytics_review_id=%s
                                          and all_count>0
                                          and sent_count<(all_count-error_count)
                                          and created>=adddate(current_timestamp, interval -1 day)
                                          and mailing_id is not null
                                          and message_id is not null
                                          and canceled is null
                                          and interrupted is null
                                        order by created desc',
                                      $review_id));
      if ($sql->num_rows()>0) {

        $messages = $sql->result();

        $service = new service\Service();
        if ($service->connect()) {
          
          $flag = true;
          
          foreach ($messages as $message) {
            
            $r = $service->cancel($message->mailing_id);
            if ($r && $r->canceled) {

              $this->db->query(sprintf('update analytics_reviews_messages
                                           set %s=current_timestamp
                                         where analytics_review_id=%s
                                           and message_id=%s
                                           and mailing_id=%s;',
                                       $field,$review_id,
                                       "'".$message->message_id."'","'".$message->mailing_id."'"));
            } else {
              $flag = false;
            }
          }
          
          if (!$flag) {
            $this->set_service_cannot_cancel();
          }
          
        } else {
          $this->set_service_not_found();
        }
      }
    }
  }
  
  
  function cancel() {
  
    $id = (int)$this->uri->segment(4);

    $this->try_cancel($id,'canceled');
    
    return redirect($this->uri->segment(1).'/'.$this->page_name);
  }
  
  function interrupt() {

    $id = (int)$this->uri->segment(4);

    $this->try_cancel($id,'interrupted');
    
    return redirect($this->uri->segment(1).'/'.$this->page_name);
  }
  
  function add() {

    $company = $this->uri->segment(4);
    $company = trim($company)==''?'UFS':$company;
    
    $cat_id_analitics = 8;
    $analitica_page  = $this->maindb_model->select_table('pages_menu','sub_page_type = "analytics" and lang="'.$this->site_lang.'" ','name',1,true,'itembg',array('name'),'','',true);
    if(!empty($analitica_page))$cat_id_analitics = $analitica_page->id;

    $content['sub_menu'] = $this->sub_menu($cat_id_analitics,array(),$company);
    $content['groups']= '';
    $content['sections']= '';
    $content['keywords']= '';

    $ret=$this->db->query('select * from analytics_reviews_groups where lang="'.$this->site_lang.'" order by level, priority');
    if($ret->num_rows()>0){

      $res=$ret->result();

      $res=$this->global_model->make_tree($res,'group_id');
      $res=$this->global_model->tree_list($res);
      $this->global_model->add_name($res,'select');

      $content['groups']=$res;
    }

    $ret=$this->db->query(sprintf('select * from mailing_sections where lang="%s" and company="%s" order by level, priority',
                                  $this->site_lang,$company));
    if($ret->num_rows()>0){

      $res=$ret->result();

      $res=$this->global_model->make_tree($res,'mailing_section_id');
      $res=$this->global_model->tree_list($res);
      $this->global_model->add_name($res,'select');

      $content['sections']=$res;
    }


      if(isset($_REQUEST['submit'])){

          $ret=$this->global_model->adjustment_of_request_array($_REQUEST);
          $next_id=$this->global_model->Auto_inc($this->page_name);

          $ret['url']=$this->global_model->SET_title_url($_REQUEST['clear']['name'],$this->page_name);
          $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);
          $ret['lang']=$this->site_lang;

          if ($ret['group_id']=='') {
            $ret['group_id']=NULL;
          }

          if (!isset($ret['only_mailing']) || ($ret['only_mailing']=='')) {
            $ret['only_mailing'] = NULL;
          } else {
            $ret['only_mailing'] = 1;
          }

          $keywords = $ret['keywords'];
          unset($ret['keywords']);
          
          $this->db->insert($this->page_name,$ret);

          $curr_id = $this->maindb_model->_get_curr_id_table($this->page_name);
          if(isset($_REQUEST['display_pg'])){
            $this->db->query('delete from analytics_reviews_pages where analitic_review_id="'.$curr_id.'"');
            foreach($_REQUEST['display_pg'] as $val) {
              $this->db->query('insert into analytics_reviews_pages (`analitic_review_id`,`page_id`,`lang`) values ("'.$curr_id.'","'.$val.'","'.$this->site_lang.'");');
            }
          }

          if (isset($_REQUEST['sections'])) {
            $this->db->query('delete from analytics_reviews_sections where analytics_review_id="'.$curr_id.'";');
            foreach ($_REQUEST['sections'] as $val) {
              $this->db->query(sprintf('insert into analytics_reviews_sections (analytics_review_id,mailing_section_id) values (%d,%d);',$curr_id,$val));
            }
          }

          $keywords = explode(',',$keywords);
          $list = array();
          foreach($keywords as $k) {
            $k = trim($k);  
            if ($k!='') {
              $sql = sprintf('select keyword_id from analytics_keywords where upper(name)=upper(%s) limit 1','"'.$k.'"');
              $ret = $this->db->query($sql);
              if($ret->num_rows()>0){
                $res = $ret->result();
                $keyword_id = $res[0]->keyword_id;
              } else {
                $sql = sprintf('insert into analytics_keywords (name,lang) values (%s,%s);',
                              '"'.$k.'"','"'.$this->site_lang.'"');
                $this->db->query($sql);
                $keyword_id = $this->maindb_model->_get_curr_id_table('analytics_keywords');
              }
              if (!array_search($keyword_id,$list)) {
                $list[] = $keyword_id;
              }
            }  
          }

          $sql = sprintf('delete from analytics_reviews_keywords where review_id=%d;',$curr_id);
          $this->db->query($sql);
          $priority = 1;
          foreach ($list as $l) {
            $sql = sprintf('insert into analytics_reviews_keywords (review_id,keyword_id,priority) values (%d,%d,%d);',
                          $curr_id,$l,$priority);
            $this->db->query($sql);
            $priority++;  
          }

          return redirect($this->uri->segment(1).'/'.$this->page_name);

      } else {

        $content['date']=$this->global_model->date_arr('',2,2011);
        
        $data->company = $company;
        $content['data'] = $data;

        return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
      }
  }



  function edit() {

    $company = $this->uri->segment(5);
    $companyNotExists = trim($company)=='';
    $company = $companyNotExists?'UFS':$company;
    
    $cat_id_analitics = 8;
    $analitica_page                 = $this->maindb_model->select_table('pages_menu','sub_page_type = "analytics" and lang="'.$this->site_lang.'" ','name',1,true,'itembg',array('name'),'','',true);
    if(!empty($analitica_page))$cat_id_analitics = $analitica_page->id;

    

      if(isset($_REQUEST['submit'])){

          $ret=$this->global_model->adjustment_of_request_array($_REQUEST);  

          $ret['url']=$this->global_model->SET_title_url($_REQUEST['clear']['name'],$this->page_name,(int)$_REQUEST['id']);
          $ret['timestamp']=$this->global_model->date_arr_to_timstamp($_REQUEST['date']);

          if ($ret['group_id']=='') {
            $ret['group_id']=NULL;
          }

          if (!isset($ret['only_mailing']) || ($ret['only_mailing']=='')) {
            $ret['only_mailing'] = NULL;
          } else {
            $ret['only_mailing'] = 1;
          }

          $keywords = $ret['keywords'];
          unset($ret['keywords']);

          $curr_id = (int)$_REQUEST['id'];

          $sql_set=$this->global_model->create_set_sql_string($ret);                

          $this->db->query('update `'.$this->page_name.'` set 
                                  '.$sql_set.' 
                                where `id`="'.$curr_id.'"
                                and `lang`="'.$this->site_lang.'";');

          $_SESSION['admin']->is_update=1;

          if(isset($_REQUEST['display_pg'])){
            $this->db->query('delete from analytics_reviews_pages where analitic_review_id="'.$curr_id.'"');
            foreach($_REQUEST['display_pg'] as $val){
              $this->db->query('insert into analytics_reviews_pages (`analitic_review_id`,`page_id`,`lang`) values ("'.$curr_id.'","'.$val.'","'.$this->site_lang.'");');
            }
          }

          $this->db->query('delete from analytics_reviews_sections where analytics_review_id="'.$curr_id.'";');
          if (isset($_REQUEST['sections'])) {
            foreach ($_REQUEST['sections'] as $val) {
             $this->db->query(sprintf('insert into analytics_reviews_sections (analytics_review_id,mailing_section_id) values (%d,%d);',$curr_id,$val));
            }
          }  

          $keywords = explode(',',$keywords);
          $list = array();
          foreach($keywords as $k) {
            $k = trim($k);
            if ($k!='') {
              $sql = sprintf('select keyword_id from analytics_keywords where upper(name)=upper(%s) limit 1','"'.$k.'"');
              $ret = $this->db->query($sql);
              if($ret->num_rows()>0){
                $res = $ret->result();
                $keyword_id = $res[0]->keyword_id;
              } else {
                $sql = sprintf('insert into analytics_keywords (name,lang) values (%s,%s);',
                               '"'.$k.'"','"'.$this->site_lang.'"');
                $this->db->query($sql);
                $keyword_id = $this->maindb_model->_get_curr_id_table('analytics_keywords');
              }
              if (!array_search($keyword_id,$list)) {
                $list[] = $keyword_id;
              }
            }
          }

          $sql = sprintf('delete from analytics_reviews_keywords where review_id=%d;',$curr_id);
          $this->db->query($sql);
          $priority = 1;
          foreach ($list as $l) {
            $sql = sprintf('insert into analytics_reviews_keywords (review_id,keyword_id,priority) values (%d,%d,%d);',
                           $curr_id,$l,$priority);
            $this->db->query($sql);
            $priority++;
          }
          
          return redirect($this->uri->segment(1).'/'.$this->page_name);
          
      } else {      

        $id = (int)$this->uri->segment(4);
        $group_id = NULL;

          $sql=$this->db->query('select * from `'.$this->page_name.'` where `id`="'.$id.'" and `lang`="'.$this->site_lang.'";');
          if($sql->num_rows()>0){  
            
             $res=$sql->result();
             $res=$res[0];

             $res=$this->global_model->adjustment_of_results($res,$this->page_name,false,false,array('subject'));
             $res=$this->global_model->date_format($res,'Y|n|j');

             if (is_null($res->only_mailing)) {
                 $res->only_mailing_checked = '';
             } else {
                 $res->only_mailing_checked = 'checked';
             }

             $date=new stdClass();
             list($date->year,$date->month,$date->day)=explode('|',$res->timestamp);

             $group_id = $res->group_id;
             
             $res->company = (is_array($res->company) && sizeof($res->company)>0)?$res->company[0]:$res->company;
             $company = ($companyNotExists)?$res->company:$company;
             $res->company = $company;

             $content['date']=$this->global_model->date_arr($date,2,2011);   
             $content['data']=$res;

           } else {
             $content['date']=$this->global_model->date_arr('',2,2011);
             $content['data']='';
           }

           $content['groups'] = '';

           $ret=$this->db->query('select * from analytics_reviews_groups where lang="'.$this->site_lang.'" order by level, priority');
           if($ret->num_rows()>0) {

              $res=$ret->result();

              $res=$this->global_model->make_tree($res,'group_id');
              $res=$this->global_model->tree_list($res);
              $this->global_model->add_name($res,'select');

              foreach ($res as $r) {
                if ($r->group_id==$group_id) {
                   $r->select = 'selected';
                  break;
                 }
              }

              $content['groups'] = $res;
           }


           $section_ids = array();

           $ret=$this->db->query('select mailing_section_id from analytics_reviews_sections where analytics_review_id="'.$id.'";');
           if($ret->num_rows()>0){
              $res=$ret->result();
              foreach ($res as $r) {
                 $section_ids[] = $r->mailing_section_id;  
              }
           }   

           $content['sections'] = '';
           
           $ret=$this->db->query(sprintf('select * from mailing_sections where lang="%s" and company="%s" order by level, priority',
                                         $this->site_lang,$company));
           if($ret->num_rows()>0){

              $res=$ret->result();

              $res=$this->global_model->make_tree($res,'mailing_section_id');
              $res=$this->global_model->tree_list($res);
              $this->global_model->add_name($res,'select');

              foreach ($res as $r) {
                $found = in_array($r->mailing_section_id,$section_ids);
                if ($found) {
                  $r->select = 'selected';
                }
              }

              $content['sections']=$res;
           }


           $content['keywords'] = '';

           $ret=$this->db->query(sprintf('select ak.name '. 
                                         'from analytics_reviews_keywords ark '.
                                         'join analytics_keywords ak on ak.keyword_id=ark.keyword_id '.
                                         'where ark.review_id=%d '.
                                         'order by ark.priority',$id));
           if($ret->num_rows()>0) {
              $res = $ret->result();
              $list = array();
              foreach ($res as $r) {
                $list[] = $r->name; 
              }
              $content['keywords'] = implode(', ',$list);
            }
            
          $content['sub_menu'] = $this->sub_menu($cat_id_analitics,$this->getSelectedArr((int)$this->uri->segment(4)),$company);

          return $this->load->view('backoffice_'.$this->page_name.'_edit',$content,true);
      }
  }    


  function del()
  {        
    
    $files    = $this->maindb_model->select_table('analytics_reviews_files',' `debt_market_id`="'.(int)$this->uri->segment(4).'"','id','',false,'itembg',array('name'));
    if(!empty($files)){
      foreach($files as $item){
                @unlink($_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$item->_file);
                      $this->db->delete('analytics_reviews_files',array('id'=>$item->id));
      }
    } 
    $id = (int)$this->uri->segment(4);

    $this->db->query('delete from analytics_reviews_messages where analytics_review_id="'.$id.'";');
    $this->db->query('delete from analytics_reviews_sections where analytics_review_id="'.$id.'";');
    $this->db->query('delete from `analytics_reviews_pages` where `analitic_review_id`="'.$id.'" and `lang`="'.$this->site_lang.'";');     
    $this->db->query('delete from `analytics_reviews_keywords` where `review_id`="'.$id.'";');
    $this->db->query('delete from `'.$this->page_name.'` where `id`="'.$id.'" and `lang`="'.$this->site_lang.'";');

    return redirect($this->uri->segment(1).'/'.$this->page_name);
  }

  function view() {
    
    $content['data']='';

    if(isset($_SESSION['admin']->is_update)) {
      $content['is_update']=$this->lang->line('admin_tpl_page_updated');
      unset($_SESSION['admin']->is_update);
    }

    $where='';

    if(!empty($_SESSION['search'])) {
      $where.=' and `t`.`name` like "%'.$_SESSION['search'].'%"';
    }


    $vals='count(*) as Rows';
    $limit=''; 
    $s = self::view_sql_count_str($vals,$where);

    $sql_r=$this->db->query($s);

    if ($sql_r->num_rows()>0) {

      $res_r=$sql_r->result();
      $res_r=$res_r[0];

      /* pager */ 
      $show=20;
      $rows=$res_r->Rows;
      $ret_page=((int)$this->uri->segment(5)==0)?1:(int)$this->uri->segment(5);
      $param['url']='/view';

      $pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
      /*********/

      $vals='`t`.`id`, `t`.`name`, `t`.`timestamp`, `t`.`only_mailing`, `t`.`company`, t.mailing_url ';
      $limit='limit '.$pages['start'].','.$pages['show']; 
      $s = self::view_sql_str($vals,$where,$limit);

      $sql=$this->db->query($s);       

      if ($sql->num_rows()>0) {  

        $res = $sql->result();  
        
        /*********************************/
         $res = $this->global_model->adjustment_of_results($res,$this->page_name);
         $res = $this->global_model->rand_css_class($res);
         $res = $this->global_model->date_format($res);
        /*********************************/

        $content['pages']=$pages['html'];
        $content['data']=$res;
      }                
    }
    return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
  }

  private function view_sql_str($vals,$where,$limit) {

    $sql='select '.$vals.',
                 section_count, subscriber_count, 
                 sent_count, delivered_count, error_count,
                 all_count, keyword_count,
                 timestampdiff(second,current_timestamp,t.begin) as time_diff
            
            from (select rw.*,
                         case when t1.section_count is null then 0 else t1.section_count end as section_count, 
                         case when t2.subscriber_count is null then 0 else t2.subscriber_count end as subscriber_count,
                         case when t3.sent_count is null then 0 else t3.sent_count end as sent_count,
                         case when t3.delivered_count is null then 0 else t3.delivered_count end as delivered_count,
                         case when t3.error_count is null then 0 else t3.error_count end as error_count,
                         case when t3.all_count is null then 0 else t3.all_count end as all_count,
                         case when t6.keyword_count is null then 0 else t6.keyword_count end as keyword_count,
                         t3.begin
                    from analytics_reviews rw
                    
                    left join (select analytics_review_id, count(*) as section_count
                                 from analytics_reviews_sections
                                group by 1) t1 on t1.analytics_review_id=rw.id            
                    
                    left join (select ar.id, count(*) as subscriber_count
                                 from analytics_reviews ar
                                 join analytics_reviews_sections ars on ars.analytics_review_id=ar.id
                                 join (select mailing_section_id
                                         from mailing_subscriptions
                                        where started<=current_timestamp and finished is null  
                                        group by 1) ms on ms.mailing_section_id=ars.mailing_section_id
                                where ar.timestamp>=date_add(current_timestamp, interval -7 month) 
                                group by 1) t2 on t2.id=rw.id   
                    
                    left join (select analytics_review_id, 
                                      sum(all_count) as all_count,
                                      sum(sent_count) as sent_count,
                                      sum(delivered_count) as delivered_count,
                                      sum(error_count) as error_count,
                                      max(begin) as begin
                                 from analytics_reviews_messages 
                                where canceled is null
                                  and interrupted is null
                                group by 1) t3 on t3.analytics_review_id=rw.id   
                    
                    left join (select review_id, count(*) as keyword_count
                                 from analytics_reviews_keywords
                                group by 1) t6 on t6.review_id=rw.id
                      
                   where rw.lang="'.$this->site_lang.'" 
                     and rw.timestamp>=date_add(current_timestamp, interval -7 month)
                     '.$where.') t   
           order by t.id desc
           '.$limit; 
    return $sql;
  }

  protected function view_sql_count_str($vals,$where) {

    $sql='select '.$vals.'
            from (select rw.id 
                    from analytics_reviews rw
                   where rw.lang="'.$this->site_lang.'"
                     and rw.timestamp>=date_add(current_timestamp, interval -7 month)
                   '.$where.') t';
    return $sql;
  }


  function download() {

    $url=mysql_string($this->uri->segment(4));

    $sql=$this->db->query('select `url`,`_file`
                           from `analytics_reviews_files`
                           where `lang`="'.$this->site_lang.'"
                           and `url`="'.$url.'"
                           limit 1;');

    if($sql->num_rows()>0) {

      $res=$sql->row();

      $filetype=explode('.',$res->_file);
      $filename=$res->url.'.'.$filetype[count($filetype)-1];
      $download_url=$_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$res->_file;

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

  function delfile() {

    $content['the_debt'] = ($this->uri->segment(4)!="")?($this->maindb_model->select_table($this->page_name,' `id`="'.(int)$this->uri->segment(4).'"','id',1,true,'itembg',array('name'))):"";
    if(empty($content['the_debt']))redirect($this->uri->segment(1).'/error');

     $content['data']       = ($this->uri->segment(5)!="")?($this->maindb_model->select_table('analytics_reviews_files',' `id`="'.(int)$this->uri->segment(5).'"','num',1,true,'itembg',array('name'))):"";
    if(empty($content['data']))redirect($this->uri->segment(1).'/error');

    @unlink($_SERVER['DOCUMENT_ROOT'].'/upload/downloads/'.$content['data']->_file);
    $this->db->delete('analytics_reviews_files',array('id'=>(int)$this->uri->segment(5)));
    redirect($this->uri->segment(1).'/'.$this->page_name.'/files/'.$content['the_debt']->id);

  }

  function files() {

    $content['the_debt'] = ($this->uri->segment(4)!="")?($this->maindb_model->select_table($this->page_name,' `id`="'.(int)$this->uri->segment(4).'"','id',1,true,'itembg',array('name'))):"";
    if(empty($content['the_debt']))redirect($this->uri->segment(1).'/error');

    $content['data']    = $this->maindb_model->select_table('analytics_reviews_files',' `debt_market_id`="'.(int)$this->uri->segment(4).'"','id','',false,'itembg',array('name'));

    return $this->load->view('backoffice_'.$this->page_name.'_files',$content,true);
  }


  function editfile() {

    $content['the_debt'] = ($this->uri->segment(4)!="")?($this->maindb_model->select_table($this->page_name,' `id`="'.(int)$this->uri->segment(4).'"','id',1,true,'itembg',array('name'))):"";
    if(empty($content['the_debt']))redirect($this->uri->segment(1).'/error');

    $content['data']       = ($this->uri->segment(5)!="")?($this->maindb_model->select_table('analytics_reviews_files',' `id`="'.(int)$this->uri->segment(5).'"','num',1,true,'itembg',array('name'))):"";

    $content['header']         = $content['the_debt']->name.' -> '.$this->lang->line('admin_tpl_files'); 


    if (isset($_REQUEST['submit'])) {

      $error                       = "";
      $ret                         = "";
      $ret->name                   = $_REQUEST['name'];
      $ret->id                     = isset($_REQUEST['id'])?$_REQUEST['id']:"";

      $the_id                      = ($ret->id!="")?$ret->id:$next_id=$this->global_model->Auto_inc('analytics_reviews_files');

      $ret->debt_market_id         = $content['the_debt']->id;
      $ret->url                    = $this->global_model->SET_title_url($ret->name,'analytics_reviews_files',$ret->id);
      $ret->_file                  = upload_file($_FILES['_file'],isset($_REQUEST['old_file'])?$_REQUEST['old_file']:'','upload/downloads','file_'.$the_id.'_',$this->subdir);
      
      /*$ret->ufs = isset($_REQUEST['ufs'])?$_REQUEST['ufs']:0;
      $ret->premier = isset($_REQUEST['premier'])?$_REQUEST['premier']:0;*/
      
      if (empty($ret->name)) {
        $error[] = $this->lang->line('admin_pages_popup_error_name');
      }
      
      if (empty($ret->_file)) {
        $error[] = $this->lang->line('admin_pages_popup_error_file');
      }    

      if (is_array($error)) {

        $content['data']      = $ret;
        $content['error']     = $error;
        
      } else {     
         
        $ret->name           = mysql_string($_REQUEST['name']);              

        if (empty($ret->id)) {

          $ret->lang    = $this->site_lang;
          $ret->num     = $this->maindb_model->_get_max_num('analytics_reviews_files','num','`debt_market_id`="'.$content['the_debt']->id.'"');
          unset($ret->id);
          $this->db->insert('analytics_reviews_files', $ret);

        } else {

          $this->db->where('id', $ret->id);
          $this->db->update('analytics_reviews_files', $ret);
        }

        unset($ret);
        unset($error);
        $_SESSION['is_update'] = 'Successfully updated';
        redirect($this->uri->segment(1).'/'.$this->page_name);
                     
      } 
      
    } else {
      
      $id = isset($content['data']->id)?$content['data']->id:false;
      
      if (!$id) { 
        /*$content['data']->ufs = 1;
        $content['data']->premier = 0;*/
      }
    }

    return  $this->load->view('backoffice_'.$this->page_name.'_files_edit',$content,true);
  }  

  private function getSelectedArr($review_id) {

    $arr = array();
    $display_arr = $this->maindb_model->select_table('analytics_reviews_pages',' `analitic_review_id`="'.$review_id.'" and `lang`="'.$this->site_lang.'"','id','',false,'itembg',array('name'),'','',true);  
    if(!empty($display_arr)){
      foreach($display_arr as $item){
        $arr[$item->page_id] = $item;
      }
    }
    return $arr;
  }

   private function sub_menu($cat_id,$selected_arr=array(),$company='UFS')
  {
     $ret='';
     $else_str = '';

      $debt_Market_page = $this->maindb_model->select_table('pages','sub_page_type = "debt_market" and lang="'.$this->site_lang.'" ','name',1,true,'itembg',array('name'),'','',true);
      $runok_aktsiyi_page = $this->maindb_model->select_table('pages','sub_page_type = "share_market" and lang="'.$this->site_lang.'" ','name',1,true,'itembg',array('name'),'','',true);
      $commodities_page = $this->maindb_model->select_table('pages','sub_page_type = "commodities" and lang="'.$this->site_lang.'" ','name',1,true,'itembg',array('name'),'','',true);
      $economy_page = $this->maindb_model->select_table('pages','sub_page_type = "economy" and lang="'.$this->site_lang.'" ','name',1,true,'itembg',array('name'),'','',true);
      $strategy_page = $this->maindb_model->select_table('pages','sub_page_type = "stock_market_strategy" and lang="'.$this->site_lang.'" ','name',1,true,'itembg',array('name'),'','',true);
      $day_events_page = $this->maindb_model->select_table('pages','sub_page_type = "day_events" and lang="'.$this->site_lang.'" ','name',1,true,'itembg',array('name'),'','',true);
      $comments_page = $this->maindb_model->select_table('pages','sub_page_type = "comments" and lang="'.$this->site_lang.'" ','name',1,true,'itembg',array('name'),'','',true);

      if (!empty($debt_Market_page) || !empty($runok_aktsiyi_page) || !empty($commodities_page) || 
          !empty($economy_page) || !empty($strategy_page) || !empty($day_events_page) || !empty($comments)) {

        $debt_Market_page_id = !empty($debt_Market_page)?$debt_Market_page->id:'NULL';
        $runok_aktsiyi_page_id = !empty($runok_aktsiyi_page)?$runok_aktsiyi_page->id:'NULL';
        $commodities_page_id = !empty($commodities_page)?$commodities_page->id:'NULL';
        $economy_page_id = !empty($economy_page)?$economy_page->id:'NULL';
        $strategy_page_id = !empty($strategy_page)?$strategy_page->id:'NULL';
        $day_events_page_id = !empty($day_events_page)?$day_events_page->id:'NULL';
        $comments_page_id = !empty($comments_page)?$comments_page->id:'NULL';

        $else_str = 'and `id` in ('.$debt_Market_page_id.','.$runok_aktsiyi_page_id.','.$commodities_page_id.','
                                   .$economy_page_id.','.$strategy_page_id.','.$day_events_page_id.','.$comments_page_id.')';
      }
      $sql=$this->db->query('select `id`, `url`, `name`, `sub_page_type`, `parent_id`
                             from `pages` 
                             where `lang`="'.$this->site_lang.'" 
                             and `is_home`="no"
                             and `is_hide`="no" 
                             and `cat_id`="'.$cat_id.'"
                             and `parent_id`="0"
                             and company="'.$company.'"
                             '.$else_str.'
                             order by `sort_id` desc;');

      if($sql->num_rows()>0){
          $res = $sql->result();

          for($i=0;$i<count($res);$i++){
             $res[$i]->select=(isset($selected_arr[$res[$i]->id]))?1:0;

              $sql_s=$this->db->query('select `id`, `url`, `name`, `sub_page_type`, `parent_id`
                                       from `pages` 
                                       where `lang`="'.$this->site_lang.'" 
                                       and `is_home`="no"
                                       and `is_hide`="no" 
                                       and `cat_id`="'.$cat_id.'"
                                       and `parent_id`="'.$res[$i]->id.'"
                                       and company="'.$company.'"  
                                       order by `sort_id` desc;');

                  if($sql_s->num_rows()>0){
                      $res_s=$sql_s->result();
                      for($m=0;$m<count($res_s);$m++){
                          $res_s[$m]->select=(isset($selected_arr[$res_s[$m]->id]))?1:0;
                      }
                     $res[$i]->sub=$res_s;
                  }             
          }

        $ret=$res;
      }

    return $ret;
  }
}
?>