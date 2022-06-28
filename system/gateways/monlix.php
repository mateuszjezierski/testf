<?php
    define('BASEPATH', true);
    require('../init.php');

    $userId = isset($_REQUEST['userid']) ? $db->EscapeString($_REQUEST['userid']) : null;
    $survey = isset($_REQUEST['transactionid']) ? $db->EscapeString($_REQUEST['transactionid']) : null;
    $reward = isset($_REQUEST['reward']) ? $db->EscapeString($_REQUEST['reward']) : null;
	$payout = isset($_REQUEST['payout']) ? $db->EscapeString($_REQUEST['payout']) : null;
    $action = isset($_REQUEST['status']) ? $db->EscapeString($_REQUEST['status']) : null;
    $userIP = isset($_REQUEST['userip']) ? $db->EscapeString($_REQUEST['userip']) : '0.0.0.0';
    $country = isset($_REQUEST['country']) ? $db->EscapeString($_REQUEST['country']) : null;
	
	// Security Check
	$secret = $db->QueryFetchArray("SELECT config_value FROM `offerwall_config` WHERE `config_name`='monlix_secret'");
	$secret = $secret['config_value'];
	
    // validate signature
    if ($secret != $db->EscapeString($_REQUEST['secret'])) {
        echo "ERROR: Signature doesn't match";
        return;
    }

	if($action == 2 && !empty($userId))
	{
		$user = $db->QueryFetchArray("SELECT `id` FROM `users` WHERE `id`='".$userId."'");
		$db->Query("UPDATE `users` SET `ow_credits`=`ow_credits`-'".$reward."' WHERE `id`='".$user['id']."'");
	}
    elseif(!empty($userId) && $db->QueryGetNumRows("SELECT * FROM `completed_offers` WHERE `survey_id`='".$survey."' LIMIT 1") == 0)
    {
        $user = $db->QueryFetchArray("SELECT `id` FROM `users` WHERE `id`='".$userId."'");
        if(!empty($user['id'])) 
		{
            $tc_points = (0.10*($payout*100));
            $tc_points = ($tc_points < 1 ? 1 : number_format($tc_points, 0));
            $db->Query("UPDATE `users` SET `ow_credits`=`ow_credits`+'".$reward."', `tasks_contest`=`tasks_contest`+'".$tc_points."' WHERE `id`='".$user['id']."'");
            $db->Query("INSERT INTO `users_offers` (`uid`,`total_offers`,`total_revenue`,`last_offer`) VALUES ('".$user['id']."','1','".$reward."','".time()."') ON DUPLICATE KEY UPDATE `total_offers`=`total_offers`+'1', `total_revenue`=`total_revenue`+'".$reward."', `last_offer`='".time()."'");
			$db->Query("INSERT INTO `completed_offers` (`user_id`,`survey_id`,`user_country`,`user_ip`,`revenue`,`reward`,`method`,`timestamp`) VALUES ('".$user['id']."','".$survey."','".$country."','".$userIP."','".$payout."','".$reward."','monlix','".time()."')");
		}

        echo "OK";
    }
?>