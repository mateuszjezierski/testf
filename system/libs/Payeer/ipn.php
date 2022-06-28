<?php
define('BASEPATH', true);
require('../../init.php');

if (!in_array(VisitorIP(), array('185.71.65.92', '185.71.65.189', '149.202.17.210'))) return;
if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
{
	$arHash = array(
		$_POST['m_operation_id'],
		$_POST['m_operation_ps'],
		$_POST['m_operation_date'],
		$_POST['m_operation_pay_date'],
		$_POST['m_shop'],
		$_POST['m_orderid'],
		$_POST['m_amount'],
		$_POST['m_curr'],
		$_POST['m_desc'],
		$_POST['m_status']
	);

	if (isset($_POST['m_params']))
	{
		$arHash[] = $_POST['m_params'];
	}

	$arHash[] = $config['payeer_secret'];
	$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

	if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success')
	{
		$payee_account 		= $db->EscapeString($_POST['m_shop']);
		$payment_amount 	= $db->EscapeString($_POST['m_amount']);
		$payment_units		= $db->EscapeString($_POST['m_curr']);
		$order_id					= $db->EscapeString($_POST['m_orderid']);
		$txn_id					= $db->EscapeString($_POST['m_operation_id']);

		$deposit = $db->QueryFetchArray("SELECT * FROM `deposits` WHERE `method`='2' AND `id`='".$order_id."' LIMIT 1");

		if(!empty($deposit['id']) && $payment_amount >= $deposit['amount'])
		{
			$db->Query("UPDATE `deposits` SET `txn_id`='".$txn_id."', `status`='1' WHERE `id`='".$deposit['id']."'");	
			$db->Query("UPDATE `users` SET `purchase_balance`=`purchase_balance`+'".$deposit['amount']."' WHERE `id`='".$deposit['user_id']."'");	

			add_notification($deposit['user_id'], 5, $deposit['amount']);
		}

		echo $_POST['m_orderid'].'|success';
		exit;
	}

	echo $_POST['m_orderid'].'|error';
}
?>