<?

set_include_path(':'.dirname(__FILE__).':'.get_include_path());

require_once 'consts.php';

set_include_path(':'.dirname(dirname(__FILE__)).'/libs:'.get_include_path());

require_once 'log.php';
require_once 'mysql.php';
require_once 'utils.php';

function get_needs($db,$exclude_currency_id) {
  
  $ret = array();
  
  $portfolios = $db->getRecords(sprintf('select distinct(currency_id) as id from portfolios where currency_id<>%s',
                                        $db->quote($exclude_currency_id)));
  if (is_array($portfolios) && sizeOf($portfolios)>0) {
    
    foreach($portfolios as $p) {
      
      $ret[] = $p['id'];
    }
  }
  return $ret;
}

$log = new Log (LOG_EXCHANGE_RATES_IMPORT,true,true,false);
if ($log) {
  $stamp = microtime(true);

  $log->writeInfo(str_repeat('-',50));
  try {
    $log->writeInfo('Connect to Database ...');

    $db = new Mysql($log,DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if ($db) {
      
      $log->writeInfo('Connected.');
      $db->setCharset('utf8');
      
      $url = sprintf('http://www.cbr.ru/scripts/XML_daily.asp?date_req=%s',date('d/m/Y'));
      $r = @file_get_contents($url);
      if ($r) {
        
        $xml = @simplexml_load_string($r);
        if ($xml) {
          
          $needs = get_needs($db,'RUR');
          if (is_array($needs) && sizeOf($needs)>0) {
            
            foreach($xml->Valute as $v) {
              
              $code = (string)$v->CharCode;
              if (in_array($code,$needs)) {
  
                $nominal = (string)$v->Nominal;
                $nominal = str_replace(',','.',$nominal);
  
                $value = (string)$v->Value;
                $value = str_replace(',','.',$value);
  
                $data['from_currency_id'] = $code;
                $data['from_value'] = $nominal;
                $data['to_currency_id'] = 'RUR';
                $data['to_value'] = $value;
                $r = $db->insertRecord('exchange_rates',$data);
              }
            }
          }
        }
      }
      unset ($db);
    }
  } catch (Exception $e) {
    $log->writeException($e);
  }
  $log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
  unset($log);
}

?>