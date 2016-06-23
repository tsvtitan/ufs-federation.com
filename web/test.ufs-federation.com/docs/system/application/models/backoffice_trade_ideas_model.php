<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/system/libraries/simple_html_dom.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_item_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_type_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_tool_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_level_value_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_level_type_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/application/models/backoffice_trade_idea_data_type_model.php');

class backoffice_trade_ideas_model extends Model{

        var $language_array =
                array('ru' => 'рус', 'en' => 'eng');
        
        var $group_name =
                array('ru' => array ('Еврооблигации' => 'евро', 'Локальный рынок' => 'рубл'),
                      'en' => array ('Eurobonds' => 'евро', 'Local market' => 'рубл'),
                      'de' => array ('Eurobonds' => 'евро', 'Lokale markt' => 'рубл'),
                      'it' => array ('Mercato locale' => 'евро', 'Marché local' => 'рубл'),
                      'fr' => array ('Eurobonds' => 'евро', 'Lokale markt' => 'рубл')
        );
	function backoffice_trade_ideas_model()
	{
		parent::Model();

        if(isset($_REQUEST['form_search']))
        {
            $_SESSION['search']=mysql_string($_REQUEST['search']);
        }else{
          if(!isset($_SESSION['search'])){
            $_SESSION['search']='';
          }
        }
        
        if(!isset($_SESSION['issuers_debt_market_type'])){
           $_SESSION['issuers_debt_market_type']='euro'; 
        }
        
        $this->load->model('backoffice_analytics_reviews_model');
        $this->load->model('global_model');
	}
    
    
    function add() {
    }
    
    function edit() {
    }
    
    function del() {
    }
    
    function set_type()
    {
        switch($this->uri->segment(4))
        {
            case 'euro':
                $_SESSION['trade_idea_type']='евро';                
                break;
            case 'local':
                $_SESSION['trade_idea_type']='рубл';
            break;
         }
       return $this->view_new();  
       //header('Location: '.$_SESSION['save_last_url']);
       //die();
    }
    
    // Алгоритм загрузки №2 (до 05.2014)
    function load_analytics_from_file($file,$extension='',$empty=false) {
      
      $ret = false;
      
      if (($data = $this->global_model->get_file_data($file, $extension))) {
        
        $trade_idea_type = new trade_idea_type();
        $trade_idea_data_type = new trade_idea_data_type();
        $trade_idea_level_type = new trade_idea_level_type();    

        foreach ($data as $sheet=>$v) {

          $table = '';
          $where = '';
          $rows = array();
          $extra = array();
          $repeats = array();
          $empty_as_update = false;

          $idea_attributes = explode(' ', $v['name']);

          $trade_idea_type_name = $idea_attributes[0];
          $trade_idea_type_id = $trade_idea_type->get_where( array('type_name' => $trade_idea_type_name) )->id;//array_search($trade_idea_type_name, $trade_idea_type_array);
          $trade_idea_lang = array_search(str_replace('\'', '', $idea_attributes[1]), $this->language_array);
          $trade_idea_group_name = array_search($idea_attributes[2], $this->group_name[$trade_idea_lang]);

          $tmp = null;

          $item = new trade_idea_item();

          if($empty) {
            $result = $item->where(
                    array( 'finished is NULL' => NULL, 
                           'language' => $trade_idea_lang ) )->update(
                                    'finished', date('Y-m-d H:i:s') );
            $empty = false;
          }

          switch($trade_idea_type_name){
            case 'идеи': default:
              $step = 6;
              break;
            case 'спред-идеи':
              $step = 10;
              break;
          }

          $priority = 1;

          for ($r=6; $r<(sizeOf($v)-1); $r=$r+$step) {

              $c = 1;

              // Дополнительная проверка
              if($v[$r][$c]->data == ""){
                break;
              }

              switch($trade_idea_type_name) {
                // идеи
                case 'идеи':

                  $item = new trade_idea_item();
                  $item->priority = $priority;
                  $item->caption = $v[$r][$c]->data;
                  $item->comment = $v[$r+1][$c+4]->data;
                  $item->type_id = $trade_idea_type_id;
                  $arr = explode(":", $v[$r][$c+4]->data);
                  $item->recommend_date = date("Y-m-d", strtotime(trim($arr[1])));
                  //На случай, если дата будет в отдельном поле
                  //date("Y-m-d", $this->getUnixDate((int)$v[$r+6][$c]->data));;
                  $item->group_name = $trade_idea_group_name;
                  $item->language = $trade_idea_lang;
                  $success = $item->save();
                  if(! $success) {
                    continue;
                  }

                  $tool = new trade_idea_tool();
                  $tool->item_id = $item->id;
                  $tool->name = $v[$r+2][$c]->data;
                  $tool->operation = $v[$r+1][$c+1]->data;
                  $tool->isin = $v[$r+3][$c]->data;
                  $tool->rating = $v[$r+4][$c]->data;
                  $tool->save();

                  for ($i=2;$i<=4;$i=$i+1) {

                    $trade_idea_data_type_id = $trade_idea_data_type->get_where( array('data_type_name' => trim($v[$r+$i][$c+1]->data)) )->id;

                    for ($j=$c+2;$j<=$c+3;$j=$j+1) {

                      $level_value = new trade_idea_level_value();
                      $level_value->data_type_id = $trade_idea_data_type_id;
                      $level_value->level_value = $v[$r+$i][$j]->data;
                      $level_value->level_type_id = $trade_idea_level_type->get_where( array('level_type' => trim($v[5][$j]->data)) )->id;
                      $level_value->save();
                      $tool->save_level_value($level_value);

                    }
                  }

                  $level_value = new trade_idea_level_value();
                  $arr = explode(":", $v[$r][$c+3]->data);
                  $level_value->data_type_id = $trade_idea_data_type->get_where( array('data_type_name' => trim($arr[0])) )->id; //trim($v[$r+2][$c+1]->data
                  $level_value->level_value = trim($arr[1]);
                  $level_value->level_type_id = $trade_idea_level_type->get_where( array('level_type' => $this->lang->line('admin_trade_ideas_level_target')) )->id;
                  $level_value->save();
                  $item->save_level_value($level_value);

                  break;

                // спред-идеи
                case 'спред-идеи':

                  $item = new trade_idea_item();
                  $item->priority = $priority;
                  $item->caption = $v[$r][$c]->data;
                  $item->comment = $v[$r+1][$c+4]->data;
                  $item->type_id = $trade_idea_type_id;
                  $arr = explode(":", $v[$r][$c+4]->data);
                  $item->recommend_date = date("Y-m-d", strtotime(trim($arr[1])));
                  $item->group_name = $trade_idea_group_name;
                  $item->language = $trade_idea_lang;
                  $success = $item->save();

                  if(! $success) {
                    continue;
                  }

                  for ($tool_ind=0; $tool_ind<=4; $tool_ind=$tool_ind+4) {

                    $tool = new trade_idea_tool();
                    $tool->item_id = $item->id;
                    $tool->name = $v[$r+2+$tool_ind][$c]->data;
                    $tool->operation = $v[$r+1+$tool_ind][$c]->data;
                    $tool->isin = $v[$r+3+$tool_ind][$c]->data;
                    $tool->rating = $v[$r+4+$tool_ind][$c]->data;
                    $tool->save();

                    for ($i=2;$i<=4;$i=$i+1) {

                      $trade_idea_data_type_id = $trade_idea_data_type->get_where( array('data_type_name' => trim($v[$r+$i+$tool_ind][$c+1]->data)) )->id;

                      for ($j=$c+2;$j<=$c+3;$j=$j+1) {

                        $level_value = new trade_idea_level_value();
                        $level_value->data_type_id = $trade_idea_data_type_id;
                        $level_value->level_value = $v[$r+$i+$tool_ind][$j]->data;
                        $level_value->level_type_id = $trade_idea_level_type->get_where( array('level_type' => trim($v[5][$j]->data)) )->id;
                        $level_value->save();
                        $tool->save_level_value($level_value);

                      }
                    }                    
                  }

                  $trade_idea_data_type_id = $trade_idea_data_type->get_where( array('data_type_name' => trim($v[$r+9][$c+1]->data)) )->id;

                  for ($j=$c+2;$j<=$c+3;$j=$j+1) {

                    $level_value = new trade_idea_level_value();
                    $level_value->data_type_id = $trade_idea_data_type_id;
                    $level_value->level_value = $v[$r+9][$j]->data;;
                    $level_value->level_type_id = $trade_idea_level_type->get_where( array('level_type' => trim($v[5][$j]->data)) )->id;
                    $level_value->save();
                    $item->save_level_value($level_value);

                  }
                  
                  $trade_idea_data_type_id = null;

                  $level_value = new trade_idea_level_value();
                  $arr = explode(":", $v[$r][$c+3]->data);
                  $level_value->data_type_id = $trade_idea_data_type->get_where( array('data_type_name' => trim($arr[0])) )->id;                  
                  $level_value->level_value = trim($arr[1]);
                  $level_value->level_type_id = $trade_idea_level_type->get_where( array('level_type' => $this->lang->line('admin_trade_ideas_level_target')) )->id;
                  $level_value->save();
                  $item->save_level_value($level_value);

                  break;
              }
              
              $priority++;
            }
        }
        $ret = true;
        
      }
      return $ret;  
    }
    
    function import_xls() {
        
      if(isset($_REQUEST['submit'])){
            
        if($_FILES['_file']['error']==0){

          error_reporting(E_ALL ^ E_NOTICE);
                
          $exel_file = $_FILES['_file']['tmp_name'];
          $exel_type = explode('.',$_FILES['_file']['name']);
          $exel_type = end($exel_type);
                
          //$ret = $this->global_model->load_analytics_from_file($_FILES['_file']['tmp_name'],$exel_type,isset($_REQUEST['del_all']),'trading_ideas');
          $ret = $this->load_analytics_from_file($_FILES['_file']['tmp_name'],$exel_type,isset($_REQUEST['del_all']));
          if ($ret) {
            $_SESSION['admin']->is_update=1;
          }
        } 

        return redirect($this->uri->segment(1).'/'.$this->page_name);
        
      } else {

        return $this->load->view('backoffice_'.$this->page_name.'_import_xls','',true);
      }
    }

    function export_xls() {
    }
    
    function view()
    {
      $content['data']='';
        
      if(isset($_SESSION['admin']->is_update)){
          $content['is_update']=$this->lang->line('admin_tpl_page_updated');
          unset($_SESSION['admin']->is_update);
      }

      $where='';

      if(!empty($_SESSION['search']))
      {
          $where.=' and `t`.`name` like "%'.$_SESSION['search'].'%"';
      }


      $vals='count("t") as Rows';
      $limit=''; 
      $sql_r=$this->db->query(self::view_sql_str($vals,$where,$limit));

      if($sql_r->num_rows()>0){	
          $res_r=$sql_r->result();
          $res_r=$res_r[0];

          /* pager */ 
          //$show=20;
          //$rows=$res_r->Rows;
          //$ret_page=((int)$this->uri->segment(5)==0)?1:(int)$this->uri->segment(5);
          //$param['url']='/view';

          //$pages=$this->global_model->Pager($show,$rows,$param,$ret_page);
          /*********/

          $vals='`t`.`trade_idea_id`, `t`.`group_name`, `t`.`name`, `t`.`isin`, `t`.`rating`, `t`.`yield`, 
                 `t`.`price`, `t`.`description`';
          //$limit='limit '.$pages['start'].','.$pages['show']; 
          $sql=$this->db->query(self::view_sql_str($vals,$where,$limit));       

              if($sql->num_rows()>0){	
                  $res=$sql->result();	

                      /*********************************/
                       $res=$this->global_model->adjustment_of_results($res,$this->page_name);
                       $res=$this->global_model->rand_css_class($res);
                       //$res=$this->global_model->date_format($res,'','mdate','maturity_date');
                       //$res=$this->global_model->date_format($res,'','next_coupon','next_coupon');
                      /*********************************/

                  //$content['pages']=$pages['html'];
                  $content['data']=$res;
              }                
        }
        $content['trade_idea_group_name'] = $this->group_name;
        return $this->load->view('backoffice_'.$this->page_name.'_view',$content,true);
    }
    
    function view_new(/*$lang, $currency*/)
    {
      if(ISSET($_SESSION['trade_idea_type'])) {
        $group_name = array_search($_SESSION['trade_idea_type'], $this->group_name[$this->site_lang]);
      } else {
        $group_name = array_search('евро', $this->group_name[$this->site_lang]);        
        $_SESSION['trade_idea_type'] = 'евро';
      }
      
      $trade_idea_item = new trade_idea_item();
      $trade_idea_item->where(array('language' => $this->site_lang,
                                    'group_name' => $group_name,
                                    'finished is null' => null))->get_iterated();
    
      $content['data']=$trade_idea_item;
      $s = 'backoffice_'.$this->page_name.'_view_new';
      return $this->load->view($s,$content,true);
    }
    
    private function view_sql_str($vals,$where,$limit)
    {
        $sql='select '.$vals.'
              from `'.$this->page_name.'` as `t`
              where `t`.`lang`="'.$this->site_lang.'" and `t`.`finished` is null  
              '.$where.'
              order by `t`.`priority`
              '.$limit.';';
        
        return $sql;
    }
    
    private function xls_reader($file)
    {
      $arr=array();
        
            include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/excel_reader2.php');
            $xls = new Spreadsheet_Excel_Reader();

            $xls->read($file);


            for($r=$xls->sheets[0]['numRows'];$r>=4;$r--)
            {
               $c=1;

                $arr[$r]['text']['ticker']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['name']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['isin']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['sector']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['price_currency']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['price_fair']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['potential']=rtrim($xls->sheets[0]['cells'][$r][++$c],'%');
                $arr[$r]['text']['recommendation']=$xls->sheets[0]['cells'][$r][++$c];
            }
        
        return $arr;
    }
    
    private function xlsx_reader($file)
    {
      $arr=array();
        
            include_once($_SERVER['DOCUMENT_ROOT'].'/system/excel/simplexlsx.class.php');
            $obj = new SimpleXLSX($file);
            
            $xlsx=$obj->rows();

            for($r=(count($xlsx)-1);$r>=2;$r--)
            {
               $c=0;

                $arr[$r]['text']['ticker']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['name']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['isin']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['sector']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['price_currency']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['price_fair']=$xls->sheets[0]['cells'][$r][++$c];
                $arr[$r]['text']['potential']=rtrim($xls->sheets[0]['cells'][$r][++$c],'%');
                $arr[$r]['text']['recommendation']=$xls->sheets[0]['cells'][$r][++$c];
               
            }
        
        return $arr;
    }
    
  function make_review() {
    
    $data = array();
    $lang = $this->site_lang;
    
    /*$r = $this->global_model->get_table_data('trade_ideas',null,array('lang'=>$lang,'finished'=>null),array('priority'));
    if ($r)*/ {
      //$r = $this->global_model->data_to_class($r);
      
      $urls = array('ru'=>'/pages/analitika/dolgovoyi-runok/torgovue-idei-new.html?trade_idea_id=',
                    'en'=>'/pages/research/debt-market/429-torgovue-idei-new.html?trade_idea_id=',
                    'de'=>'/pages/analytik/anleihenmarkt/handelsideen-new.html?trade_idea_id=');
      
      if (isset($urls[$lang])) {
        
        /*$groups = array();
        $old_group_name = '';
        $counter = 0;
        foreach($r as $item) {
          if ($item->group_name!=$old_group_name) {
            $group = new stdClass();
            $group->name = $item->group_name;
            $group->items = array();
            $groups[] = $group;
            $counter++;
            $old_group_name = $group->name;
          }
          if (isset($group)) {
            $path = sprintf($urls[$lang],$item->trade_idea_id);
            $item->url = sprintf('http://%s%s',$this->global_model->get_host(),$path); 
            $group->items[] = $item;
          }
        }*/
        
        $lang = isset($_REQUEST['lang'])?$_REQUEST['lang']:$this->site_lang;

        $idea_data = new trade_idea_item();
        $idea_data->where(array('language' => $lang,
                                'finished is null' => null)
                         )->order_by('language, group_name, priority')->get(); 
                     
        $data['data'] = $idea_data; 
        $data['lang'] = $lang;
        $data['url'] = $urls[$lang];
        $data['date'] = date('d.m.Y');
        
        $text = $this->load->view('backoffice_'.$this->page_name.'_review_new',$data,true);
        if (trim($text)!='') {
          
          $table = 'analytics_reviews';
          $names = array('ru'=>'Торговые идеи на долговом рынке',
                         'en'=>'Trading Ideas in the Bond Market',
                         'de'=>'Trading Ideas in the Bond Market');
          
          $review['name'] = $names[$lang];
          $review['url'] = $this->global_model->SET_title_url($review['name'],$table);
          $review['extra_mailing_text'] = $text;
          $review['lang'] = $lang;
          $review['only_mailing'] = 1;
          $review['use_template'] = 0;
          
          $r = $this->global_model->insert($table,$review);
          if ($r) {
            
            $review_id = $this->db->insert_id();
            
            $sections = array('ru'=>array(12),
                              'en'=>array(18),
                              'de'=>array(29));
            
            foreach($sections[$lang] as $id) {
              $review_sections['analytics_review_id'] = $review_id;
              $review_sections['mailing_section_id'] = $id;
              $this->global_model->insert('analytics_reviews_sections',$review_sections);
            }
            
            $pages = array('ru'=>array(338),
                           'en'=>array(432),
                           'de'=>array(477));
            
            foreach($pages[$lang] as $id) {
              $review_pages['analitic_review_id'] = $review_id;
              $review_pages['page_id'] = $id;
              $review_pages['lang'] = $lang;
              $this->global_model->insert('analytics_reviews_pages',$review_pages);
            }
            
            //$this->backoffice_analytics_reviews_model->try_mailing($review_id);

            $s = $this->phpself.'analytics_reviews';
            header('Location: '.$s,null,301);
            die();
          }
        }
      }
    }
  }
}
?>
