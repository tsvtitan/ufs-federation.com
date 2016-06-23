<?
class backoffice extends Controller {

    var $data;
    var $page_name;
    var $sub_page_name;
    var $phpself='/backoffice/';
    var $base_url='/';
    var $urlsufix='';
    var $subdir='';
    var $subdir_redirect=''; 
    var $arr=array();
    var $infomail='test.info@ufs.artics.com';
    var $host='test.ufs-federation.com';
    var $mail_base_url='/';
    var $mail_phpself='/';
    var $mail_urlsufix='.html';
    var $cat_id;
    var $cat_sid;
    var $tpl_header; 
    var $site_lang;
    var $login='';

    function backoffice()
    {
            parent::Controller();

            session_start();

            $this->mail_base_url='http://'.$_SERVER['HTTP_HOST'];
            $this->mail_phpself='http://'.$_SERVER['HTTP_HOST'].'/';
            
            $this->host = $_SERVER['HTTP_HOST'];
            
            if(!isset($_SESSION['site_lang']))
            {
                $_SESSION['site_lang']='ru';
            }
            $this->site_lang=$_SESSION['site_lang'];

            $this->load->helper('url');
            $this->load->helper('pic');
            $this->load->helper('string');
            $this->load->helper('email');
            $this->load->helper('file');
            $this->load->helper('form');
            
            switch ($this->site_lang) { 
              case 'ru': default:
                $this->lang->load('backoffice','russian');
                break;
              case 'en':
                $this->lang->load('backoffice','english');
                break;
            }            
            $this->lang->load('global','russian');
            
            $this->load->model('global_model');
            
            $this->phpself='http://'.$_SERVER['HTTP_HOST'].$this->subdir.'/backoffice/';
            $this->phpself_https='https://'.$_SERVER['HTTP_HOST'].$this->subdir.'/backoffice/';

            if($_SERVER['SERVER_PORT']==443){
                $this->base_url='https://'.$_SERVER['HTTP_HOST'].$this->subdir.'/';
            }else{
                $this->base_url='http://'.$_SERVER['HTTP_HOST'].$this->subdir.'/';
            }
            
            $this->data['langs']=$this->global_model->component_arr('lang',$this->site_lang,'id',false,false,'asc','name','lang');


            $this->data['title']='UFS Investment Company';
            
            //ini_set('session.cache_expire',1800);

    }


    /* index function */
    function index()
    {

            if(isset($_SESSION['login_id']) and isset($_SESSION['login_pass'])){
                    redirect($this->subdir_redirect.'backoffice/home');
            }else{
                    if(isset($_REQUEST['submit'])){
                            $sql=$this->db->query('select `id`, `pass` from `logins` where `login`="'.$_REQUEST['login'].'" and `pass`="'.md5($_REQUEST['password']).'";');
                            $res=$sql->result();

                                    if(isset($res[0]->id)){
                                                    $_SESSION['login_id']=$res[0]->id;
                                                    $_SESSION['login_pass']=$res[0]->pass;
                                            redirect($this->subdir_redirect.'backoffice/home');
                                    }else{
                                            $content['error']=$this->lang->line('admin_login_error');
                                                    $this->data['content']=$this->load->view('backoffice_login',$content,true);
                                    }

                    }else{
                            $content['error']='';
                                    $this->data['content']=$this->load->view('backoffice_login',$content,true);
                    }
            }

            $this->load->view('backoffice_index',$this->data);
    }

    private function make_main_menu() {

      $ret = false;
      
      $menu = $this->global_model->admin_menu();
      if (sizeOf($menu)>0) {
        
        $groups = array();
        $other = 'Прочие';
        
        foreach($menu as $mk=>$mv) {
          
          $name = $mv['name'];
          $s = $name;
          $pos = strpos($s,':');
          if ($pos) {
            $s = substr($name,0,$pos);
            $name = substr($name,$pos+1);
          } else {
            $s = $other;
          }
          $s = trim($s);
          if (!isset($groups[$s])) {
            $groups[$s] = array(); 
          }
          $o = new stdClass();
          $o->name = trim($name);
          $o->sel = $mv['sel'];
          $groups[$s][$mk] = $o; 
        }
        
        $ret = $groups; 
      } 
      return $ret;
    } 
              
    /* check login function */
    private function check_login()
    {
            if(!isset($_SESSION['login_id']) and !isset($_SESSION['login_pass'])){
                    redirect($this->subdir_redirect.'backoffice');
            }else{
                    $sql=$this->db->query('select `id`, `pass`, `login` from `logins` where `id`="'.$_SESSION['login_id'].'" and `pass`="'.$_SESSION['login_pass'].'";');
                    
                    $res=$sql->result();
                    
                    if(!isset($res[0]->id)){
                        session_destroy();
                        redirect($this->subdir_redirect.'backoffice');
                    } else {
                      
                      $this->login=$res[0]->login;  
                      $path=strtolower($_SERVER['REQUEST_URI']);   
                      $access='';
                      $menu=array_reverse(explode('/',$path));
                      
                      foreach($menu as $m) {

                        $sql=$this->db->query('select p.`path`, p.`access` '.
                                                'from `permissions` p '.
                                                'join `roles` r on r.id=p.role_id '.
                                                'join `login_roles` lr on lr.role_id=r.id '.
                                               'where lr.`login_id`="'.$res[0]->id.'" '.
                                                 'and p.`path`="'.$path.'" '.
                                               'order by 2 desc;');
                      
                        if($sql->num_rows()>0) {
                          
                          $ret=$sql->result();
                          
                          foreach ($ret as $r) {
                            
                            if ($r->access<>$access) {
                              $access=$r->access;
                              break;
                            }  
                          }
                        }
                        
                        $l=strlen($m)+1;
                        $l2=strlen($path);
                        
                        $path=substr($path,0,$l2-$l);
                        if ($path=='') {
                          $path='/';  
                        }
                        
                        if ($access!='') {
                          break;  
                        }
                        
                      }
                      
                      if ($access=='') {
                        $access='no';
                      }
                      
                      if ($access=='no') {
                        $_SESSION['access_denied']=true;
                        if (isset($_SESSION['access_path'])) {
                          if ($_SESSION['access_path']==$_SERVER['REQUEST_URI']) {
                            session_destroy();
                            redirect($this->subdir_redirect.'backoffice');
                          }  else {
                            redirect($_SESSION['access_path']);
                          }  
                        } else {
                          session_destroy();
                          redirect($this->subdir_redirect.'backoffice');
                        } 
                      } else {
                      
                        if (isset($_SESSION['access_denied'])) {
                         
                          if ($_SESSION['access_denied']) {
                            $this->data['access_denied']=$this->load->view('backoffice_access_denied','',true);
                          }
                          unset($_SESSION['access_denied']);
                        }
                      }  
                      
                    }         
            }

        $this->data['menu'] = $this->make_main_menu();

        $this->data['logout_and_change_pass']=true;
        
        $message = $this->global_model->get_message();
        if ($message) {
          $content['message'] = $message;
          $this->data['message_content'] = $this->load->view('backoffice_message',$content,true);
        }
        
        $_SESSION['access_path']=$_SERVER['REQUEST_URI'];
    }


    /* logout function */
    function logout()
    {
            session_destroy();
            redirect($this->subdir_redirect.'backoffice');
    }


    /* chenge password function */
    function change_pass()
    {
            self::check_login();

            if(isset($_REQUEST['submit'])){

                    if($_SESSION['login_pass']==md5($_REQUEST['oldpassword']) and !empty($_REQUEST['newpassword'])){
                            $newpass=md5($_REQUEST['newpassword']);
                            $_SESSION['login_pass']=$newpass;
                                    $this->db->query('update `logins` set `pass`="'.$newpass.'" where `id`="'.$_SESSION['login_id'].'";');
                            redirect($this->subdir_redirect.'backoffice/home');
                    }else{
                            $content['error']=$this->lang->line('admin_login_error');
                            $this->data['content']=$this->load->view('backoffice_change_pass',$content,true);
                    }

            }else{
                            $content['error']='';
                            $this->data['content']=$this->load->view('backoffice_change_pass',$content,true);
            }

            $this->load->view('backoffice_index',$this->data);
    }



    /* popup case */
    private function popup_case($table,$content,$page_name,$numbtype=false,$nat_sort=false)
    {
        $this->load->model('backoffice_popup_case_model');
        $func=($this->uri->segment(4)!='')?$this->uri->segment(4):'view';
        $this->data['content']=$this->backoffice_popup_case_model->$func($table,$content,$page_name,$numbtype,$nat_sort);
    }
    
    
  /* popup case pages_menu */
  private function popup_case_pages_menu($table,$content,$page_name)
  {
        $this->load->model('backoffice_popup_case_pages_menu_model');
        $func=($this->uri->segment(4)!='')?$this->uri->segment(4):'view';
        $this->data['content']=$this->backoffice_popup_case_pages_menu_model->$func($table,$content,$page_name);
  }

    
  /* popup case disclosure_of_information_menu */
  private function popup_case_disclosure_of_information_menu($table,$content,$page_name)
  {
        $this->load->model('backoffice_popup_case_disclosure_of_information_menu_model');
        $func=($this->uri->segment(4)!='')?$this->uri->segment(4):'view';
        $this->data['content']=$this->backoffice_popup_case_disclosure_of_information_menu_model->$func($table,$content,$page_name);
  }

  /* popup case downloads_menu */
  private function popup_case_downloads_menu($table,$content,$page_name)
  {
        $this->load->model('backoffice_popup_case_downloads_menu_model');
        $func=($this->uri->segment(4)!='')?$this->uri->segment(4):'view';
        $this->data['content']=$this->backoffice_popup_case_downloads_menu_model->$func($table,$content,$page_name);
  }

  /* popup case roles */
  private function popup_case_roles($table,$content,$page_name)
  {
    $this->load->model('backoffice_popup_case_roles_model');
    $func=($this->uri->segment(4)!='')?$this->uri->segment(4):'view';
    $this->data['content']=$this->backoffice_popup_case_roles_model->$func($table,$content,$page_name);
  }

  /* popup case subscribers */
  private function popup_case_subscribers($table,$content,$page_name)
  {
    $this->load->model('backoffice_popup_case_subscribers_model');
    $func=($this->uri->segment(4)=='unsubscribe')?$this->uri->segment(4):'view';
    $this->data['content']=$this->backoffice_popup_case_subscribers_model->$func($table,$content,$page_name);
  }

  /* popup case portfolios */
  private function popup_case_portfolios($table,$content,$page_name)
  {
   $this->load->model('backoffice_popup_case_portfolios_model');
   $func=($this->uri->segment(4)!='')?$this->uri->segment(4):'view';
   $this->data['content']=$this->backoffice_popup_case_portfolios_model->$func($table,$content,$page_name);
  }
  
    /* popup function */
    function popup()
    {
        self::check_login();
        $this->page_name='popup';
        $content['new']=true;
        $page_name=$this->page_name;

            switch($this->uri->segment(3))
            {
                case 'pages_menu':
                    $content['header']=$this->lang->line('admin_pages_popup_header');
                    $table='pages_menu';
                    $_SESSION['back_cat']=$this->subdir.'/'.$this->uri->segment(1).'/pages/';
                    self::popup_case_pages_menu($table,$content,$page_name);
                break;

                case 'disclosure_of_information_menu':
                    $content['header']=$this->lang->line('admin_pages_popup_header');
                    $table='disclosure_of_information_menu';
                    $_SESSION['back_cat']=$this->subdir.'/'.$this->uri->segment(1).'/disclosure_of_information/';
                    self::popup_case_disclosure_of_information_menu($table,$content,$page_name);
                break;
            
                case 'downloads_menu':
                    $content['header']=$this->lang->line('admin_pages_popup_header');
                    $table='downloads_menu';
                    $_SESSION['back_cat']=$this->subdir.'/'.$this->uri->segment(1).'/downloads/';
                    self::popup_case_downloads_menu($table,$content,$page_name);
                break;
                
                case 'roles':
                  $content['header']=$this->lang->line('admin_roles_popup_header');
                  $table='roles';
                  $_SESSION['back_cat']=$this->subdir.'/'.$this->uri->segment(1).'/permissions/';
                  self::popup_case_roles($table,$content,$page_name);
                break;
                
                case 'subscribers':
                  $content['header']=$this->lang->line('admin_subscribers_popup_header');
                  $table='mailing_subscriptions';
                  $_SESSION['back_cat']=$this->subdir.'/'.$this->uri->segment(1).'/mailing_sections';
                  self::popup_case_subscribers($table,$content,$page_name);
                break;
                
                case 'portfolios':
                  $content['header']=$this->lang->line('admin_portfolios_popup_header');
                  $table='portfolios';
                  $_SESSION['back_cat']=$this->subdir.'/'.$this->uri->segment(1).'/portfolio_history/';
                  self::popup_case_portfolios($table,$content,$page_name);
                break;
                
                default:
                    $content['header']='';
                    $this->data['content']='';
            }

        $this->load->view('backoffice_'.$page_name.'_index',$this->data);
    }
    
    /* lang select */
    function lang_select()
    {
        $url=isset($_SESSION['save_last_url'])?$_SESSION['save_last_url']:'';
        $this->global_model->lang_select($this->uri->segment(3),$url);
    }
    
    private function save_last_url()
    {
        $_SESSION['save_last_url']=$this->phpself.$this->page_name;
    }

    private function set_default_menu_and_get_header($menu,$page) {
      
      $ret = '';
      if (isset($menu)) {
        
        foreach($menu as $mk=>$mv) {
          
          if (isset($mv[$page])) {
            
            $mv[$page]->sel = ' class="sel"';
            $ret = $mk.': '.$mv[$page]->name; 
            break;
          }
        }
      }
      return $ret;
    }
    
    /* home function */
    function home()
    {
        self::check_login();
        $this->page_name='home';
        self::save_last_url();

        $content['header'] = $this->lang->line('admin_home_header');
           
        $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
            
        $content['data']=array();
            
        foreach($this->data['menu'] as $mk=>$mv){
                  
          foreach ($mv as $k=>$v) {
            
            if ($k!='home') {
                  
              $tmp = new stdClass();
              $tmp->url = $k;
              $tmp->name = $mk.': '.$v->name;

              $content['data'][] = $tmp;
            }
          }
              
        }

        $this->data['content']=$this->load->view('backoffice_'.$this->page_name.'_view',$content,true);

        $this->load->view('backoffice_index',$this->data);
    }
    
    function pages()
    {
        self::check_login();
        $this->page_name='pages';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        $this->cat_id=(int)$this->uri->segment(4);
        $_SESSION['back_cat']='/'.$this->uri->segment(1).'/'.$this->page_name.'/cat/'.$this->cat_id;
        
        $this->load->model('backoffice_pages_model');
            
            $func=($this->uri->segment(3)!='' and $this->uri->segment(3)!='cat')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_pages_model->$func();

        $this->load->view('backoffice_index',$this->data);  
    }
    
    function news()
    {
        self::check_login();
        $this->page_name='news';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_news_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_news_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function press_about_us()
    {
        self::check_login();
        $this->page_name='press_about_us';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_press_about_us_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_press_about_us_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }

    function disclosure_of_information()
    {
        self::check_login();
        $this->page_name='disclosure_of_information';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        $this->cat_id=(int)$this->uri->segment(4);
        $this->cat_sid=(int)$this->uri->segment(5);
        $_SESSION['back_cat']='/'.$this->uri->segment(1).'/'.$this->page_name.'/cat/'.$this->cat_id;
        
            $this->load->model('backoffice_disclosure_of_information_model');
            $func=($this->uri->segment(3)!='' and $this->uri->segment(3)!='cat')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_disclosure_of_information_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function issuers_debt_market()
    {
        self::check_login();
        $this->page_name='issuers_debt_market';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_issuers_debt_market_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_issuers_debt_market_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }

    function trade_ideas()
    {
        self::check_login();
        $this->page_name='trade_ideas';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
        $this->load->model('backoffice_trade_ideas_model');
        $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view_new';
        //$_SESSION['trade_idea_lang']=$_REQUEST['lang'];
        $this->data['content']=$this->backoffice_trade_ideas_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
  
  function recommendations()
    {
        self::check_login();
        $this->page_name='recommendations';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_recommendations_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_recommendations_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function commodities()
    {
      self::check_login();
      $this->page_name='commodities';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
    
      $this->load->model('backoffice_commodities_model');
      $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_commodities_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }
    
    function planned_placements()
    {
      self::check_login();
      $this->page_name='planned_placements';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
    
      $this->load->model('backoffice_planned_placements_model');
      $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_planned_placements_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }

    function dividend_calendar()
    {
      self::check_login();
      $this->page_name='dividend_calendar';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
    
      $this->load->model('backoffice_dividend_calendar_model');
      $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_dividend_calendar_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }
    

    function conferencies()
    {
        self::check_login();
        $this->page_name='conferencies';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        $this->cat_id=(int)$this->uri->segment(4);
        
            $this->load->model('backoffice_conferencies_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_conferencies_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function questions()
    {
        self::check_login();
        $this->page_name='questions';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        $this->cat_id=(int)$this->uri->segment(4);
        
            $this->load->model('backoffice_questions_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_questions_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }    
    
    function feedback()
    {
        self::check_login();
        $this->page_name='feedback';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_feedback_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_feedback_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function downloads()
    {
        self::check_login();
        $this->page_name='downloads';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        $this->cat_id=(int)$this->uri->segment(4);
        $this->cat_sid=(int)$this->uri->segment(5);
        $_SESSION['back_cat']='/'.$this->uri->segment(1).'/'.$this->page_name.'/cat/'.$this->cat_id;
        
            $this->load->model('backoffice_downloads_model');
            $func=($this->uri->segment(3)!='' and $this->uri->segment(3)!='cat')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_downloads_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }

    function permissions()
    {
      self::check_login();
      $this->page_name='permissions';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
      
      $this->role_id=(int)$this->uri->segment(4);
      $_SESSION['back_role']='/'.$this->uri->segment(1).'/'.$this->page_name.'/role/'.$this->role_id;
    
      $this->load->model('backoffice_permissions_model');
      $func=($this->uri->segment(3)!='' and $this->uri->segment(3)!='role')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_permissions_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }
    
    function analytics_news()
    {
        self::check_login();
        $this->page_name='analytics_news';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_analytics_news_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_analytics_news_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function analytics_team()
    {
        self::check_login();
        $this->page_name='analytics_team';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_analytics_team_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_analytics_team_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function logins()
    {
      self::check_login();
      $this->page_name='logins';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
    
      $this->load->model('backoffice_logins_model');
      $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_logins_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }
    

    function contacts()
    {
        self::check_login();
        $this->page_name='contacts';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_contacts_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_contacts_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function share_market_daily_comments()
    {
        self::check_login();
        $this->page_name='share_market_daily_comments';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_share_market_daily_comments_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_share_market_daily_comments_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }

    function share_market_reviews_issuers()
    {
        self::check_login();
        $this->page_name='share_market_reviews_issuers';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_share_market_reviews_issuers_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_share_market_reviews_issuers_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }

    function share_market_special_comments()
    {
        self::check_login();
        $this->page_name='share_market_special_comments';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_share_market_special_comments_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_share_market_special_comments_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function share_market_trading_ideas()
    {
        self::check_login();
        $this->page_name='share_market_trading_ideas';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_share_market_trading_ideas_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_share_market_trading_ideas_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function share_market_model_portfolio()
    {
        self::check_login();
        $this->page_name='share_market_model_portfolio';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_share_market_model_portfolio_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_share_market_model_portfolio_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function analytics_calendar_statistics()
    {
        self::check_login();
        $this->page_name='analytics_calendar_statistics';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_analytics_calendar_statistics_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_analytics_calendar_statistics_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function debt_market_surveys_on_emitter()
    {
        self::check_login();
        $this->page_name='debt_market_surveys_on_emitter';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_debt_market_surveys_on_emitter_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_debt_market_surveys_on_emitter_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }    
    
    function analytics_reviews()
    {
        self::check_login();
        $this->page_name='analytics_reviews';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_analytics_reviews_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_analytics_reviews_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function dictionary()
    {
        self::check_login();
        $this->page_name='dictionary';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_dictionary_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_dictionary_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }
    
    function settings()
    {
        self::check_login();
        $this->page_name='settings';
        self::save_last_url();
        //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
        //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
        $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
        
            $this->load->model('backoffice_settings_model');
            $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
            $this->data['content']=$this->backoffice_settings_model->$func();

        $this->load->view('backoffice_index',$this->data);   
    }

    function quik_news()
    {
      self::check_login();
      $this->page_name='quik_news';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
    
      $this->load->model('backoffice_quik_news_model');
      $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_quik_news_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }

    function analytics_reviews_groups()
    {
      self::check_login();
      $this->page_name='analytics_reviews_groups';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
    
      $this->load->model('backoffice_analytics_reviews_groups_model');
      $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_analytics_reviews_groups_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }
    
    function mailing_sections()
    {
      self::check_login();
      $this->page_name='mailing_sections';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
    
      $this->load->model('backoffice_mailing_sections_model');
      $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_mailing_sections_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }

    function application_forms()
    {
      self::check_login();
      $this->page_name='application_forms';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
    
      $this->load->model('backoffice_application_forms_model');
      $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_application_forms_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }

    function portfolio_history() {
      
      self::check_login();
      $this->page_name = 'portfolio_history';
      self::save_last_url();
      //$this->tpl_header = $this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel'] = ' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
      $this->portfolio_id = (int)$this->uri->segment(4);
      $_SESSION['back_portfolio'] = '/'.$this->uri->segment(1).'/'.$this->page_name.'/portfolio/'.$this->portfolio_id;
    
      $this->load->model('backoffice_portfolio_history_model');
      $s = $this->uri->segment(3);
      $func = ($s!='' && $s!='portfolio')?$s:'view';
      if ($func=='view') {
        $this->data['content']=$this->backoffice_portfolio_history_model->$func($this->portfolio_id);
      } else {
        $this->data['content']=$this->backoffice_portfolio_history_model->$func();
      }
      $this->load->view('backoffice_index',$this->data);
    }
    
    function search_keys() {
      
      self::check_login();
      $this->page_name = 'search_keys';
      self::save_last_url();
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
      $this->group = $this->uri->segment(4);
      
      $this->load->model('backoffice_search_keys_model');
      $s = $this->uri->segment(3);
      $func = ($s!='' && $s!='group')?$s:'view';
      if ($func=='view') {
        $this->data['content']=$this->backoffice_search_keys_model->$func($this->group);
      } else {
        $this->data['content']=$this->backoffice_search_keys_model->$func();
      }
      $this->load->view('backoffice_index',$this->data);
    }
    
    function emitents() {
      
      self::check_login();
      $this->page_name='emitents';
      self::save_last_url();
      //$this->tpl_header=$this->data['menu'][$this->page_name]['name'];
      //$this->data['menu'][$this->page_name]['sel']=' class="sel"';
      $this->tpl_header = $this->set_default_menu_and_get_header($this->data['menu'],$this->page_name);
    
      $this->load->model('backoffice_emitents_model');
      $func=($this->uri->segment(3)!='')?$this->uri->segment(3):'view';
      $this->data['content']=$this->backoffice_emitents_model->$func();
    
      $this->load->view('backoffice_index',$this->data);
    }
    
    function hide_message() {
      
      $data = new stdClass();
      $data->success = $this->global_model->clear_message();

      echo(@json_encode($data));
    }
    
################################################################################################################
  
}
?>