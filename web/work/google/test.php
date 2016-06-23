<?php

require_once 'consts.php';
require_once 'log.php';
require_once 'utils.php';
//require_once 'google.php';
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_PredictionService.php';
require_once 'Zend/Loader.php';

$log = new Log (LOG_GOOGLE,true);
if ($log) {

	$stamp = microtime(true);
	$log->writeInfo(str_repeat('-',50));
	try {

		$log->writeInfo('Connecting to Google ...');
		
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Gdata_Gapps');
		
		$client = Zend_Gdata_ClientLogin::getHttpClient('dev@ufs-federation.com','9ABPli8zv6oX0kOslt7g',Zend_Gdata_Gapps::AUTH_SERVICE_NAME);
		if ($client) {
			
			$log->writeInfo('Connected. Getting access...');
			
			$gdata = new Zend_Gdata_Gapps($client,'ufs-federation.com');
			if ($gdata) {

				$group_id = 'iddqd@ufs-federation.com';
				
				$log->writeInfo('Searching a previous group...');
				
				$group = $gdata->retrieveGroup($group_id);
				if ($group) {
					
					$log->writeInfo('Found previous group. Deleting...');
					
					$gdata->deleteGroup($group_id);
					
					$log->writeInfo('Deleted.');
				} 
				
				$log->writeInfo('Creating...');
				
				$group = $gdata->createGroup($group_id,'iddqd',null,'Owner');
				if ($group) {
					
					$log->writeInfo('Created. Adding members...');
					
					/*$gdata->addMemberToGroup('imv@ufs-federation.com',$group_id);
					$log->writeInfo('Added #1.');
					
					$gdata->addMemberToGroup('tsv@ufs-federation.com',$group_id);
					$log->writeInfo('Added #2.');
					
					$gdata->addMemberToGroup('tsv@nextsoft.ru',$group_id);
					$log->writeInfo('Added #3.');
					
					$gdata->addMemberToGroup('tefin@mail.ru',$group_id);
					$log->writeInfo('Added #4.');*/
					
					$member = $gdata->newMemberEntry();
					
					$properties[] = $gdata->newProperty();
					$properties[0]->name = 'memberId';
					$properties[0]->value = 'tsv@nextsoft.ru';
						
					$member->property = $properties;
					
					$uri  = $gdata::APPS_BASE_FEED_URI . $gdata::APPS_GROUP_PATH . '/';
					$uri .= $gdata->getDomain() . '/' . $group_id . '/member';
					
					$ret = $gdata->insertMember($member, $uri);

					if ($ret) {
						
					}
					
					$log->writeInfo('Sending email...');
					@mail($group_id,'Message from '.$group_id,'This is test message');
					
				}
				
/*				$feed = $gdata->retrieveAllGroups();
				if ($feed) {
					
					foreach($feed->entry as $entry) {
						
						foreach($entry->property as $p) {

							$log->writeInfo('Name: '.$p->name.' Value: '.$p->value);
						}
					}
				}*/
			
			} 
			
			
		}
		
/*		$client = new Google_Client();
		if ($client) {
			$client->setApplicationName('Test application');
			
			$key = file_get_contents('/install/php/work/google/key1.p12');
			
			$creds = new Google_AssertionCredentials('582203553586@developer.gserviceaccount.com',
					                                       array('https://www.googleapis.com/auth/prediction'),
					                                       $key);
			$client->setAssertionCredentials($creds);
			$client->setClientId('582203553586.apps.googleusercontent.com');
			
			$service = new Google_PredictionService($client);


			// Prediction logic:
			$id = 'sample.languageid';
			$predictionData = new Google_InputInput();
			$predictionData->setCsvInstance(array('Je suis fatigue'));

			$input = new Google_Input();
			$input->setInput($predictionData);

			$result = $service->hostedmodels->predict($id, $input);
			print '<h2>Prediction Result:</h2><pre>' . print_r($result, true) . '</pre>';

			$token = $client->getAccessToken();
			
		//	$auth = $client->authenticate($token);

			
			
			unset($client);
		} */
		
		/*$google = new Google($log);
		if ($google) {
			
			

			unset($google);
		}*/
	} catch (Exception $e) {
		$log->writeException($e);
	}
	$log->writeInfo(sprintf('Execution time is %s',microtime(true)-$stamp));
	unset($log);
}

?>