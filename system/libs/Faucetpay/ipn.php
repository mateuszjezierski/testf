<?php
define('BASEPATH', true);
require('../../init.php');

$token = $_POST['token'];
$payment_info = file_get_contents("https://faucetpay.io/merchant/get-payment/" . $token);
$payment_info = json_decode($payment_info, true);
$token_status = $payment_info['valid'];

if ($config['faucetpay_username'] == $payment_info['merchant_username'] && $token_status == true) 
{
	$payment_amount 	= $db->EscapeString($payment_info['amount1']);
	$txn_id					= $db->EscapeString($token);
	
	$get_data = explode('|', $payment_info['custom']);
	$user_id 		= $db->EscapeString($get_data[0]);
	$deposit_id	= $db->EscapeString($get_data[1]);
	$user_ip		= $db->EscapeString($get_data[2]);
	
	$user = $db->QueryFetchArray("SELECT `id` FROM `users` WHERE `id`='".$user_id."' LIMIT 1");
	$deposit = $db->QueryFetchArray("SELECT * FROM `deposits` WHERE `method`='1' AND `id`='".$deposit_id."' AND `status`!='1' LIMIT 1");

	if(!empty($user['id']) && !empty($deposit['id']) && $payment_amount >= $deposit['amount'])
	{
		$db->Query("UPDATE `users` SET `purchase_balance`=`purchase_balance`+'".$payment_amount."' WHERE `id`='".$user['id']."'");	
		$db->Query("UPDATE `deposits` SET `amount`='".$payment_amount."', `txn_id`='".$txn_id."', `status`='1', `time`='".time()."' WHERE `id`='".$deposit['id']."'");	
	
		add_notification($user['id'], 5, $payment_amount);	
	}

	echo $payment_info['custom'].'|success';
	exit;
}
else
{
	echo $payment_info['custom'].'|error';
}
?>