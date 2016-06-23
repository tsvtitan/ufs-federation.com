<?php 

require_once 'package.php';
require_once 'log.php';
require_once 'utils.php';

define ('R_TYPE_SUCCESS','success');
define ('R_TYPE_INTERNAL_ERROR','internal_error');
define ('PACKAGE_RESULT','result');
define ('MAX_RND_LENGTH',3*1024);
define ('LOCAL_KEY','79C5868D7808345E524DF0587021BDDB');
define ('REMOTE_KEY','1DE1E46E5B48C7065F6FE64936DFAE91');

class Connection  {

	public $url = '';
	private $log = null;
	private $local_key = LOCAL_KEY;
	private $remote_key = REMOTE_KEY;
	
	public function __construct($log,$url) {
		
		$this->url = $url;
		$this->log = $log; 
	}
	
	private function log($message) {
	  if (isset($this->log))
	  	$this->log->writeInfo($message);	
	}
	
	private function send($package) {
		
		$ret=false;
		try {
		  if ($package) {
			$package->add('rnd')->value = base64_encode(random_string(MAX_RND_LENGTH));
			$xml = $package->saveXml();
			if ($xml) {
				//file_put_contents('/home/ufs-federa/mailing/logs/data.xml',$xml); 
				$xml = encode_string($this->local_key,$xml);
				$options = array ('http'=>array('method'=>'POST',
				  	                            'content'=>$xml,
				                                'header'=>'Content-type: text/xml'));
				$context = @stream_context_create($options);
				$url = $this->url;
				$response = @file_get_contents($url,false,$context);
				if ($response) {
					$xml = decode_string($this->remote_key,$response); 
					$ret = new Package(PACKAGE_RESULT);
					$ret->loadXml($xml);
				} else
					$this->log(sprintf('Could not send to %s',$url));
			  }
		  }	  
		} catch (Exception $e) {
			$ret = false; 
			$this->log($e->getMessage());
		}
		return $ret;
	}
	
	public function sendmail($name,$email,$params,$subject,$body,$headers,&$error) {
		
		$ret = false;
		$out = new Package(__FUNCTION__);
		
		$a = $out->add('data');
		
		$a->attributes['name'] = base64_encode($name);
		$a->attributes['email'] = base64_encode($email);

		$a->childs->add('params')->value = base64_encode($params);
		$a->childs->add('subject')->value = base64_encode($subject);
		$a->childs->add('body')->value = base64_encode($body);
		$a->childs->add('headers')->value = base64_encode($headers);
		
		$in = $this->send($out);
		if ($in) {
			
			$type = $in->find('type');
			if (($type) && (isset($type->value))) {
				
				switch ($type->value) {
					case R_TYPE_SUCCESS: {
						$ret = true;
						break;
					}
					case R_TYPE_INTERNAL_ERROR: {
						$e = $in->find('error');
						if ($e) {
							if (isset($e->value)) {
								$error = $e->value;
							}
						}
						break;
					}
				}
			}
		}
		return $ret;
	}
	
	public function sendmails(&$emails,$subject,$body,$headers,&$error) {
		
		$ret = false;
		$out = new Package(__FUNCTION__);
		
		$a = $out->add('data');
		$a->childs->add('subject')->value = base64_encode($subject);
		$a->childs->add('body')->value = base64_encode($body);
		$a->childs->add('headers')->value = base64_encode($headers);
		
		foreach($emails as $e) {
		  
		  $i = $a->childs->add('item',false);
		  $i->attributes['id'] = $e['mailing_id'];
		  $i->attributes['name'] = base64_encode($e['name']); 
		  $i->attributes['email'] = base64_encode($e['email']);
		  $i->childs->add('params')->value = base64_encode($e['params']);
		}

		$in = $this->send($out);
		if ($in) {
			
			$diff = $in->stamp - $out->stamp;
			
			$type = $in->find('type');
			if (($type) && (isset($type->value))) {
				
				switch ($type->value) {
					case R_TYPE_SUCCESS: {
						$data = $in->find('data');
						if ($data) {
							foreach ($data->childs as $d) {
								if ($d->name='item') {
									foreach ($emails as &$e) {
										if ($d->attributes['id']==$e['mailing_id']) {
										    if (trim($d->attributes['sent'])!='') {
										        $t = strtotime($d->attributes['sent']);
														$t = $t - $diff;
											    	$e['sent'] = date('Y-m-d H:i:s',$t);
												}	
												$e['result'] = $d->attributes['result']; 
										}
									}
								}
							}
						}
						$ret = true;
						break;
					}
					case R_TYPE_INTERNAL_ERROR: {
						$e = $in->find('error');
						if ($e) {
							if (isset($e->value)) {
								$error = $e->value;
							}
						}
						break;
					}
				}
			}
		}
		return $ret;
	}
	
}

?>