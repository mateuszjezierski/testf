<?php
	define('BASEPATH', true);
	require('../../init.php');
	require('autoload.php'); 

	use CoinbaseCommerce\Webhook;

	$headerName = 'X-Cc-Webhook-Signature';
	$headers = getallheaders();
	$signraturHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
	$payload = trim(file_get_contents('php://input'));

	try {
		$event = Webhook::buildEvent($payload, $signraturHeader, $config['coinbase_secret']);
	} catch (\Exception $exception) {
		echo 'Error occured. ' . $exception->getMessage();
		exit;
	}

	if($event['type'] == 'charge:confirmed' && $event['data']['metadata']['script_id'] == 'afs_ultimate')
	{
		$user_id 		= $db->EscapeString($event['data']['metadata']['user_id']);
		$deposit_id	= $db->EscapeString($event['data']['metadata']['deposit_id']);
		$user_ip		= $db->EscapeString($event['data']['metadata']['user_ip']);
		
		$user = $db->QueryFetchArray("SELECT `id` FROM `users` WHERE `id`='".$user_id."' LIMIT 1");
		$deposit = $db->QueryFetchArray("SELECT * FROM `deposits` WHERE `method`='3' AND `id`='".$deposit_id."' AND `status`!='1' LIMIT 1");

		if(!empty($user['id']) && !empty($deposit['id']))
		{
			$db->Query("UPDATE `users` SET `purchase_balance`=`purchase_balance`+'".$deposit['amount']."' WHERE `id`='".$user['id']."'");	
			$db->Query("UPDATE `deposits` SET `amount`='".$deposit['amount']."', `txn_id`='".$event['id']."', `status`='1', `time`='".time()."' WHERE `id`='".$deposit['id']."'");	
		
			add_notification($user['id'], 5, $deposit['amount']);	
		}

		echo 'SUCCESS';
	}
	else
	{
		echo 'ERROR';
	}
?>