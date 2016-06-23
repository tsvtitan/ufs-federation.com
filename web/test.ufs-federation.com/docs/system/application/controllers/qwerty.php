<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_include_path(':/var/www/ufs-federation.com/docs/system:'.get_include_path());

require_once $GLOBALS['application_folder'].'/libraries/controller.php';
require_once $GLOBALS['application_folder'].'/libraries/Service.php';


class Qwerty extends ControllerEx {

  public function index() {
    
    $service = new Service();
    $service->setAuth('tsv');
    
    $mailing = new Mailing();
    $mailing->setSender('tsv@ufs-financial.ch','ttt'); 
    $mailing->subject = 'Проверка сообщения 2';
    $mailing->body = 'Текст письма';
    
    $mailing->addRecipient('tsv@ufs-federation.com','ddd');
    $mailing->addRecipient('tefin@mail.ru','www');
    
    $mailing->addHeader('Reply-To','tefin@mail.ru');
    
    $mailing->channel = 'ExchangeOutgoing';
    $mailing->pattern = 'EmptyPattern';
    $mailing->prioriy = 777;
    $mailing->test = true;
    
    $mailing->delay = 10;
    $mailing->duration = 30;
    
    $mailing->addAttachment('Проверка вложения','txt','Проверка вложения данные','text/plain');
    
    $mailing->addKeyword('Проверка1');
    $mailing->addKeyword('Проверка2');
    
    
    $r = $service->sendMailing($mailing);
    if ($r) {
      echo(var_dump($r));
    }
  }
}

?> 