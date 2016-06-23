<?

class front extends Controller {

  var $data;
  var $temp;
  var $page_name;
  var $last_page_name;
  var $full_page_url;
  var $page_url;
  var $page_id=false;
  var $base_url='';
  var $phpself='/';
  var $phpself_https='/';
  var $urlsufix='.html'; 
  var $arr=array();
  var $pages='';
  var $subdir='';
  var $subdir_redirect='';
  var $infomail='info@test.ufs.artics.com';
  var $host='test.ufs-federation.com';
  var $mail_base_url='/';
  var $mail_phpself='/';
  var $mail_urlsufix='.html';
  var $cat_id;
  var $site_lang;
  var $dictionary        = array();
  var $settings          = array();
  var $active_settings   = array();
  var $front_trim_short  = true;

  ///xc\cs
  function front()
  {
            parent::Controller();

            
          $this->last_page_name = '';
          $this->full_page_url = $_SERVER['REQUEST_URI'];
          
          $list_f = explode('/',$this->full_page_url);
            if (sizeOf($list_f)==2) {
              $last = $list_f[sizeOf($list_f)-1];
              $last = strtolower($last);
              if (($last=='index.html') || ($last=='index.php')) {
              header('Location: http://'.$_SERVER['HTTP_HOST'],null,301);
              die();
              } 
            }
            
            if (sizeOf($list_f)>=2) {
              $this->last_page_name = $list_f[sizeOf($list_f)-1];
              $list_f2 = explode('.',$this->last_page_name);
              if (sizeOf($list_f2)>0) {
                $this->last_page_name = $list_f2[0];
              }
            }

            

      $s1 = strtolower($_SERVER['HTTP_HOST']);
      $s2 = $this->host;
      if (($s1!=$s2) &&
          ($s1!='www.'.$s2) &&
          ($s1!='ru.'.$s2) &&
          ($s1!='en.'.$s2) &&
          ($s1!='de.'.$s2) &&
          ($s1!='fr.'.$s2) &&
          ($s1!='cn.'.$s2)) {
        
        header('HTTP/1.0 404 Not Found',null,404);
        
        die();   
      }
      
            session_start();
            
            $this->mail_base_url='http://'.$_SERVER['HTTP_HOST'];
            $this->mail_phpself='http://'.$_SERVER['HTTP_HOST'].'/';
            
            
         //   $this->host = $_SERVER['HTTP_HOST'];

            $this->load->helper('url');
            $this->load->helper('pic');
            $this->load->helper('string');
            $this->load->helper('text');
            $this->load->helper('email');
            $this->load->helper('language');
            
            $this->lang->load('front','russian');
            $this->lang->load('global','russian');
            
            $this->load->model('global_model');

            $this->phpself       = 'http://'.$_SERVER['HTTP_HOST'].$this->subdir.'/';
            $this->phpself_https = 'https://'.$_SERVER['HTTP_HOST'].$this->subdir.'/';

            if($_SERVER['SERVER_PORT']==443){
                    $this->base_url='https://'.$_SERVER['HTTP_HOST'].$this->subdir;
            }else{
                    $this->base_url='http://'.$_SERVER['HTTP_HOST'].$this->subdir;
            }
            
            $this->site_lang=self::set_lang();
            
            $data['langs']=$this->global_model->component_arr('lang',$this->site_lang,'id',false,false,'asc','name','lang');
            $data['menu']=self::menu();
            
            $this->dictionary             = self::dictionary();
            $this->settings               = self::settings();
            $this->active_settings        = $this->is_active_settings();
            
            $this->data['body_css_class'] = 'index';
            $this->data['title']          = 'UFS Investment Company';
            $this->data['keywords']       = '';
            $this->data['description']    = '';
            
  
            $this->data['body_header']   = $this->load->view('body_header',$data,true);
            $this->data['body_footer']   = $this->load->view('body_footer',$data,true);
            $this->data['body_ie6']      = $this->load->view('body_ie6','',true);
  }
  
   
  /*main function*/  
  function index()
  {
        $this->page_name         = 'home';
        $this->page_url          = 'index';
        $this->data['home_page'] = true;
        $this->global_model->ssl_check(false);
        
            $this->load->model('front_index_model');
            $this->data['content']=$this->front_index_model->view();

        $this->load->view('body_index',$this->data);
  }
  
  function _redirect(&$method) 
  {

    $ret = false;

    if ($method) {
      
      $lang = "'".$this->site_lang."'";
      $redirect = "'".$method."'";
      $furl = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_BASENAME);
      $a = explode('?',$furl,2);
      if (sizeOf($a)>0) {
        $furl = $a[0];
      }
      $fext = '.'.strtolower(pathinfo($furl, PATHINFO_EXTENSION));
      if ($fext==$this->urlsufix) {
        $furl = substr($furl,0,strlen($furl)-strlen($fext));
      }
      $url = "'".$furl."'";
      
      
          $sql = $this->db->query(sprintf('SELECT id, url '.
                        'FROM pages '.
                        'WHERE lang=%s and (url=%s or redirect=%s) '.
                        'ORDER BY sort_id desc '.
                        'LIMIT 1',$lang,$url,$url));
          if($sql->num_rows()>0) {
            $r = $sql->row();
            if ($r) {

              $method = 'pages';
              $this->page_id = $r->id;
              
              $uri = '/front/pages/'.$this->global_model->pages_url_by_id($r->id);
              
              $this->uri->set_uri_string($uri);
              
              $ret = true;
            }
          }
    }   
      
    return $ret;
  }

  private function get_page_type($page_url)
  {
    $ret='';
  
    $sql=$this->db->query('select sub_page_type '.
                'from pages p '.
                'where lang="'.$this->site_lang.'" '.
                      'and url="'.$page_url.'" '.
                  'order by sort_id desc '.
                 'limit 1;');
  
    if($sql->num_rows()>0){
      $res=$sql->row();
      $ret=$res->sub_page_type;
    }
  
    return $ret;
  }
  
  private function get_redirect_by_page(&$id)
  {
    $ret = false;
    
    $cat_url = $this->uri->segment(3);
    $page_url=mysql_string($this->uri->segment(4));
    $s_page_url=mysql_string($this->uri->segment(5));
    
    $segments = $this->uri->segments;
    $last = '';
    if (sizeof($segments)>0) {
      $last = $segments[sizeof($segments)];   
    }
    
    $m_url = "'".mysql_string($cat_url)."'";
    $lang = "'".$this->site_lang."'";
    $tmp_url = '';
    
    if(empty($page_url)) {
        
      $where=' and (p.parent_id="0" or p.parent_id is null)'; 
      
    } else {
          
      if(empty($s_page_url)) {
               
        $tmp_url=$page_url;
        
      } else {
               
        $type = self::get_page_type($page_url);
        if (in_array($type,$this->global_model->open_types)) {
          
          $tmp_url=$s_page_url;
          
        } else {
          
          $tmp_url=$page_url;
           
        }
      }
      $where=' and p.url="'.$tmp_url.'"';
    }
        
    $sql = $this->db->query(sprintf('SELECT p.id, p.redirect, p.redirect_code '.
                                      'FROM pages p '.
                                      'LEFT JOIN pages_menu as m on m.id=p.cat_id '.
                                     'WHERE m.url=%s and m.is_home="no" and m.lang=%s and p.lang=%s and p.is_home="no"%s '.
                                     'ORDER BY p.sort_id desc '.
                                     'LIMIT 1',$m_url,$lang,$lang,$where));
    if ($sql->num_rows()==0) {
      
      if (!empty($page_url)) {
        
        $where=' and p.url="'.$page_url.'"';
        
        $sql = $this->db->query(sprintf('SELECT p.id, p.redirect, p.redirect_code '.
                                          'FROM pages p '.
                                          'LEFT JOIN pages_menu as m on m.id=p.cat_id '.
                                         'WHERE m.url=%s and m.is_home="no" and m.lang=%s and p.lang=%s and p.is_home="no"%s '.
                                         'ORDER BY p.sort_id desc '.
                                         'LIMIT 1',$m_url,$lang,$lang,$where));
      }
    }
    
    if ($sql->num_rows()>0) {
      $r = $sql->row(); 
      if ($r->redirect!='') {
        if ($tmp_url==$last) {
          $ret = $r;
        } else {
          $id = $r->id;
        } 
      } else {
        $id = $r->id;
      }
    }
    
    return $ret;
  }
  
    function pages()
    {
      $ret = false;
      $id = $this->page_id;
      if (!$id) {

        $ret = $this->get_redirect_by_page($id);
        if ($ret) {
          $s=strtolower(substr($ret->redirect,0,strlen('http')));
          $redirect=$ret->redirect;
          $ext='.'.pathinfo($redirect,PATHINFO_EXTENSION);
          if ($ext!=$this->urlsufix) {
            $redirect.=$this->urlsufix;
          }
          $code=$ret->redirect_code; 
          if ($code=='') {
            $code=301;
          } 
          if ($s=='http') {
            header("Location: ".$redirect, TRUE, $code);
          } else {
            redirect($redirect,'location',$code);
          }  
        }
      }
      
      if ($id) {
        $this->page_name              = 'pages';
        $this->page_url               = 'pages';
        $this->data['body_css_class'] = 'inner sub-inner o-kompanii';
        $this->global_model->ssl_check(false);
        
        $this->load->model('front_pages_model');
        $this->data['content']=$this->front_pages_model->view($id);

        $this->load->view('body_index',$this->data);
          
      }
    }
    
    function search()
    {
        $this->page_name              = 'search';
        $this->page_url               = 'search';
        $this->data['body_css_class'] = 'inner sub-inner o-kompanii search_result';
        //$this->data['body_css_class']='inner content-right-sidebar contact';
        $this->global_model->ssl_check(false);
        
            $this->load->model('front_search_model');
            $this->data['content']=$this->front_search_model->view();

        $this->load->view('body_index',$this->data);  
    }

  /*  function subscribe()
    {
      $this->page_name              = 'subscribe';
      $this->page_url               = 'subscribe';
      $this->data['body_css_class'] = 'inner sub-inner o-kompanii subscribe';
      $this->global_model->ssl_check(false);
    
      $this->load->model('front_subscribe_model');
      $this->data['content']=$this->front_subscribe_model->view();
    
      $this->load->view('body_index',$this->data);
    } */
    
    
    function disclosure_of_information()
    {
        $this->page_name='disclosure_of_information';
        $this->page_url='disclosure-of-information';
        $this->data['body_css_class']='inner content-right-sidebar';
        $this->global_model->ssl_check(false);
        
            $this->load->model('front_disclosure_of_information_model');
            $this->data['content']=$this->front_disclosure_of_information_model->view();

        $this->load->view('body_index',$this->data);  
    }

    function disclosure_of_information_download_pdf()
    {
        $this->page_name='disclosure_of_information';
        $this->page_url='disclosure-of-information-download-pdf';
        
          $this->load->model('front_disclosure_of_information_download_pdf_model');
          $this->front_disclosure_of_information_download_pdf_model->download();
    }
    
    function site_map()
    {
        $this->page_name='site_map';
        $this->page_url='site-map';
        $this->global_model->ssl_check(false);
        
            $this->load->model('front_site_map_model');
            $this->data['content']=$this->front_site_map_model->view();

        $this->load->view('body_index',$this->data); 
    }
    
    function downloads()
    {
        $this->page_name='downloads';
        $this->page_url='downloads';
        $this->data['body_css_class']='inner content-right-sidebar';
        $this->global_model->ssl_check(false);
        
            $this->load->model('front_downloads_model');
            $this->data['content']=$this->front_downloads_model->view();

        $this->load->view('body_index',$this->data);  
    }
    
    function download_files()
    {
        $this->page_name='downloads';
        $this->page_url='download-files';
        
          $this->load->model('front_download_files_model');
          $this->front_download_files_model->download();
    }
    
    function coming_soon()
    {
        $this->page_name='coming_soon';
        $this->page_url='coming-soon';
        $this->global_model->ssl_check(false);
        $this->data['body_css_class']='inner content-right-sidebar coming-soon';
        
        $this->load->model('front_coming_soon_model');
        $this->data['content']=$this->front_coming_soon_model->view();

        $this->load->view('body_index',$this->data); 
    }
    
    
    function bank() {
     
      $bic = isset($_REQUEST['bic'])?$_REQUEST['bic']:false;
      if ($bic) {
        
        $bank = $this->global_model->get_query_data(sprintf('select b.*, r.region_id 
                                                               from banks b
                                                               left join regions r on r.rgn=b.rgn   
                                                              where b.newnum=%s limit 1',
                                                            $this->global_model->quote($bic)));
        if (is_array($bank) && sizeOf($bank)>0) {
          $bank = @json_encode($bank[0]);
          echo $bank;
        }
      }  
    }
    
        
    function instruments() {
      
      header("HTTP/1.0 404 Not Found");
      die();
      
     // $post = @file_get_contents('/install/data.xml');
      $post = @file_get_contents('php://input');
      
      $get = (trim($post)!='')?false:true;
      if ($get) {
          
        $instruments = $this->global_model->get_query_data('select instrument_id, ident, issuer, isin '.
                                                             'from instruments '.
                                                            'where locked is null and ident is not null '.
                                                            'order by created ');
        if (is_array($instruments) && sizeOf($instruments)>0) {
          
          $instruments = $this->global_model->data_to_class($instruments);
          
          $xmlns = 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"';
          $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Instruments '.$xmlns.'></Instruments>');
          
          foreach($instruments as $i) {
            
            $params = $this->global_model->get_query_data(sprintf('select ip.param_id, p.ident, p.param_type '.
                                                                    'from instrument_params ip '.
                                                                    'join params p on p.param_id=ip.param_id '.
                                                                   'where ip.instrument_id=%s and ip.locked is null '.
                                                                   'order by p.ident ',
                                                                  $this->global_model->quote($i->instrument_id)));
            if (is_array($params) && sizeOf($params)>0) {
              
              $params = $this->global_model->data_to_class($params);
              
              $n0 = $xml->addChild('Instrument');
              $n0->addAttribute('id',$i->instrument_id);
              if (trim($i->ident)!='') {
                $n0->addAttribute('ident',$i->ident);
              }
              if (trim($i->issuer)!='') {
                $n0->addAttribute('issuer',$i->issuer);
              }
              if (trim($i->isin)!='') {
                $n0->addAttribute('isin',$i->isin);
              }
              
              $n1 = $n0->addChild('Params');
              foreach($params as $p) {
                
                $n2 = $n1->addChild('Param');
                $n2->addAttribute('id',$p->param_id);
                if (trim($p->ident)!='') {
                  $n2->addAttribute('ident',$p->ident);
                }
                $n2->addAttribute('type',$p->param_type);
              }
            }
          }
          $instruments = $xml->asXML(); 
          
          echo $instruments;
        }
        
      } else {
        
        @file_put_contents('/install/data.xml',$post);
        
        $xml = new SimpleXMLElement($post);
        if ($xml) {
          
          $flag = false;
          
          foreach($xml->Instrument as $n0) {
            
            $instrument_id = (string)$n0['id'];
            $r = true;
            
            foreach($n0->Params->Param as $n1) {
              
              $param_id = (string)$n1['id'];
              $type = (int)$n1['type'];
              
              $data['instrument_id'] = $instrument_id;
              $data['to_date'] = date('Y-m-d',strtotime('-1 day',time())).' 23:45:00';
              $data['param_id'] = $param_id;
              $data['currency_id'] = null;
              $data['value_number'] = null;
              $data['value_string'] = null;
              $data['value_date'] = null;
              
              $value = isset($n1->value)?$n1->value:null;
              if (isset($value)) {
                
                switch($type) {
                  case 0:
                    $data['value_number'] = (double)$value;
                    break;
                  case 1:
                    $data['value_string'] = (string)$value;
                    break;
                  case 2:
                    $s = (string)$value;
                    $t = strtotime($s); // ??? what's the format of date
                    $data['value_date'] = date($t);
                    break;
                  default:
                    $data['value_string'] = (string)$value;
                }
                
                $r = $r && $this->global_model->insert('instrument_values',$data);  
              }
              
            }
            $flag = $r;
          }
          
          if ($flag) {
            echo('ok');
          } else {
            echo ('failed');
          }
        }
      }
    }
    
    function notify() {
      
      $success = (isset($_REQUEST['success']) && $_REQUEST['success']=='1')?true:false;
      $step = (isset($_REQUEST['step']) && trim($_REQUEST['step'])!='')?trim($_REQUEST['step']):false;
      $type = (isset($_REQUEST['type']) && trim($_REQUEST['type'])!='')?trim($_REQUEST['type']):false;
      
      switch ($type) {
        case 'ufsblb': {
          if (!$success) {
            $body = ($success)?'Данные успешно загружены':sprintf('Не удалось загрузить данные (шаг: %s)',$step);
            $emails = $this->global_model->data_to_class(array(array('email'=>'ufsblb@ufs-federation.com')));
            $r = $this->global_model->send_emails($emails,'Уведомление о загрузке данных из Bloomberg',$body,null,'UFS Bloomberg');
            if ($r) {
              echo('ok');
            } else {
              echo('failed');
            }
          } else {
            echo('ok');
          }
          break;
        }
        case 'send': {
          $email = (isset($_REQUEST['email']) && trim($_REQUEST['email'])!='')?trim($_REQUEST['email']):false;
          $name = (isset($_REQUEST['name']) && trim($_REQUEST['name'])!='')?trim($_REQUEST['name']):false;
          $fathername = (isset($_REQUEST['fathername']) && trim($_REQUEST['fathername'])!='')?trim($_REQUEST['fathername']):false;
          $secondname = (isset($_REQUEST['secondname']) && trim($_REQUEST['secondname'])!='')?trim($_REQUEST['secondname']):false;
          $phone = (isset($_REQUEST['phone']) && trim($_REQUEST['phone'])!='')?trim($_REQUEST['phone']):false;
          $date = (isset($_REQUEST['date']) && trim($_REQUEST['date'])!='')?trim($_REQUEST['date']):false;
          $r = false;
          if (($email)&&($name)&&($phone)) {
            $body = sprintf('Зарегистрировался пользователь: %s %s %s;<br>email: %s;<br>контактный телефон: %s;<br>дата регистрации: %s.',$name, $fathername, $secondname, $email, $phone, $date);
            //$emails = $this->global_model->data_to_class(array(array('email'=>'apply@ufs-federation.com'))); 
            $emails = $this->global_model->data_to_class(array(array('email'=>'apply@ufs-federation.com'),array('email'=>'vpa@ufs-federation.com')));
            $r = $this->global_model->send_emails($emails,'Регистрация на мероприятие',$body,null,'Web-site');
          }
          $data = new stdClass();
          $data->success = $r;
          echo(@json_encode($data));
          break;
        }
        
        case 'nnsend': {
          $email = (isset($_REQUEST['email']) && trim($_REQUEST['email'])!='')?trim($_REQUEST['email']):false;
          $name = (isset($_REQUEST['name']) && trim($_REQUEST['name'])!='')?trim($_REQUEST['name']):false;
          $phone = (isset($_REQUEST['phone']) && trim($_REQUEST['phone'])!='')?trim($_REQUEST['phone']):false;
          $date = (isset($_REQUEST['date']) && trim($_REQUEST['date'])!='')?trim($_REQUEST['date']):false;
          $r = false;
          if (($email)&&($name)&&($phone)) {
            $body = sprintf('<b>Зарегистрировался пользователь:</b> %s;<br><b>Email:</b> %s;<br><b>Контактный телефон:</b> %s;<br><b>Мероприятие:</b> %s.',$name, $email, $phone, $date);
            //$emails = $this->global_model->data_to_class(array(array('email'=>'apply@ufs-federation.com'))); 
            $emails = $this->global_model->data_to_class(array(array('email'=>'apply@ufs-federation.com'),array('email'=>'vpa@ufs-federation.com')));
            
            
            
            
            
            $r = $this->global_model->send_emails($emails,'Регистрация на мероприятие',$body,null,'Web-site');
          }
          $data = new stdClass();
          $data->success = $r;
          echo(@json_encode($data));
          break;
        }
        
        
        case 'sendgss': {
          $email = (isset($_REQUEST['email']) && trim($_REQUEST['email'])!='')?trim($_REQUEST['email']):false;
          $company = (isset($_REQUEST['company']) && trim($_REQUEST['company'])!='')?trim($_REQUEST['company']):false;
          $position = (isset($_REQUEST['position']) && trim($_REQUEST['position'])!='')?trim($_REQUEST['position']):false;
          $phone = (isset($_REQUEST['phone']) && trim($_REQUEST['phone'])!='')?trim($_REQUEST['phone']):false;
          $volume = (isset($_REQUEST['ivolume']) && trim($_REQUEST['ivolume'])!='')?trim($_REQUEST['ivolume']):false;
          $r = false;
          if (($email)&&($volume)&&($phone)) {
            $body = sprintf('<b>Email:</b> %s;<br><b>Организация:</b> %s;<br><b>Должность:</b> %s;<br><b>Контактный телефон:</b> %s;<br><b>Желаемый объем:</b> %s.',$email,$company,$position,$phone,$volume);
            $emails = $this->global_model->data_to_class(array(array('email'=>'gss@ufs-federation.com'))); 
            //$emails = $this->global_model->data_to_class(array(array('email'=>'apply@ufs-federation.com'),array('email'=>'vpa@ufs-federation.com'),array('email'=>'cey@ufs-financial.ch')));
            $r = $this->global_model->send_emails($emails,'ГСС - Завяка на участие',$body,null,'Web-site');
          }
          $data = new stdClass();
          $data->success = $r;
          echo(@json_encode($data));
          break;
        }
        
        
        case 'nsend': {
          $name = (isset($_REQUEST['name']) && trim($_REQUEST['name'])!='')?trim($_REQUEST['name']):false;
          $phone = (isset($_REQUEST['phone']) && trim($_REQUEST['phone'])!='')?trim($_REQUEST['phone']):false;
          $object = (isset($_REQUEST['object']) && trim($_REQUEST['object'])!='')?trim($_REQUEST['object']):false;
          $cost = (isset($_REQUEST['cost']) && trim($_REQUEST['cost'])!='')?trim($_REQUEST['cost']):false;
          $prepayment = (isset($_REQUEST['prepayment']) && trim($_REQUEST['prepayment'])!='')?trim($_REQUEST['prepayment']):false;
          $contractdate = (isset($_REQUEST['contractdate']) && trim($_REQUEST['contractdate'])!='')?trim($_REQUEST['contractdate']):false;
          $typepayment = (isset($_REQUEST['typepayment']) && trim($_REQUEST['typepayment'])!='')?trim($_REQUEST['typepayment']):false;

          $r = true;
          $error = ''; 
          
          $captcha = isset($_REQUEST['captcha'])?true:false;
          if ($captcha) {

            $captcha_word = isset($_SESSION['captcha_word'])?$_SESSION['captcha_word']:'';
            $word = isset($_REQUEST['captcha'])?$_REQUEST['captcha']:'';
      
            if ($word!=$captcha_word) {
              $error = dictionary('Неправильно указано число с картинки.');
              $r = false;
            }
          }
          
          if ($r) {
            if ($name && $phone && $object) {
              $body = sprintf('Новый клиент: %s;<br>Контакты: %s;<br>интересующий продукт: %s;<br>стоимость: %s;<br>аванс: %s;<br>срок договора: %s;<br>тип платежей: %s',$name, $phone, $object, $cost, $prepayment, $contractdate, $typepayment);
              //$emails = $this->global_model->data_to_class(array(array('email'=>'apply@ufs-federation.com'))); //dbd
              $emails = $this->global_model->data_to_class(array(array('email'=>'apply@ufs-federation.com'),array('email'=>'vpa@ufs-federation.com'),array('email'=>'ssi@ufs-lease.com')));
              $r = $this->global_model->send_emails($emails,'Новый клиент',$body,null,'Web-site');
              if (!$r) {
                $error = dictionary('Ошибка отправки сообщения. Попробуйте еще раз.');
              }
            } else {
              $error = dictionary('Не заполнено одно из полей.');
              $r = false;
            }
          }
          
          $data = new stdClass();
          $data->success = $r;
          $data->message = $error;
          
          echo(@json_encode($data));
          break;
        }
        
        case 'become_client': {
          
          $name = (isset($_REQUEST['name']) && trim($_REQUEST['name'])!='')?trim($_REQUEST['name']):false;
          $city = (isset($_REQUEST['city']) && trim($_REQUEST['city'])!='')?trim($_REQUEST['city']):false;
          $phone = (isset($_REQUEST['phone']) && trim($_REQUEST['phone'])!='')?trim($_REQUEST['phone']):false;
          $email = (isset($_REQUEST['email']) && trim($_REQUEST['email'])!='')?trim($_REQUEST['email']):false;
          $prod = (isset($_REQUEST['prod']) && trim($_REQUEST['prod'])!='')?trim($_REQUEST['prod']):false;
          
          $r = false;
          if ($name && $city && $phone && $email && $prod) {
            
            $body = sprintf('<b>Зарегистрировался клиент:</b> %s;<br><b>Город:</b> %s;<br><b>Телефон:</b> %s;<br><b>E-mail:</b> %s;<br><b>Продукт:</b> %s.',
                            $name,$city,$phone,$email,$prod);
            $emails = $this->global_model->data_to_class(array(array('email'=>'apply@ufs-federation.com'),array('email'=>'vpa@ufs-federation.com')));
            
            $r = $this->global_model->send_emails($emails,'Стать клиентом',$body,null,'Стать клиентом');
            
          } else {
            $error = dictionary('Не заполнено одно из полей.');
            $r = false;
          }
          $data = new stdClass();
          $data->success = $r;
          echo(@json_encode($data));
          break;
        }
        
        case 'learning': {
          
          $name = (isset($_REQUEST['name']) && trim($_REQUEST['name'])!='')?trim($_REQUEST['name']):false;
          $email = (isset($_REQUEST['email']) && trim($_REQUEST['email'])!='')?trim($_REQUEST['email']):false;
          $phone = (isset($_REQUEST['phone']) && trim($_REQUEST['phone'])!='')?trim($_REQUEST['phone']):false;
          $city = (isset($_REQUEST['city']) && trim($_REQUEST['city'])!='')?trim($_REQUEST['city']):false;
          $event = (isset($_REQUEST['event']) && trim($_REQUEST['event'])!='')?trim($_REQUEST['event']):false;
          
          $r = false;
          $error = ''; 
          
          if (!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/",$email)) {
            
            $error = dictionary('Неправильно указан адрес электронной почты');
            $r = false;
            
          } else if (!preg_match("/^([0-9.+]{10,12})$/",$phone)) {
            
            $error = dictionary('Неправильно указан телефон');
            $r = false;
            
          } else if ($name && $email && $phone && $city && $event) {
            
            $body = sprintf('<b>Зарегистрировался клиент:</b> %s<br><b>E-mail:</b> %s<br><b>Телефон:</b> %s<br><b>Город:</b> %s<br><b>Семинар:</b> %s',
                            $name,$email,$phone,$city,$event);
            
            //$emails = $this->global_model->data_to_class(array(array('email'=>'zev@ufs-federation.com'),array('email'=>'info@ufs-federation.com'),array('email'=>'apply@ufs-federation.com')));
            $emails = $this->global_model->data_to_class(array(array('email'=>'tsv@ufs-financial.ch')));
            
            $r = $this->global_model->send_emails($emails,sprintf('Запись на семинар: %s (%s)',$email,$name),$body,null,'Запись на семинар');
            if (!$r) {
              $error = dictionary('Ошибка отправки сообщения. Попробуйте еще раз');
            } else {
              
              $emails = $this->global_model->data_to_class(array(array('email'=>$email)));
              $body = '«Благодарим Вас за регистрацию! О дате ближайшего выбранного Вами семинара со свободными местами мы обязательно сообщим дополнительно. Вы также можете связаться с менеджером, чтобы скоординировать дату семинара с Вашим деловым расписанием, по телефону  8 800 234 0202»'; 
              $r = $this->global_model->send_emails($emails,$event,$body,null,'Запись на семинар');
              if (!$r) {
                $error = dictionary('Ошибка отправки сообщения. Попробуйте еще раз');
              }
            }
            
          } else {
            $error = dictionary('Не заполнено одно из полей');
            $r = false;
          }
          
          $data = new stdClass();
          $data->success = $r;
          $data->message = $error;
          echo(@json_encode($data));
          break;
        }
        
        case 'feedback': {
          
          $name = (isset($_REQUEST['name']) && trim($_REQUEST['name'])!='')?trim($_REQUEST['name']):false;
          $email = (isset($_REQUEST['email']) && trim($_REQUEST['email'])!='')?trim($_REQUEST['email']):false;
          $question = (isset($_REQUEST['question']) && trim($_REQUEST['question'])!='')?trim($_REQUEST['question']):false;
          
          $r = false;
          $error = ''; 
          
          $captcha = isset($_REQUEST['captcha'])?true:false;
          if ($captcha) {

            $captcha_word = isset($_SESSION['captcha_word'])?$_SESSION['captcha_word']:'';
            $word = isset($_REQUEST['captcha'])?$_REQUEST['captcha']:'';
      
            if ($word!=$captcha_word) {
              $error = dictionary('Неправильно указан текст на картинке.');
              $r = false;
            }
            
          } else if (!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/",$email)) {
            
            $error = dictionary('Неправильно указан адрес электронной почты');
            $r = false;
            
          } else if ($name && $email && $question) {
            
            $body = sprintf('<b>Оставлен отзыв от:</b> %s<br><b>E-mail:</b> %s<br><br>%s',
                            $name,$email,$question);
            
            //$emails = $this->global_model->data_to_class(array(array('email'=>'mas@ufs-finance.com'),array('email'=>'tua@ufs-financial.ch')));
            $emails = $this->global_model->data_to_class(array(array('email'=>'tsv@ufs-financial.ch')));
            
            $r = $this->global_model->send_emails($emails,sprintf('Оставлен отзыв: %s (%s)',$email,$name),$body,null,'Оставлен отзыв');
            if (!$r) {
              $error = dictionary('Ошибка отправки сообщения. Попробуйте еще раз');
            }
            
          } else {
            $error = dictionary('Не заполнено одно из полей');
            $r = false;
          }
          
          $data = new stdClass();
          $data->success = $r;
          $data->message = $error;
          echo(@json_encode($data));
          break;
        }
        
        /*case 'valentine': {
        
          $r = false; 
          $error = '';  
          $root = $_SERVER['DOCUMENT_ROOT'];
          
          require_once $root.'/system/application/libraries/MessageGate.php';
          
          $gate = new MessageGate();
          if ($gate->connect()) {
            
            $message = new Message();
            
            $from_name = (isset($_REQUEST['from-name']) && trim($_REQUEST['from-name'])!='')?trim($_REQUEST['from-name']):false;
            $from_email = (isset($_REQUEST['from-email']) && trim($_REQUEST['from-email'])!='')?trim($_REQUEST['from-email']):false;
            $to_name = (isset($_REQUEST['to-name']) && trim($_REQUEST['to-name'])!='')?trim($_REQUEST['to-name']):false;
            $to_email = (isset($_REQUEST['to-email']) && trim($_REQUEST['to-email'])!='')?trim($_REQUEST['to-email']):false;
            $card = (isset($_REQUEST['card']) && trim($_REQUEST['card'])!='')?trim($_REQUEST['card']):false;
          
            $message->senderName = $from_name;
            $message->senderContact = 'mailer@lists.ufs-financial.ch';
            
            $message->subject = 'Валентинка '.$to_name;
            $message->body = 'Здравствуйте.<br/>Вам пришла открытка от '.$from_name.' ('.$from_email.').<br/>С Днем святого Валентина!';
            
            $message->addRecipient($to_email,$to_name);
            $message->addRecipient('cey@ufs-financial.ch','Евгений');
            //$message->addRecipient('tsv@ufs-financial.ch','Сергей');
            
            $message->addPattern('EmailResearchPattern');
            
            $message->addHeader('Reply-To',$from_name.' <'.$from_email.'>');
            
            $message->priority = 999;
            
            $file = $card.'.jpg';
            $data = file_get_contents($root.'/banners/stval/'.$file);
            $message->addAttachment('Открытка','jpg',$data,'image/jpg');
            
            $r = $gate->sendMessage($message);
            if (is_object($r) && $r instanceof SendResult && !is_null($r->messageId)) {
              
              $r = true;
              $error = dictionary('Сообщение отправлено.');
              
            } else $error = dictionary('Шлюз сообщений не может принять сообщение.');
            
          } else $error = dictionary('Шлюз сообщений не доступен.');
          
          $data = new stdClass();
          $data->success = $r;
          $data->message = $error;
          $data->request = $_REQUEST;
          $data->root = $root;
          
          header('Content-type: application/json');
          
          echo(@json_encode($data));
          break;
        } */
      }
    }
    
    function callback() {
      
      $phone = (isset($_REQUEST['phone']) && trim($_REQUEST['phone'])!='')?trim($_REQUEST['phone']):false;

      $r = true;
      $error = '';

      $captcha = isset($_REQUEST['captcha'])?true:false;
      if ($captcha) {

        $captcha_word = isset($_SESSION['captcha_word'])?$_SESSION['captcha_word']:'';
        $word = isset($_REQUEST['captcha'])?$_REQUEST['captcha']:'';

        if ($word!=$captcha_word) {
          $error = dictionary('Неправильно указано число с картинки.');
          $r = false;
        }
      }

      if ($r) {
        if ($phone) {
          $r = $this->global_model->call_phone($phone);
          if (!$r) {
            $error = dictionary('Ошибка отправки сообщения. Попробуйте еще раз.');
          }
        } else {
          $error = dictionary('Не заполнено поле телефон.');
          $r = false;
        }
      }

      $data = new stdClass();
      $data->success = $r;
      $data->message = $error;

      echo(@json_encode($data));
    }
    
    
#############################################################################################################################################
    
  
  /* error function */
  function error()
  {
      $this->page_name='error';
      $this->page_url='error';

            $this->load->model('front_error_model');
      $this->data['content']=$this->front_error_model->view();
      
        $this->load->view('body_index',$this->data);
        
  }
    
    
    /* lang select */
    function lang_select()
    {
        header('Location: http://'.mysql_string($this->uri->segment(3)).'.'.$this->host,null,301);
        die();
    }
    
    
    private function menu()
    {
      $ret=array();
      $model=mysql_string($this->uri->segment(2));
      $url=mysql_string($this->uri->segment(3));
      
      $sql=$this->db->query('select id, `url`, `name`
                             from `pages_menu`
                             where `lang`="'.$this->site_lang.'" 
                             and `is_home`="no"
                             and `is_hide`="no"
                             order by `sort_id` desc;');

      if($sql->num_rows()>0){
          $res=$sql->result();
          $res=$this->global_model->adjustment_of_results($res,'pages_menu');
          
          foreach ($res as &$r) {
            
            $childs = $this->global_model->get_table_data_as_class('pages',null,array('main'=>1,'cat_id'=>$r->id),array('sort_id desc'));
            if (is_array($childs) && sizeOf($childs)>0) {
              $r->childs = $childs;
            }
            
            $r->select = ($model=='pages' and $url==$r->url)?true:false;
          }
          
          /*for($i=0;$i<count($res);$i++){
              $res[$i]->select=($model=='pages' and $url==$res[$i]->url)?' class="selected"':'';
          }*/
          $ret=$res;
      }
        
      return $ret;
    }
    
    private function dictionary()
    {
      $ret=array();
      
        $sql=$this->db->query('select `ru`, `'.$this->site_lang.'` as `value`
                               from `dictionary`
                               order by `id` desc;');

        if($sql->num_rows()>0){
            $res=$sql->result();
            $res=$this->global_model->adjustment_of_results($res,'dictionary');
                
            foreach($res as $item){
                $ret[$item->ru]=$item->value;
            }
        }
        
      return $ret;
    }
    
    private function settings()
    {
      $ret=array();
      
        $sql=$this->db->query('select `name`, `'.$this->site_lang.'` as `value`, `type`
                               from `settings`
                               order by `id` asc;');

        if($sql->num_rows()>0){
            $res=$sql->result();
                
            foreach($res as $item){
                $ret[$item->name]=($item->type=='text')?HTML_decode($item->value):$item->value;
            }
        }
        
      return $ret;
    }
    
    private function is_active_settings()
    {
      $ret=array();
      
        $sql=$this->db->query('select `name`, `'.$this->site_lang.'` as `value`, `type`, `is_active`
                               from `settings`
                               order by `id` asc;');

        if($sql->num_rows()>0){
            $res=$sql->result();
                
            foreach($res as $item){
                $ret[$item->name]=$item->is_active;
            }
        }
        
      return $ret; 
    }
     
    private function set_lang()
    {
      $lang='';
       
        $arr=$this->global_model->component_arr('lang',$lang,'id',false,false,'asc','name','lang');
        
        $mask=array();
        foreach($arr as $val)
        {
            $mask[]=$val->lang;
        }

            if(preg_match('/^('.implode('|',$mask).')\./',$_SERVER['HTTP_HOST'],$url_arr)==true)
            {
                $lang=$url_arr[1];
            }else{
                header('Location: http://ru.'.$this->host,null,301);
                die();
            }
            
       return $lang;
    }

}
?>