<?php
define('BASEPATH', true);
define('IS_AJAX', true);
require('init.php');
require('libs/Coinbase/autoload.php'); 

use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;

if($is_online)
{
	if(isset($_GET['a']))
	{
		switch ($_GET['a']) {
			case 'calculatePTC':
				if(isset($_GET['pack']) && is_numeric($_GET['pack']) && isset($_GET['visits']) && is_numeric($_GET['visits']))
				{
					$pID = $db->EscapeString($_GET['pack']);
					$visits = $db->EscapeString($_GET['visits']);
					$redirect = ($_GET['redirect'] == 1 ? 1 : 0);
					$ad_pack = $db->QueryFetchArray("SELECT `price` FROM `ptc_packs` WHERE `id`='".$pID."' LIMIT 1");

					$value = $visits * $ad_pack['price'];
					if($redirect)
					{
						$value = $value + ($value/100*$config['ptc_redirect_price']);
					}

					echo '$'.number_format($value, 4);
				}
				else
				{
					echo '$0.0000';
				}

				break;
			case 'calculateRefs':
				if(isset($_GET['refs']) && is_numeric($_GET['refs']))
				{
					$price = $_GET['refs'] * $config['market_price'];

					echo '$'.number_format($price, 2);
				}
				else
				{
					echo '$0.00';
				}

				break;
			case 'getReward':
				if(is_numeric($_GET['rID'])){
					$rID = $db->EscapeString($_GET['rID']);
					$reward = $db->QueryFetchArray("SELECT a.*, b.membership AS mem_name FROM activity_rewards a LEFT JOIN memberships b ON b.id = a.membership WHERE a.id = '".$rID."' LIMIT 1");

					$leads = $db->QueryFetchArray("SELECT `total_offers` FROM `users_offers` WHERE `uid`='".$data['id']."' LIMIT 1");
					$refs = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE `ref`='".$data['id']."'");

					$type = 'error';
					$msg = $lang['l_212'];
					if(!empty($reward['id']))
					{
						if($reward['req_type'] == 0 && $reward['requirements'] > $data['total_claims']){
							$type = 'error';
							$msg = lang_rep($lang['l_209'], array('-NUM-' => number_format($reward['requirements'])));
						}elseif($reward['req_type'] == 1 && $reward['requirements'] > $data['sl_total']){
							$type = 'error';
							$msg = lang_rep($lang['l_202'], array('-NUM-' => number_format($reward['requirements'])));
						}elseif($reward['req_type'] == 2 && $reward['requirements'] > $leads['total_offers']){
							$type = 'error';
							$msg = lang_rep($lang['l_488'], array('-NUM-' => number_format($reward['requirements'])));
						}elseif($reward['req_type'] == 3 && $reward['requirements'] > $refs['total']){
							$type = 'error';
							$msg = lang_rep($lang['l_489'], array('-NUM-' => number_format($reward['requirements'])));
						}elseif($db->QueryGetNumRows("SELECT * FROM `activity_rewards_claims` WHERE `reward_id`='".$reward['id']."' AND `user_id`='".$data['id']."' LIMIT 1") > 0){
							$type = 'error';
							$msg = $lang['l_213'];
						}else{
							if($reward['type'] == 1)
							{
								if($data['membership'] == 0) 
								{
									$premium = time()+(86400*$reward['reward']);
									$db->Query("UPDATE `users` SET `membership`='".$premium."', `membership_id`='".$reward['membership']."' WHERE `id`='".$data['id']."'");
								}
								else 
								{
									$premium = ((86400*$reward['reward'])+$data['membership']);
									$db->Query("UPDATE `users` SET `membership`='".$premium."' WHERE `id`='".$data['id']."'");
								}
							}
							elseif($reward['type'] == 2)
							{
								$satoshi = ($reward['reward']/100000000);
								$db->Query("UPDATE `users` SET `purchase_balance`=`purchase_balance`+'".$satoshi."' WHERE `id`='".$data['id']."'");
							}
							else
							{
								$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$reward['reward']."', `total_revenue`=`total_revenue`+'".$reward['reward']."' WHERE `id`='".$data['id']."'");
							}

							$db->Query("UPDATE `activity_rewards` SET `claims`=`claims`+'1' WHERE `id`='".$reward['id']."'");
							$db->Query("INSERT INTO `activity_rewards_claims` (`reward_id`,`user_id`,`reward`,`type`,`date`)VALUES('".$reward['id']."','".$data['id']."','".$reward['reward']."','".$reward['type']."','".time()."')");

							$type = 'success';
							$msg = lang_rep($lang['l_214'], array('-REWARD-' => ($reward['type'] == 1 ? number_format($reward['reward'], 0).' '.($data['membership_id'] == $reward['membership'] ? $reward['mem_name'] : ($data['membership_id'] > 1 ? $data['mem_name'] : $reward['mem_name'])).' '.$lang['l_234'] : number_format($reward['reward']).' '.$lang['l_337'])));
						}
					}

					$resultData = array('message' => $msg, 'type' => $type);

					header('Content-type: application/json');
					echo json_encode($resultData);
				}
					
				break;
			case 'bannerPacks':
				$type = ($_GET['type'] == 1 ? 1 : 0);
				$adpack = ($_GET['pack'] == 1 ? 1 : ($_GET['pack'] == 2 ? 2 : 0));
				$position = ($_GET['position'] == 1 ? 1 : 0);
				$packs = $db->QueryFetchArrayAll("SELECT * FROM `ad_packs` WHERE `pack`='".$adpack."' AND `position`='".$position."' AND `type`='".$type."' ORDER BY `price` ASC");
				foreach($packs as $pack)
				{
					if($adpack == 1)
					{
						echo '<option value="'.$pack['id'].'">'.$pack['value'].' '.$lang['l_189'].' - $'.$pack['price'].'</option>';
					}
					elseif($adpack == 2)
					{
						echo '<option value="'.$pack['id'].'">'.$pack['value'].' '.$lang['l_188'].' - $'.$pack['price'].'</option>';
					}
					else
					{
						echo '<option value="'.$pack['id'].'">'.$pack['value'].' '.$lang['l_234'].' - $'.$pack['price'].'</option>';
					}
				}

				break;
			case 'calcWithdraw':
				$coin = $db->EscapeString($_GET['coin']);
				$amount = $db->EscapeString($_GET['amount']);
			
				$fiat_value = ($amount*$config['bits_rate']);
				$crypto = number_format((1 / $coin_value[$coin]) * $fiat_value, 8, '.', '');

				$resultData = array('message' => '');
				if($fiat_value > 0 )
				{
					$resultData = array('message' => 'You will receive '.$crypto.' '.strtoupper($coin).' ($'.$fiat_value.')');
				}

				header('Content-type: application/json');
				echo json_encode($resultData);
					
				break;
			case 'calcEarnings':
				$payout = (isset($_GET['payout']) ? $db->EscapeString($_GET['payout']) : null);
				$coinPay = (isset($_GET['currency']) ? $db->EscapeString($_GET['currency']) : null);
				$frequency = (isset($_GET['frequency']) ? $db->EscapeString($_GET['frequency']) : null);
				$boost = ($_GET['boost'] < 1 ? 1 : ($_GET['boost'] > $config['auto_faucet_boost'] ? $config['auto_faucet_boost'] : $_GET['boost']));
				
				$resultData = '';
				if(isset($_GET['token']) && $_GET['token'] === $_SESSION['auto_faucet_token'])
				{
					$level_multiplier = userLevel($data['id'], 3, $data['total_claims']);
					$frequencies = unserialize($config['auto_faucet_frequency']);
					
					$gateway = ($payout == 2 ? 'faucetpay' : false);
					if ($gateway != false)
					{
						$currencies = array();
						if(!empty($coinPay)) {
							foreach($coinPay as $coin) {
								$currencies[] = $db->QueryFetchArray("SELECT * FROM `coins` WHERE `coin`='".$coin."' AND `".$gateway."`='1' AND `status`='1'");
							}
						}
					}
					
					if(empty($payout) || empty($frequency) || empty($boost) || !is_numeric($payout) || !is_numeric($frequency) || !is_numeric($boost))
					{
						$resultData = array('status' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please select your payout method, frequency and boost!</div>'); 
					}
					elseif(empty($frequencies[$frequency][1]))
					{
						$resultData = array('status' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please select a valid claim frequency!</div>');
					}
					elseif($gateway != false && $data['direct_crypto_auto'] == 0)
					{
						$resultData = array('status' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_541'].'</div>');
					}
					elseif($gateway != false && empty($currencies))
					{
						$resultData = array('status' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please select your desired currency!</div>');
					}
					elseif($payout == 1)
					{
						$costs = $frequencies[$frequency][1] * $config['auto_faucet_price'] * $boost;
						$multiplier = ($data['multiplier'] + $level_multiplier) - 1;
						$earnings = (($frequencies[$frequency][1] + ($frequencies[$frequency][1] / 100 * $config['auto_faucet_cp_bonus']) + ($frequencies[$frequency][0] / 100 * $frequencies[$frequency][1])) * $boost) * $multiplier;
						$earnings = number_format($earnings, 2, '.', '');
						
						$fiat_value = number_format($earnings*$config['bits_rate'], 4, '.', '');
						$resultData = array('status' => 200, 'message' => '<div class="alert alert-info" role="alert">'.$earnings.' coins ($'.$fiat_value.') will be sent to your account every '.$frequencies[$frequency][1].' minutes for '.$costs.' Faucet Tokens!</div>');
					}
					elseif($payout == 2)
					{
						if($payout == 2 && empty($data['fp_email']))
						{
							$resultData = array('status' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please set your FaucetPay Email address on <a href="'.GenerateURL('account').'">Withdrawal Settings</a>!</div>');
						}
						else
						{
							$result = '';
							foreach($currencies as $currency)
							{
								$costs = $frequencies[$frequency][1] * $config['auto_faucet_price'] * $boost;
								$multiplier = ($data['multiplier'] + $level_multiplier) - 1;
								$fiat_value = (($frequencies[$frequency][1] + ($frequencies[$frequency][0]/100)) * $boost) * $multiplier;
								$fiat_value = number_format(($fiat_value * $config['bits_rate']), 8, '.', '');
								$crypto = number_format((1 / $coin_value[$currency['coin']]) * $fiat_value, 8, '.', '');
								
								$result .= '<p><span>'.$crypto.'</span> <span>'.$currency['stock'].'</span> will be sent every '.$frequencies[$frequency][1].' minutes for <span>'.$costs.'</span> <span>Faucet Tokens!</span></p>';
							}

							if(!empty($result))
							{
								$resultData = array('status' => 200, 'message' => '<div class="item text-center">'.$result.'</div>');
							}
						}
					}
					else
					{
						$resultData = array('status' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please select your payout method!</div>'); 
					}
				}
				else
				{
					$resultData = array('status' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>'); 
				}

				header('Content-type: application/json');
				echo json_encode($resultData);
					
				break;
			case 'manualEarnings':
				$tokens = (isset($_GET['ftokens']) ? $db->EscapeString($_GET['ftokens']) : null);
				$coin = (isset($_GET['currency']) ? $db->EscapeString($_GET['currency']) : null);
				$payout = (isset($_GET['payout']) ? $db->EscapeString($_GET['payout']) : null);
				
				$resultData = array('status' => 0, 'message' => ''); 
				if(isset($_GET['token']) && $_GET['token'] === $_SESSION['token'])
				{
					$level_multiplier = userLevel($data['id'], 3, $data['total_claims']);

					if(empty($tokens) || !isset($payout) || ($payout != 1 && !isset($coin)) || !is_numeric($tokens) || $tokens <= 0)
					{
						$resultData = array('status' => 0, 'message' => ''); 
					}
					elseif($payout == 1)
					{
						$multiplier = ($data['multiplier'] + $level_multiplier) - 1;
						$earnings = ($tokens / $config['manual_faucet_price']) * $multiplier;
						$earnings = number_format($earnings, 2, '.', '');

						if($earnings > 0)
						{
							$resultData = array('status' => 200, 'message' => '<label>Estimated</label><div class="form-control disabled">'.$earnings.' Coins</div>');
						}
					}
					else
					{
						$gateway = ($payout == 2 ? 'faucetpay' : false);
						if ($gateway != false)
						{
							$currency = $db->QueryFetchArray("SELECT * FROM `coins` WHERE `coin`='".$coin."' AND `".$gateway."`='1' AND `status`='1'");
						}
						
						if($gateway != false && !empty($currency['coin']))
						{
							$multiplier = ($data['multiplier'] + $level_multiplier) - 1;
							$fiat_value = ($tokens / $config['manual_faucet_price']) * $multiplier;
							$fiat_value = number_format(($fiat_value * $config['bits_rate']), 8, '.', '');
							$crypto = number_format((1 / $coin_value[$currency['coin']]) * $fiat_value, 8, '.', '');

							if($crypto > 0)
							{
								$resultData = array('status' => 200, 'message' => '<label>Estimated</label><div class="form-control disabled">'.$crypto.' '.$currency['stock'].'</div>');
							}
						}
					}
				}

				header('Content-type: application/json');
				echo json_encode($resultData);
					
				break;
			case 'getCurrencies':
				$resultData = array('status' => 0, 'message' => '');
				if(isset($_GET['token']) && $_GET['token'] === $_SESSION['auto_faucet_token'])
				{
					$gateway = ($_GET['payout'] == 2 ? 'faucetpay' : false);
					if ($gateway != false)
					{
						$getCurrencies = $db->QueryFetchArrayAll("SELECT * FROM `coins` WHERE `".$gateway."`='1' AND `status`='1'");
					}

					$result = '';
					if($gateway != false)
					{
						foreach($getCurrencies as $currency)
						{
							$result .= '<div class="col-4">
										<label class="rwrapper">
											<input type="checkbox" name="currency[]" id="auto-currency" value="'.$currency['coin'].'"> '.$currency['name'].'
										</label>
									</div>';
						}
					}
					
					$resultData = array('status' => 200, 'message' => $result); 
				}

				header('Content-type: application/json');
				echo json_encode($resultData);
					
				break;
			case 'getManualCurrencies':
				$resultData = array('status' => 0, 'message' => '');
				if(isset($_GET['token']) && $_GET['token'] === $_SESSION['token'])
				{
					$result = '';
					$status = 0;
					if($data['direct_crypto_manual'] == 0)
					{
						$result = '<div class="alert alert-warning" role="alert"><i class="fa fa-exclamation-triangle"></i> You have to upgrade your membership to be able to claim direct crypto! <a href="'.GenerateURL('membership').'">Click here</a> for details...</div>';
						$status = 300;
					}
					else
					{
						$gateway = ($_GET['payout'] == 2 ? 'faucetpay' : false);
						if ($gateway != false)
						{
							$getCurrencies = $db->QueryFetchArrayAll("SELECT * FROM `coins` WHERE `".$gateway."`='1' AND `status`='1'");
						}

						if($gateway != false)
						{
							foreach($getCurrencies as $currency)
							{
								$status = 200;
								$result .= '<option value="'.$currency['coin'].'">'.$currency['name'].' ('.$currency['stock'].')</option>';
							}
						}
					}
					
					$resultData = array('status' => $status, 'message' => $result); 
				}

				header('Content-type: application/json');
				echo json_encode($resultData);
					
				break;
			case 'getRollDice':
				$resultData = array('status' => 0, 'message' => '');
				if(isset($_GET['token']) && $_GET['token'] === $_SESSION['token'])
				{
					$rollID = $db->EscapeString($_GET['id']);
					
					if(!empty($rollID) && is_numeric($rollID))
					{
						$round = $db->QueryFetchArray("SELECT `salt`,`roll` FROM `dice_history` WHERE `user_id`='".$data['id']."' AND `id`='".$rollID."' AND `open`='1' LIMIT 1");
						if(!empty($round))
						{
							$resultData = array('status' => 200, 'secret' => $round['salt'], 'roll' => $round['roll'], 'message' => '');
						}
					}
				}

				header('Content-type: application/json');
				echo json_encode($resultData);
					
				break;
			case 'getRollDiceHash':
				$resultData = array('status' => 0, 'message' => '');
				$roll = $db->EscapeString($_GET['roll']);
				$secret = $db->EscapeString($_GET['secret']);
				
				if(empty($roll) || empty($secret))
				{
					$resultData = array('status' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please provide a valid Secret Key and Roll Number!</div>');
				}
				else
				{
					$resultData = array('status' => 200, 'message' => '<div class="alert alert-success" role="alert"><b>Hash:</b> '.sha1($secret . '+' . $roll).'</div>');
				}

				header('Content-type: application/json');
				echo json_encode($resultData);
					
				break;
		}
	}
	
	if(isset($_POST['a']) && $_POST['a'] == 'endClaim')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_504'].'</div>', 'status' => 0); 
		}
		elseif(!isset($_POST['token']) || $_POST['token'] !== $_SESSION['auto_faucet_token'])
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 0);
		}
		else
		{
			$db->Query("DELETE FROM `faucet_sessions` WHERE `user_id`='".$data['id']."'");
			
			$resultData = array('message' => '<div class="alert alert-warning" role="alert"><i class="fa fa-exclamation-triangle"></i> Claim session was closed, please wait...</div>', 'status' => 200); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'startClaim')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_504'].'</div>', 'status' => 0); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] === $_SESSION['auto_faucet_token'])
		{
			$payout = $db->EscapeString($_POST['payout']);
			$coinPay = empty($_POST['currency']) ? null : $db->EscapeString($_POST['currency']);
			$frequency = $db->EscapeString($_POST['frequency']);
			$boost = $db->EscapeString($_POST['boost']);
			$frequencies = unserialize($config['auto_faucet_frequency']);
			
			$gateway = ($payout == 2 ? 'faucetpay' : false);
			$currencies = array();
			if ($gateway != false)
			{
				if(!empty($coinPay)) {
					foreach($coinPay as $coin) {
						$currency = $db->QueryFetchArray("SELECT `coin` FROM `coins` WHERE `coin`='".$coin."' AND `".$gateway."`='1' AND `status`='1'");
						
						if(!empty($currency['coin']))
						{
							$currencies[] = $currency['coin'];
						}
					}
				}
			}

			if(empty($payout) || empty($frequency) || empty($boost))
			{
				$resultData = array('message' => $payout.'<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please select your payout method, frequency and boost!</div>', 'status' => 0); 
			}
			elseif($gateway != false && $data['direct_crypto_auto'] == 0)
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_541'].'</div>', 'status' => 0);
			}
			elseif($gateway != false && empty($currencies))
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please select your desired currency!</div>', 'status' => 0); 
			}
			elseif($gateway != false && count($currencies) > $config['auto_faucet_max'])
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> You can\'t claim more than '.$config['auto_faucet_max'].' currencies at once!</div>', 'status' => 0); 
			}
			else
			{
				$totalCurrencies = empty($currencies) ? 1 : count($currencies);
				$listCurrencies = empty($currencies) ? null : serialize($currencies);
				$costs = ($frequencies[$frequency][1] * $config['auto_faucet_price'] * $boost) * $totalCurrencies;
				if($costs > $data['tokens'])
				{
					$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> You don\'t have enough Faucet Tokens!</div>', 'status' => 0); 
				}
				else
				{
					$db->Query("INSERT INTO `faucet_sessions`(`user_id`,`payout`,`frequency`,`boost`,`coin`,`time`) VALUES ('".$data['id']."','".$payout."','".$frequency."','".$boost."','".$listCurrencies."','".time()."') ON DUPLICATE KEY UPDATE `payout`='".$payout."', `frequency`='".$frequency."', `boost`='".$boost."', `coin`='".$listCurrencies."', `time`='".time()."'");

					$resultData = array('status' => 200);
				}
			}
		} 
		else
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 0); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'validateClaim')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_504'].'</div>', 'status' => 0); 
		}
		elseif(!isset($_POST['token']) || $_POST['token'] !== $_SESSION['auto_faucet_token'])
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 0);
		}
		else
		{
			$frequencies = unserialize($config['auto_faucet_frequency']);
			$claimSession = $db->QueryFetchArray("SELECT * FROM `faucet_sessions` WHERE `user_id`='".$data['id']."' LIMIT 1");
			$endTime = $claimSession['time'] + ($frequencies[$claimSession['frequency']][1] * 60);
			
			if($endTime > time())
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Session is not yet complete! Please refresh this page and try again.</div>', 'status' => 0); 
			}
			else
			{
				$level_multiplier = userLevel($data['id'], 3, $data['total_claims']);
				$currencies = empty($claimSession['coin']) ? null : unserialize($claimSession['coin']);
				$totalCurrencies = empty($claimSession['coin']) ? 1 : count($currencies);
				$totalCosts = $frequencies[$claimSession['frequency']][1] * $config['auto_faucet_price'] * $claimSession['boost'] * $totalCurrencies;

				if($totalCosts > $data['tokens'])
				{
					$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> You don\'t have enough tokens! Session closed...</div>', 'status' => 0); 
				}
				else
				{
					if($claimSession['payout'] == 1)
					{
						$multiplier = ($data['multiplier'] + $level_multiplier) - 1;
						$earnings = (($frequencies[$claimSession['frequency']][1] + ($frequencies[$claimSession['frequency']][1] / 100 * $config['auto_faucet_cp_bonus']) + ($frequencies[$claimSession['frequency']][0] / 100 * $frequencies[$claimSession['frequency']][1])) * $claimSession['boost']) * $multiplier;
						$earnings = number_format($earnings, 2, '.', '');
						
						$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$earnings."', `tokens`=`tokens`-'".$totalCosts."', `today_revenue`=`today_revenue`+'".$earnings."', `total_revenue`=`total_revenue`+'".$earnings."', `today_claims`=`today_claims`+'1', `total_claims`=`total_claims`+'1' WHERE `id`='".$data['id']."'");
						$db->Query("INSERT INTO `faucet_claims`(`user_id`,`tokens`,`reward`,`payout`,`coin`,`type`,`time`) VALUES ('".$data['id']."','".$totalCosts ."','".$earnings."','".$claimSession['payout']."','".$claimSession['coin']."','1','".time()."')");
						
						if($totalCosts > ($data['tokens'] - $totalCosts))
						{
							$db->Query("DELETE FROM `faucet_sessions` WHERE `user_id`='".$data['id']."'");
						}
						else
						{
							$db->Query("UPDATE `faucet_sessions` SET `payout`='".$claimSession['payout']."', `frequency`='".$claimSession['frequency']."', `boost`='".$claimSession['boost']."', `coin`='".$claimSession['coin']."', `time`='".time()."' WHERE `user_id`='".$data['id']."'");
						}

						$resultData = array('message' => '<div class="alert alert-success" role="alert"><i class="fas fa-check-circle"></i> '.$earnings.' Coins were sent to your account balance! Please wait...</div>', 'status' => 200); 
					}
					elseif($claimSession['payout'] == 2)
					{
						if($data['direct_crypto_auto'] == 0)
						{
							$db->Query("DELETE FROM `faucet_sessions` WHERE `user_id`='".$data['id']."'");
							$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_541'].'</div>', 'status' => 300);
						}
						else
						{
							$multiplier = ($data['multiplier'] + $level_multiplier) - 1;
							$coins = (($frequencies[$claimSession['frequency']][1] + ($frequencies[$claimSession['frequency']][0]/100)) * $claimSession['boost']) * $multiplier;
							$coins = number_format($coins, 2, '.', '');
							$fiat_value = number_format(($coins * $config['bits_rate']), 6, '.', '');
							$totalCoins = $coins * $totalCurrencies;

							$result = '';
							$query = array();
							$failbackCurrencies = array();
							foreach($currencies as $currency)
							{
								$crypto = number_format((1 / $coin_value[$currency]) * $fiat_value, 8, '.', '');
								$satoshi = $crypto * 100000000;

								if($claimSession['payout'] == 2)
								{
									$faucetpay = new FaucetPay($config['fp_api_key'], $currency);
									$fp_result = $faucetpay->send($data['fp_email'], $satoshi);

									if($fp_result['success'] == true)
									{
										$query[] = "('".$data['id']."','".$coins."','".$fiat_value."','".$currency."','".$crypto."','1','".$data['fp_email']."','".$fp_result['payout_id']."','".VisitorIP()."','".time()."','1')";
										$result .= '<div class="alert alert-success" role="alert"><i class="fas fa-check-circle fa-fw"></i> '.$crypto.' '.strtoupper($currency).' was sent to your FaucetPay account!</div>'; 
									}
									else
									{
										$failbackCurrencies[] = $currency;
										$result .= '<div class="alert alert-warning" role="alert"><i class="fas fa-exclamation-triangle fa-fw"></i> System wasn\'t able to proccess '.strtoupper($currency).' payout (wallet may be empty)! '.$coins.' coins were sent to your account!</div>'; 
									}
								}
							}
							
							$failbackQuery = '';
							$resultStatus = 200;
							if(!empty($failbackCurrencies))
							{
								$resultStatus = 300;
								$failbackCoins = $coins * count($failbackCurrencies);
								$failbackQuery = ", `account_balance`=`account_balance`+'".$failbackCoins."'";
							}
							
							$db->Query("INSERT INTO `withdrawals` (`user_id`,`bits`,`amount`,`coin`,`crypto`,`method`,`payment_info`,`payout_id`,`ip_address`,`time`,`status`) VALUES ".implode(',', $query));
							$db->Query("INSERT INTO `faucet_claims`(`user_id`,`tokens`,`reward`,`payout`,`coin`,`type`,`time`) VALUES ('".$data['id']."','".$totalCosts ."','".$totalCoins."','".$claimSession['payout']."','".$claimSession['coin']."','1','".time()."')");
							$db->Query("UPDATE `users` SET `tokens`=`tokens`-'".$totalCosts."'".$failbackQuery.", `today_revenue`=`today_revenue`+'".$totalCoins."', `total_revenue`=`total_revenue`+'".$totalCoins."', `today_claims`=`today_claims`+'1', `total_claims`=`total_claims`+'1' WHERE `id`='".$data['id']."'");

							if($totalCosts > ($data['tokens'] - $totalCosts) || !empty($failbackCurrencies))
							{
								$db->Query("DELETE FROM `faucet_sessions` WHERE `user_id`='".$data['id']."'");
							}
							else
							{
								$db->Query("UPDATE `faucet_sessions` SET `payout`='".$claimSession['payout']."', `frequency`='".$claimSession['frequency']."', `boost`='".$claimSession['boost']."', `coin`='".$claimSession['coin']."', `time`='".time()."' WHERE `user_id`='".$data['id']."'");
							}

							$resultData = array('message' => $result, 'status' => $resultStatus); 
						}
					}
				}
			}
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'getFaucet')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_504'].'</div>', 'status' => 0); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			$payout = $db->EscapeString($_POST['payout']);
			$coin = $db->EscapeString($_POST['currency']);
			$tokens = $db->EscapeString($_POST['ftokens']);

			$gateway = ($payout == 2 ? 'faucetpay' : false);
			if ($gateway != false)
			{
				$currency = $db->QueryFetchArray("SELECT * FROM `coins` WHERE `coin`='".$coin."' AND `".$gateway."`='1' AND `status`='1'");
			}

			$captcha_valid = 1;
			if($config['faucet_recaptcha'] == 1 || $config['faucet_solvemedia'] == 1 || $config['faucet_raincaptcha'] == 1)
			{
				$captcha_valid = 0;
				if($_POST['captcha'] == 1 && $config['faucet_recaptcha'] == 1)
				{
					$recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha_sec']);
					$recaptcha = $recaptcha->verify($_POST['response'], $_SERVER['REMOTE_ADDR']);
				
					if($recaptcha->isSuccess()){
						$captcha_valid = 1;
					}
				}
				elseif($_POST['captcha'] == 0 && $config['faucet_solvemedia'] == 1)
				{
					$solvemedia_response = solvemedia_check_answer($config['solvemedia_v'],$_SERVER["REMOTE_ADDR"],$_POST['challenge'],$_POST['response'],$config['solvemedia_h']);
					if($solvemedia_response->is_valid)
					{
						$captcha_valid = 1;
					}
				}
				elseif($_POST['captcha'] == 2 && $config['faucet_raincaptcha'] == 1)
				{
					$client = new \SoapClient('https://raincaptcha.com/captcha.wsdl');
					$response = $client->send($config['raincaptcha_secret'], $_POST['response'], $_SERVER['REMOTE_ADDR']);
					if ($response->status === 1)
					{
						$captcha_valid = 1;
					}
				}
			}

			if(!$captcha_valid)
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_142'].'</div>', 'status' => 0); 
			}
			elseif($_POST['ftokens'] <= 0)
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> You need to claim at least 1 Faucet Token!</div>', 'status' => 0);
			}
			elseif($_POST['ftokens'] > $data['tokens'])
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> You don\'t have enough Faucet Tokens!</div>', 'status' => 0);
			}
			elseif($_POST['ftokens'] > $config['manual_faucet_max'])
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> You can\'t claim more than '.$config['manual_faucet_max'].' Faucet Tokens at once!</div>', 'status' => 0);
			}
			elseif($payout != 1 && $data['direct_crypto_manual'] == 0)
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_541'].'</div>', 'status' => 0);
			}
			elseif($payout != 1 && empty($currency['coin']))
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please select a valid currency to be paid!</div>', 'status' => 0);
			}
			else
			{
				$coinsQuery = '';
				$validPayout = true;
				$level_multiplier = userLevel($data['id'], 3, $data['total_claims']);
				$multiplier = ($data['multiplier'] + $level_multiplier) - 1;
				$coins = ($tokens / $config['manual_faucet_price']) * $multiplier;
				$coins = number_format($coins, 2, '.', '');

				if($coins <= 0)
				{
					$validPayout = false;
				}

				if($payout == 1 && $validPayout)
				{
					$coinsQuery = ", `account_balance`=`account_balance`+'".$coins."'";
					
					$resultData = array('message' => '<div class="alert alert-success" role="alert"><i class="fas fa-check-circle"></i> '.$coins.' Coins were sent to your account balance! Please wait...</div>', 'status' => 200); 
				}
				elseif($payout == 2 && $validPayout)
				{
					$fiat_value = number_format(($coins * $config['bits_rate']), 8, '.', '');
					$crypto = number_format((1 / $coin_value[$currency['coin']]) * $fiat_value, 8, '.', '');
					$satoshi = $crypto * 100000000;
					
					if($satoshi > 0) 
					{
						$coinsPayout = true;
						$faucetpay = new FaucetPay($config['fp_api_key'], $currency['coin']);
						$fp_result = $faucetpay->send($data['fp_email'], $satoshi);

						if($fp_result['success'] == true)
						{
							$db->Query("INSERT INTO `withdrawals` (`user_id`,`bits`,`amount`,`coin`,`crypto`,`method`,`payment_info`,`payout_id`,`ip_address`,`time`,`status`) VALUES ('".$data['id']."','".$coins."','".$fiat_value."','".$currency['coin']."','".$crypto."','1','".$data['fp_email']."','".$fp_result['payout_id']."','".VisitorIP()."','".time()."','1')");
							$coinsPayout = false;
							
							$resultData = array('message' => '<div class="alert alert-success" role="alert"><i class="fas fa-check-circle"></i> '.$crypto.' '.$currency['stock'].' were sent to your FaucetPay account! Please wait...</div>', 'status' => 200); 
						}
						
						if($coinsPayout)
						{
							$payout= 1;
							$coinsQuery = ", `account_balance`=`account_balance`+'".$coins."'";
							
							$resultData = array('message' => '<div class="alert alert-warning" role="alert"><i class="fas fa-check-circle"></i> System wasn\'t able to proccess '.$currency['stock'].' payout (wallet may be empty)! '.$coins.' Coins were sent to your account balance! Please wait...</div>', 'status' => 200); 
						}
					}
					else
					{
						$validPayout = false;
					}
				}

				if($validPayout)
				{
					$db->Query("UPDATE `users` SET `tokens`=`tokens`-'".$tokens."'".$coinsQuery.", `today_revenue`=`today_revenue`+'".$coins."', `total_revenue`=`total_revenue`+'".$coins."', `today_claims`=`today_claims`+'1', `total_claims`=`total_claims`+'1', `last_claim`='".time()."' WHERE `id`='".$data['id']."'");
					$db->Query("INSERT INTO `faucet_claims`(`user_id`,`tokens`,`reward`,`payout`,`coin`,`time`) VALUES ('".$data['id']."','".$tokens ."','".$coins."','".$payout."','".$currency['coin']."','".time()."')");
				}
				else
				{
					$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Tokens amount is to low to be claimed. Please try to claim more tokens!</div>', 'status' => 0); 
				}
			}
		} else {
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 0); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'rollDice')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('status' => 0, 'type' => 'danger', 'message' => '<div class="alert alert-danger" role="alert">'.$lang['l_504'].'</div>'); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			$probability = $db->EscapeString($_POST['multiplier']);
			$betAmount = $db->EscapeString($_POST['betAmount']);
			$rollType = $db->EscapeString($_POST['rollType']);
			
			if($betAmount > $data['account_balance'])
			{
				$resultData = array('status' => 0, 'type' => 'danger', 'message' => $lang['l_406']); 
			}
			elseif($betAmount > $config['dice_max_bet'] || $betAmount < $config['dice_min_bet'])
			{
				$resultData = array('status' => 0, 'type' => 'danger', 'message' => 'Bet must be between '.$config['dice_min_bet'].' - '.$config['dice_max_bet'].' coins'); 
			}
			elseif($probability > 97 || $probability < 2)
			{
				$resultData = array('status' => 0, 'type' => 'danger', 'message' => 'Win chance must be between 2 - 97'); 
			}
			else
			{
				$latestGame = $db->QueryFetchArray("SELECT * FROM `dice_history` WHERE `user_id`='".$data['id']."' AND `open`='0' ORDER BY `id` DESC LIMIT 1");
				if(empty($latestGame))
				{
					$resultData = array('status' => 0, 'type' => 'danger', 'message' => 'An error occurred, please reload'); 
				}
				elseif($latestGame['claim_time'] > (time() - 5))
				{
					$resultData = array('status' => 0, 'type' => 'danger', 'message' => 'Please wait 5 seconds between bets'); 
				}
				else
				{
					$resultData = array();
					$multi = (100 / $probability) * ((100-$config['dice_house_edge'])/100);
					$netProfit = number_format(($betAmount * $multi) - $betAmount, 2, '.', '');
					
					if($rollType == 'rollHi') 
					{
						$target = 100 - $probability;
						$betType = 2;

						if ($latestGame['roll'] > $target)
						{
							$resultData['message'] = 'You Won '.$netProfit.' coins';
							$resultData['type'] = 'success';
						}
						elseif($latestGame['roll'] < $target)
						{
							$netProfit = $betAmount * -1;
							$resultData['message'] = 'You Lost '.$betAmount.' coins';
							$resultData['type'] = 'danger';
						}
					}
					elseif ($rollType == 'rollLo') 
					{
						$target = $probability;
						$betType = 1;

						if($latestGame['roll'] < $target)
						{
							$resultData['message'] = 'You Won '.$netProfit.' coins';
							$resultData['type'] = 'success';
						}
						elseif($latestGame['roll'] > $target)
						{
							$netProfit = $betAmount * -1;
							$resultData['message'] = 'You Lost '.$betAmount.' coins';
							$resultData['type'] = 'danger';
						}
					}

					$resultData['status'] = 200;
					$resultData['userCoins'] = number_format($data['account_balance'] + $netProfit, 2).' '.$lang['l_337'];
					$db->Query("UPDATE `dice_history` SET `target`='".$target."', `bet`='".$betAmount."', `profit`='".$netProfit."', `type`='".$betType."', `open`='1' WHERE `id`='".$latestGame['id']."'");
					$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$netProfit."' WHERE `id`='".$data['id']."'");
					
					$game = diceGame();
					$db->Query("INSERT INTO `dice_history` (`user_id`,`salt`,`roll`,`claim_time`) VALUES ('".$data['id']."','".$game['salt']."','".$game['percent']."','".time()."')");
					$resultData['proof'] = sha1($game['salt'] . '+' . $game['percent']);

					$latestGame = $db->QueryFetchArray("SELECT * FROM `dice_history` WHERE `id`='".$latestGame['id']."' LIMIT 1");
					$resultData['recent'] = [
						'id' => $latestGame['id'],
						'secret' => $latestGame['salt'],
						'target' => ($latestGame['type'] == 1 ? '&lt;' : '&gt;') . $latestGame['target'],
						'bet' => $latestGame['bet'],
						'roll' => $latestGame['roll'],
						'profit' => $latestGame['profit']
					];
				}
			}
		} else {
			$resultData = array('status' => 0, 'type' => 'danger', 'message' => $lang['l_304']); 
		}
		
		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'coinFlip')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('status' => 0, 'type' => 'danger', 'message' => '<div class="alert alert-danger" role="alert">'.$lang['l_504'].'</div>'); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			$betAmount = $db->EscapeString($_POST['betAmount']);
			$coin = $db->EscapeString($_POST['coin']);
			
			if($betAmount > $data['account_balance'])
			{
				$resultData = array('status' => 0, 'type' => 'danger', 'message' => $lang['l_406']); 
			}
			elseif($betAmount > $config['coinflip_max_bet'] || $betAmount < $config['coinflip_min_bet'])
			{
				$resultData = array('status' => 0, 'type' => 'danger', 'message' => 'Bet must be between '.$config['coinflip_min_bet'].' - '.$config['coinflip_max_bet'].' coins'); 
			}
			elseif($coin != 'BTC' && $coin != 'ETH')
			{
				$resultData = array('status' => 0, 'type' => 'danger', 'message' => 'Please bet on BTC or ETH!'); 
			}
			else
			{
				$lastFlip = $db->QueryFetchArray("SELECT `claim_time` FROM `coinflip_history` WHERE `user_id`='".$data['id']."' ORDER BY `id` DESC LIMIT 1");
				
				if($lastFlip['claim_time'] > (time()-5))
				{
					$resultData = array('status' => 0, 'type' => 'danger', 'message' => 'Please wait 5 seconds between bets'); 
				}
				else
				{
					$resultData['status'] = 200;
					$coins = ['BTC', 'ETH'];
					$winAmount = number_format($betAmount * (100 - $config['coinflip_edge']) / 100, 2, '.', '');
					$coinResult = $coins[mt_rand(1, 100) % 2];
					if ($coinResult == $coin) {
						$resultData['message'] = 'You Won '.$winAmount.' coins';
						$resultData['profit'] = $winAmount;
						$resultData['type'] = 'success';
					} else {
						$winAmount = $betAmount * -1;
						$resultData['message'] = 'You Lose '.$betAmount.' coins';
						$resultData['profit'] = '-'.$betAmount;
						$resultData['type'] = 'danger';
					}
					
					$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$winAmount."' WHERE `id`='".$data['id']."'");
					$db->Query("INSERT INTO `coinflip_history` (`user_id`,`coin`,`result`,`bet_amount`,`profit`,`claim_time`) VALUES ('".$data['id']."','".strtoupper($coin)."','".$coinResult."','".$betAmount."','".$winAmount."','".time()."')");

					$resultData['id'] = $db->GetLastInsertId();
					$resultData['coin'] = $coin;
					$resultData['betAmount'] = $betAmount;
					$resultData['result'] = $coinResult;
					$resultData['userCoins'] = number_format($data['account_balance'] + $winAmount, 2).' '.$lang['l_337'];
				}
			}
		} else {
			$resultData = array('status' => 0, 'type' => 'danger', 'message' => $lang['l_304']); 
		}
		
		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'getBonusRoll')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('number' => 0, 'reward' => 0,  'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> '.$lang['l_504'].'</div>', 'status' => 0); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			$captcha_valid = 1;
			if($config['faucet_recaptcha'] == 1 || $config['faucet_solvemedia'] == 1 || $config['faucet_raincaptcha'] == 1)
			{
				$captcha_valid = 0;
				if($_POST['captcha'] == 1 && $config['faucet_recaptcha'] == 1)
				{
					$recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha_sec']);
					$recaptcha = $recaptcha->verify($_POST['response'], $_SERVER['REMOTE_ADDR']);
				
					if($recaptcha->isSuccess()){
						$captcha_valid = 1;
					}
				}
				elseif($_POST['captcha'] == 0 && $config['faucet_solvemedia'] == 1)
				{
					$solvemedia_response = solvemedia_check_answer($config['solvemedia_v'],$_SERVER["REMOTE_ADDR"],$_POST['challenge'],$_POST['response'],$config['solvemedia_h']);
					if($solvemedia_response->is_valid)
					{
						$captcha_valid = 1;
					}
				}
				elseif($_POST['captcha'] == 2 && $config['faucet_raincaptcha'] == 1)
				{
					$client = new \SoapClient('https://raincaptcha.com/captcha.wsdl');
					$response = $client->send($config['raincaptcha_secret'], $_POST['response'], $_SERVER['REMOTE_ADDR']);
					if ($response->status === 1)
					{
						$captcha_valid = 1;
					}
				}
			}
			
			if(!$captcha_valid)
			{
				$resultData = array('number' => 0, 'reward' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> '.$lang['l_142'].'</div>', 'status' => 0); 
			}
			elseif($data['sl_today'] < $config['bonus_roll_sl_required'])
			{
				$resultData = array('number' => 0, 'reward' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> '.lang_rep($lang['l_427'], array('-SUM-' => $config['faucet_sl_required'] - $data['sl_today'])).'</div>', 'status' => 0); 
			}
			elseif($data['bonus_roll_time'] < (time()-($data['bonus_roll']*60)))
			{
				$prize = 0;
				$number = mt_rand(1,99999);
				if($number == 99999) {
					$prize = $config['jackpot_prize'];
				}
				else
				{
					$getPrize = $db->QueryFetchArray("SELECT `reward` FROM `bonus_roll` WHERE `small`<='".$number."' AND `big`>='".$number."' LIMIT 1");
					if(!empty($getPrize['reward'])) 
					{
						$prize = $getPrize['reward'];
					}
				}

				$db->Query("INSERT INTO `bonus_roll_claims` (`user_id`,`number`,`reward`,`time`) VALUES ('".$data['id']."','".$number."','".$prize."','".time()."')");
				$db->Query("UPDATE `users` SET `tokens`=`tokens`+'".$prize."', `bonus_roll_time`='".time()."' WHERE `id`='".$data['id']."'");
				
				if($data['ref'] > 0) {
					$ref_data = $db->QueryFetchArray("SELECT a.last_activity, b.ref_com FROM users a LEFT JOIN memberships b ON b.id = a.membership_id WHERE a.id = '".$data['ref']."' LIMIT 1");
					
					if(!empty($ref_data['last_activity']) && $ref_data['last_activity'] > (time() - ($config['ref_activity']*3600))) {
						$commission = (($ref_data['ref_com']/100)*$prize);
						ref_commission($data['ref'], $data['id'], $commission);
					}
				}
				
				$resultData = array('number' => $number, 'reward' => $prize, 'message' => '<div class="alert alert-success" role="alert"><i class="fa fa-check-circle fa-fw"></i> Congratulations, your lucky number was '.number_format($number).' and you won '.number_format($prize).' Faucet Tokens!</div>', 'status' => 200);
			}
			else
			{
				$resultData = array('number' => 0, 'reward' => 0, 'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> You already claimed your Faucet Tokens, please come back later.</div>', 'status' => 400);
			}
		} else {
			$resultData = array('number' => 0, 'reward' => 0,  'message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> '.$lang['l_304'].'</div>', 'status' => 0); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'proccessDeposit')
	{
		if(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			$amount = $db->EscapeString($_POST['amount']);
			$method = $db->EscapeString($_POST['method']);
			
			if($amount < $config['deposit_min']) {
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Minimum amount to deposit is '.$config['deposit_min'].' '.getCurrency().'.</div>', 'status' => 100);
			}
			else
			{
				$db->Query("INSERT INTO `deposits` (`user_id`,`user_email`,`amount`,`method`,`user_ip`,`time`) VALUES ('".$data['id']."','".$data['email']."','".$amount."','".$method."','".VisitorIP()."','".time()."')");
				$depositID = $db->GetLastInsertId();

				if($method == 1)
				{
					$fp_form = '<form name="faucetpayform" action="https://faucetpay.io/merchant/webscr" method="post">
						<input type="hidden" name="merchant_username" value="'.$config['faucetpay_username'].'">
						<input type="hidden" name="item_description" value="'.('Deposit $'.number_format($amount, 2, '.', '').' to '.$config['site_logo']).'">
						<input type="hidden" name="amount1" value="'.number_format($amount, 2, '.', '').'">
						<input type="hidden" name="currency1" value="USD">
						<input type="hidden" name="currency2" value="">
						<input type="hidden" name="custom" value="'.($data['id'].'|'.$depositID.'|'.VisitorIP()).'">
						<input type="hidden" name="callback_url" value="'.$config['secure_url'].'/system/libs/Faucetpay/ipn.php">
						<input type="hidden" name="success_url" value="'.GenerateURL('deposits', true).'">
						<input type="hidden" name="cancel_url" value="'.GenerateURL('deposits', true).'">
						<input type="submit" name="submit" class="btn btn-success btn-md w-100 mt-1 text-center" value="Click here to proceed">
					</form>';
					
					$resultData = array('message' => $fp_form, 'transaction' => $depositID, 'method' => 1, 'status' => 200);
				}
				elseif($method == 2)
				{
					$s_orderid = $depositID;
					$s_amount = number_format($amount, 2, '.', '');
					$s_curr = 'USD';
					$s_desc = base64_encode('Deposit $'.$s_amount.' to '.$config['site_logo']);

					$arHash = array(
						$config['payeer_key'],
						$s_orderid,
						$s_amount,
						$s_curr,
						$s_desc,
						$config['payeer_secret']
					);
					
					$payeer_form = ' <form name="payeerform" method="GET" action="https://payeer.com/merchant/">
					  <input type="hidden" name="m_shop" value="'.$config['payeer_key'].'">
					  <input type="hidden" name="m_orderid" value="'.$s_orderid.'">
					  <input type="hidden" name="m_amount" value="'.$s_amount.'">
					  <input type="hidden" name="m_curr" value="'.$s_curr.'">
					  <input type="hidden" name="m_desc" value="'.$s_desc.'">
					  <input type="hidden" name="m_sign" value="'.strtoupper(hash('sha256', implode(':', $arHash))).'">
					  <input type="hidden" name="m_process" value="send" />
					  <input type="submit" name="submit" class="btn btn-success btn-md w-100 mt-1 text-center"  value="Click here to proceed">
					 </form>';
					
					$resultData = array('message' => $payeer_form, 'transaction' => $depositID, 'method' => 1, 'status' => 200);
				}
				elseif($method == 3)
				{
					ApiClient::init($config['coinbase_api']);
					$chargeObj = new Charge(
						[
							"name" => 'Deposit Funds',
							"description" => 'Deposit $'.number_format($amount, 2).' to '.$config['site_logo'],
							"metadata" => [
								"script_id" => 'afs_ultimate',
								"user_id" => $data['id'],
								"deposit_id" => $depositID,
								"user_ip" => VisitorIP()
							],
							"local_price" => [
								"amount" => number_format($amount, 2, '.', ''),
								"currency" => 'USD'
							],
							"pricing_type" => 'fixed_price',
							"redirect_url" => GenerateURL('deposits', true),
							"cancel_url" => GenerateURL('deposits', true)
						]
					);

					try {
						$chargeObj->save();
					} catch (\Exception $exception) {
						$result = '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_146'].'</div>';
					}

					if ($chargeObj->id) {
						try {
							$retrievedCharge = Charge::retrieve($chargeObj->id);
							$result = '<a class="btn btn-success btn-md w-100 mt-1 text-center" href="'.$retrievedCharge['hosted_url'].'">Click here to proceed</a>';
						} catch (\Exception $exception) {
							$result = '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_146'].'</div>';
						}
					}
					
					$resultData = array('message' => $result, 'transaction' => $depositID, 'method' => 1, 'status' => 200);
				}
				else
				{
					$cp_form = '<form name="cpform" action="https://www.coinpayments.net/index.php" method="post">
						<input type="hidden" name="cmd" value="_pay">
						<input type="hidden" name="reset" value="1">
						<input type="hidden" name="merchant" value="'.$config['cp_id'].'">
						<input type="hidden" name="item_name" value="Deposit Funds">
						<input type="hidden" name="currency" value="USD">
						<input type="hidden" name="amountf" value="'.number_format($amount, 2, '.', '').'">
						<input type="hidden" name="quantity" value="1">
						<input type="hidden" name="allow_quantity" value="0">
						<input type="hidden" name="want_shipping" value="0">
						<input type="hidden" name="custom" value="'.($data['id'].'|'.$depositID.'|'.VisitorIP()).'">
						<input type="hidden" name="success_url" value="'.GenerateURL('deposits', true).'">
						<input type="hidden" name="cancel_url" value="'.GenerateURL('deposits', true).'">
						<input type="hidden" name="ipn_url" value="'.$config['secure_url'].'/system/libs/CoinPayments/ipn.php">
						<input type="hidden" name="allow_extra" value="0">
						<input type="submit" name="submit" class="btn btn-success btn-md w-100 mt-1 text-center"  value="Click here to proceed">
					</form>';
					
					$resultData = array('message' => $cp_form, 'transaction' => $depositID, 'method' => 1, 'status' => 200);
				}
			}
		}
		else
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 400); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'proccessWithdraw')
	{
		if(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			$method = $db->EscapeString($_POST['method']);
			
			if($method != 1 && $method != 3) {
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please select a valid withdrawal method.</div>', 'status' => 100);
			}
			else
			{
				$withdraw_options = '';
				$currencies = array();
				if($method == 1)
				{
					$currencies = $db->QueryFetchArrayAll("SELECT * FROM `coins` WHERE `status`='1' AND `faucetpay`='1'");
				}
				elseif($method == 3)
				{
					$currencies = $db->QueryFetchArrayAll("SELECT * FROM `coins` WHERE `status`='1' AND `coinbase`='1'");
				}
				
				foreach($currencies as $currency)
				{
					$withdraw_options .= '<option id="'.$currency['coin'].'" value="'.$currency['coin'].'">'.$currency['name'].'</option>';
				}

				$resultData = array('message' => '<div id="withdrawAlert">
				<div class="alert alert-info" role="alert">Please select the amount you want to withdraw.</div></div>
				<form class="justify-content-center" onsubmit="sendWithdraw(); return false;">
					<input type="hidden" value="'.$method.'" id="withdrawMethod" />
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="withdrawCoin">Select Coin</label>
							<select class="form-control custom-select my-1 mr-sm-2" id="withdrawCoin" onchange="calculateWithdraw()">'.$withdraw_options.'</select>
						</div>
						<div class="form-group col-md-6">
							<label for="withdrawAmount">Amount</label>
							<input type="text" class="form-control my-1 mr-sm-2" id="withdrawAmount" oninput="calculateWithdraw()" placeholder="Amount in Coins" required>
						</div>
						<div class="form-group col-md-12">
							<button type="submit" class="btn btn-primary">Withdraw</button>
						</div>
					</div>
					<div id="withdrawResult"></div>
				</form>
				<small id="depositHelp" class="form-text text-muted">Min. withdraw '.$config['withdraw_min'].' coins ($'.($config['withdraw_min'] * $config['bits_rate']).')</small>', 'status' => 200);
			}
		}
		else
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 400); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'sendWithdraw')
	{
		if(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			$coin = $db->EscapeString($_POST['coin']);
			$method = $db->EscapeString($_POST['method']);
			$amount = $db->EscapeString($_POST['amount']);
			$amount = max($amount, 0);

			$fiat_value = number_format($amount*$config['bits_rate'], 6, '.', '');
			$crypto = number_format((1 / $coin_value[$coin]) * $fiat_value, 8, '.', '');
			$satoshi = ($crypto * 100000000);

			if($method != 1 && $method != 3)
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please select a valid withdrawal method.</div>', 'status' => 100);
			}
			elseif($data['total_claims'] < $config['withdraw_min_claims'])
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> You must complete at least <b>'.number_format($config['withdraw_min_claims']).' faucet claims</b>, before being able to withdraw your funds!</div>', 'status' => 700);
			}
			elseif($method == 1 && empty($data['fp_email']))
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please set your <a href="https://faucetpay.io/?r=2233" target="_blank">FaucetPay</a> Email address on <a href="'.GenerateURL('account').'">Withdrawal Settings</a>!</div>', 'status' => 600);
			}
			elseif($method == 3 && empty($data['cb_email']))
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Please set your <a href="https://www.coinbase.com/join/negrea_d" target="_blank">Coinbase</a> Email address on <a href="'.GenerateURL('account').'">Withdrawal Settings</a>!</div>', 'status' => 600);
			}
			elseif($amount > $data['account_balance'])
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> You don\'t have enough coins into account balance!</div>', 'status' => 600);
			}
			elseif($amount < $config['withdraw_min'])
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> You must withdraw atleast '.$config['withdraw_min'].' coins ($'.($config['withdraw_min'] * $config['bits_rate']).').</div>', 'status' => 500);
			}
			else
			{
				$fiat_value = number_format($fiat_value, 2, '.', '');
				$db->Query("UPDATE `users` SET `account_balance`=`account_balance`-'".$amount."' WHERE `id`='".$data['id']."'");
				
				$status = 0;
				$payout_id = '';
				$payment_info = ($method == 1 ? $data['fp_email'] : $data['cb_email']);
				$successMsg = 'Your withdrawal request was successfully received.';
				if($method == 1 && $data['withdraw_wait_time'] == 0) 
				{
					$faucetpay = new FaucetPay($config['fp_api_key'], $coin);
					$fp_result = $faucetpay->send($data['fp_email'], $satoshi);

					if($fp_result['success'] == true)
					{
						$status = 1;
						$payout_id = $fp_result['payout_id'];
						$successMsg = $crypto.' '.strtoupper($coin).' was sent to your FaucetPay account!';
					}
				}
				elseif($method == 3 && $data['withdraw_wait_time'] == 0) 
				{
				    $coinbase = new CoinbaseAPI($config['coinbase_withdraw_api'], $config['coinbase_withdraw_secret']);
                    $cb_result = $coinbase->sendPayment($data['cb_email'], $crypto, $coin, 'Withdrawal from '.$config['site_name']);
							
					if(isset($cb_result['data']['id']) && !empty($cb_result['data']['id']))
					{
						$status = 1;
						$payout_id = $cb_result['data']['id'];
						$successMsg = $crypto.' '.strtoupper($coin).' was sent to your Coinbase account!';
					}
				}
				
				$db->Query("INSERT INTO `withdrawals` (`user_id`,`bits`,`amount`,`coin`,`crypto`,`method`,`payment_info`,`payout_id`,`ip_address`,`time`,`status`) VALUES ('".$data['id']."','".$amount."','".$fiat_value."','".$coin."','".$crypto."','".$method."','".$payment_info."','".$payout_id."','".VisitorIP()."','".time()."','".$status."')");
				
				$resultData = array('message' => '<div class="alert alert-success mb-0" role="alert"><i class="fa fa-check-circle"></i> '.$successMsg.'</div>', 'status' => 200);
			}
		}
		else
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 400); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'sendTransfer')
	{
		if(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'] && $config['transfer_status'] == 1)
		{
			$amount = $db->EscapeString($_POST['amount']);
			$amount = max($amount, 0);
			$funds = number_format($amount*$config['bits_rate'], 2, '.', '');

			if(!is_numeric($amount) || $amount < $config['transfer_min'])
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.lang_rep($lang['l_459'], array('-NUM-' => (int)$config['transfer_min'])).'</div>', 'status' => 500);
			}
			elseif($amount > $data['account_balance'])
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_406'].'</div>', 'status' => 600);
			}
			else
			{
				$db->Query("UPDATE `users` SET `account_balance`=`account_balance`-'".$amount."', `purchase_balance`=`purchase_balance`+'".$funds."' WHERE `id`='".$data['id']."'");
				$db->Query("INSERT INTO `funds_transfers` (`user_id`,`bits`,`funds`,`bits_rate`,`time`) VALUES ('".$data['id']."','".$amount."','".$funds."','".$config['bits_rate']."','".time()."')");

				$resultData = array('message' => '<div class="alert alert-success mb-0" role="alert"><i class="fa fa-check-circle"></i> '.lang_rep($lang['l_460'], array('-BTC-' => $funds, '-BITS-' => number_format($amount, 2))).'</div>', 'status' => 200);
			}
		}
		else
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 400); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'getShortlink')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_504'].'</div>', 'status' => 500); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			$sid = $db->EscapeString($_POST['data']);
			$linkData = $db->QueryFetchArray("SELECT * FROM `shortlinks_config` WHERE `id`='".$sid."' AND `status`='1' LIMIT 1");
			if(empty($linkData['shortlink']) || empty($linkData['password']))
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_482'].'</div>', 'status' => 500);
			}
			else
			{
				$validate = $db->QueryFetchArray("SELECT `count` FROM `shortlinks_done` WHERE `user_id`='".$data['id']."' AND `short_id`='".$linkData['id']."' LIMIT 1");
				if($validate['count'] >= $linkData['daily_limit'])
				{
					$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_482'].'</div>', 'status' => 500);
				}
				else
				{
					$shortLink = false;
					$short_key = false;
					$countLinks = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `shortlinks` WHERE `short_id`='".$linkData['id']."'");
					if($countLinks['total'] < 10) 
					{
						$short_key = GenerateKey(32);
						$return_url = urlencode($config['secure_url'].'/shortlink.php?short_key='.$short_key);
						$api_url = 'http://'.$linkData['shortlink'].'/api?api='.$linkData['password'].'&url='.$return_url.'&alias=CB'.GenerateKey(9);
						$getLink = get_data($api_url);

						if(empty($getLink))
						{
							$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_146'].'</div>', 'status' => 500);
						}
						else
						{
							$getLink = json_decode($getLink, true);
							if($getLink['status'] === 'error' || empty($getLink['shortenedUrl'])) 
							{
								$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_146'].'</div>', 'status' => 500);
							}
							else
							{
								$shortLink = $db->EscapeString($getLink['shortenedUrl']);
								$db->Query("INSERT INTO `shortlinks` (`short_id`,`shortlink`,`hash`,`time`) VALUES ('".$linkData['id']."','".$shortLink."','".$short_key."','".time()."')");
							}
						}
					}
					else
					{
						$getLink = $db->QueryFetchArray("SELECT `shortlink`, `hash` FROM `shortlinks` WHERE `short_id`='".$linkData['id']."' ORDER BY rand() LIMIT 1");
						$shortLink = $getLink['shortlink'];
						$short_key = $getLink['hash'];
					}

					if(!$shortLink || !$short_key)
					{
						$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_146'].'</div>', 'status' => 500);
					}
					else
					{
						$_SESSION['shortlink_key'] = $short_key;
						$db->Query("INSERT INTO `shortlinks_session` (`user_id`,`short_id`,`time`) VALUES ('".$data['id']."','".$linkData['id']."','".time()."') ON DUPLICATE KEY UPDATE `time`='".time()."'");

						$resultData = array('message' => '<div class="alert alert-success" role="alert"><i class="fa fa-check-circle"></i> '.$lang['l_483'].'</div>', 'shortlink' => $shortLink, 'status' => 200);
					}
				}
			}
		}
		else
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 500); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'proccessPTC')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_504'].'</div>', 'status' => 400); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] == $_SESSION['ptc_token'])
		{
			// Initialise captcha
			require('libs/captcha/session.class.php');
			require('libs/captcha/captcha.class.php');
			CBCaptcha::setIconsFolderPath('../../../static/img/captcha/');
			
			if(!CBCaptcha::validateSubmission($_POST)) 
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Captcha error, please try again!</div>', 'status' => 600);
			} 
			else 
			{
				$sid = $db->EscapeString($_POST['data']);
				$sit = $db->QueryFetchArray("SELECT a.id,a.website,a.redirect,b.reward FROM ptc_websites a LEFT JOIN ptc_packs b ON b.id = a.ptc_pack LEFT JOIN ptc_done c ON c.user_id = '".$data['id']."' AND c.site_id = a.id WHERE a.id = '".$sid."' AND a.status = '1' AND (a.daily_limit > a.received_today OR a.daily_limit = '0') AND a.total_visits > a.received AND c.site_id IS NULL LIMIT 1");

				if(empty($sit['id']) || empty($data['id']))
				{
					$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> This page is no longer available.</div>', 'status' => 100);
				}
				else
				{
					$mod_ses = $db->QueryFetchArray("SELECT ses_key FROM `ptc_sessions` WHERE `user_id`='".$data['id']."' AND `site_id`='".$sit['id']."' LIMIT 1");

					if($mod_ses['ses_key'] != '' && $mod_ses['ses_key'] <= time())
					{
						$db->Query("UPDATE `users` SET `tokens`=`tokens`+'".$sit['reward']."' WHERE `id`='".$data['id']."'");
						$db->Query("UPDATE `ptc_websites` SET `received`=`received`+'1', `received_today`=`received_today`+'1' WHERE `id`='".$sit['id']."'");
						$db->Query("INSERT INTO `ptc_done` (`user_id`, `site_id`, `time`) VALUES('".$data['id']."', '".$sit['id']."', '".time()."')");

						// Referral Commission
						if($data['ref'] > 0) {
							$ref_data = $db->QueryFetchArray("SELECT `last_activity` FROM `users` WHERE `id` = '".$data['ref']."' LIMIT 1");
							
							if(!empty($ref_data['last_activity']) && $ref_data['last_activity'] > (time() - ($config['ref_activity']*3600))) {
								$commission = (($data['ref_com']/100)*$sit['reward']);
								ref_commission($data['ref'], $data['id'], $commission);
							}
						}

						$resultData = array('message' => '<div class="alert alert-success" role="alert"><i class="fa fa-check-circle"></i> <b>SUCCESS</b> You received '.number_format($sit['reward'], 2).' '.$lang['l_530'].'!</div>', 'redirect' => ($sit['redirect'] == 1 ? $sit['website'] : 'false'), 'status' => 200);
					}
					else
					{
						$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> This page is no longer available.</div>', 'status' => 100);
					}
				}
			}
		}
		else
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 400); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'processScratch')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_504'].'</div>', 'status' => 400); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] == $_SESSION['token'])
		{
			
			if($data['account_balance'] < $config['scratch_price'])
			{
				$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_406'].'</div>', 'status' => 0); 
			}
			else
			{
				$won = 2;
				$prize = 0;
				$scratch_prizes = unserialize($config['scratch_prizes']);
				if(rand(0,100) < $config['scratch_win_chance'])
				{
					$won = 1;
					$win = mt_rand(0,100);
					if($win == 100){
						$win = 6;
					}elseif($win <= 15){
						$win = 5;
					}elseif($win <= 30){
						$win = 4;
					}elseif($win <= 50){
						$win = 3;
					}elseif($win <= 75){
						$win = 2;
					}else{
						$win = 1;
					}

					$a[1]	=	$scratch_prizes[$win];
					$a[2]	=	$scratch_prizes[$win];
					$a[3]	=	$scratch_prizes[$win];
					$a[4]	=	$scratch_prizes[mt_rand(2,3)];
					$a[5]	=	$scratch_prizes[mt_rand(4,6)];
					$a[6]	=	$scratch_prizes[mt_rand(4,6)];
					$a = shuffle_assoc($a);
					
					$prize = $scratch_prizes[$win];
					$message = '<div class="alert alert-success" role="alert"><b>Congratulations!</b> You won '.number_format($prize).' coins!</div>';
				}
				else
				{
					$a[1]	=	$scratch_prizes[mt_rand(1,2)];
					$a[2]	=	$scratch_prizes[mt_rand(3,4)];
					$a[3]	=	$scratch_prizes[mt_rand(5,6)];
					$a[4]	=	$scratch_prizes[mt_rand(1,2)];
					$a[5]	=	$scratch_prizes[mt_rand(3,4)];
					$a[6]	=	$scratch_prizes[mt_rand(5,6)];
					$message = '<div class="alert alert-danger" role="alert"><b>Bad luck!</b> You didn\'t won anything this time, try again!</div>';
				}
				
				$user_profit = ($prize - $config['scratch_price']);
				$db->Query("UPDATE `users` SET `account_balance`=`account_balance`+'".$user_profit."' WHERE `id`='".$data['id']."'");
				$db->Query("INSERT INTO `scratch_games` (`user_id`,`bet`,`profit`,`status`,`time`) VALUES ('".$data['id']."','".$config['scratch_price']."','".$user_profit."','".$won."','".time()."')");
				$gameID = $db->GetLastInsertId();
			
				$j = 0;
				$scratch_content = '';
				foreach($a as $key => $value)
				{
					$j++;
					$scratch_content .= '<td width="100" height="40"><a href="javascript:void(0)" id="click_'.$key.'" onClick="showPrize('.$key.', \'system/libs/scratch/img.php?val='.$value.'\')"><img id="pic_'.$key.'" src="static/img/scratch/vol.jpg" width="80" height="52"></a></td><td width="8" height="40"></td>';
					
					if($j == 3)
					{
						$scratch_content .= '</tr><tr><td width="10" height="5"></td><td width="50" height="5"></td><td width="20" height="5"></td><td width="50" height="5"></td><td width="100" height="5"></td><td width="50" height="14"></td></tr><tr>';
					}
				}
				
				$scratch_form = '<p class="infobox text-center">Scratch the ticket (click on grey blocks) to see if you won!</p>
						<div class="scratch-ticket">
							<table width="500" height="350" background="static/img/scratch/scratch.png" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td width="500" height="150">
										<table align="right" cellpadding="0" cellspacing="0" style="margin-top:84px">
											<tr>'.$scratch_content.'</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
						<div id="status" class="d-none">'.$message.'</div>
						<div id="buy-again" class="text-center d-none">
							<button class="btn btn-primary mt-1" id="buy-ticket"><i class="fas fa-ticket-alt"></i> '.$lang['l_555'].'</button>
						</div>
						<script>
							var scratched = 0;
							function showPrize(id,what) {
								document.getElementById("pic_"+id).src = what; 
								$("#click_"+id).removeAttr("onclick");
								scratched = scratched + 1;
								if(scratched == 6)
								{
									$("#myTable").prepend("<tr><td>'.$gameID.'</td><td>'.$config['scratch_price'].' '.$lang['l_337'].'</td><td>'.$user_profit.' '.$lang['l_337'].'</td><td>'.($won == 1 ? $lang['l_559'] : $lang['l_560']).'</td><td>'.date('d M Y - H:i', time()).'</td></tr>");
									$("#status").removeClass("d-none");
									$("#buy-again").removeClass("d-none");
									scratched = 0;
								}
							}
						</script>';
			
				$resultData = array('message' => $scratch_form, 'status' => 200); 
			}
		}
		else
		{
			$resultData = array('message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '.$lang['l_304'].'</div>', 'status' => 400); 
		}

		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	
	if($data['admin'] == 1 && isset($_GET['checkIP']) && isset($_GET['user_id']))
	{
		$uIP = $db->EscapeString($_GET['checkIP']);
		$uID = $db->EscapeString($_GET['user_id']);
		$UserIPData = $db->QueryFetchArray("SELECT `id`,`status`,`time` FROM `ip_checks` WHERE `user_id`='".$uID."' AND `ip_address`='".$uIP."' LIMIT 1");
		if(empty($UserIPData) || $UserIPData['time'] < (time()-86400))
		{
			$IPData = detectProxy($uIP);
			
			if($IPData['status'] != 99)
			{
				$db->Query("INSERT INTO `ip_checks` (`user_id`,`ip_address`,`country_code`,`status`,`time`)VALUES('".$uID."','".$uIP."','".$IPData['country']."','".$IPData['status']."','".time()."') ON DUPLICATE KEY UPDATE `status`='".$IPData['status']."', `time`='".time()."'");
				$result = $IPData['status'];
			}
		}
		else
		{
			$result = $UserIPData['status'];
		}
		
		
		echo $result;
		exit;
	}
}
else
{
	if(isset($_POST['a']) && $_POST['a'] == 'login' && isset($_POST['username']) && isset($_POST['password']))
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_504'].'</div>', 'status' => 0); 
		}
		elseif(!isset($_POST['access_key']) || ($_POST['access_key'] !== $_SESSION['authentication_key']))
		{
			$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_304'].'</div>'); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			// validate recaptcha
			$captcha_valid = 1;
			if(!empty($config['recaptcha_sec'])){
				if(!isset($_POST['recaptcha'])) {
					$captcha_valid = 0;
				}
				else
				{
					$recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha_sec']);
					$recaptcha = $recaptcha->verify($_POST['recaptcha'], $_SERVER['REMOTE_ADDR']);
				
					if(!$recaptcha->isSuccess()){
						$captcha_valid = 0;
					}
				}
			}
			
			if(!$captcha_valid)
			{
				$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_142'].'</div>'); 
			}
			else
			{
				$ip_address = ip2long(VisitorIP());
				$attempts = $db->QueryFetchArray("SELECT `count`,`time` FROM `wrong_logins` WHERE `ip_address`='".$ip_address."' LIMIT 1");

				if($attempts['count'] >= $config['login_attempts'] && $attempts['time'] > (time() - (60*$config['login_wait_time'])))
				{
					$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.lang_rep($lang['l_14'], array('-TIME-' => $config['login_wait_time'])).'</div>'); 
				}
				else
				{
					$login = $db->EscapeString($_POST['username']);
					$data = $db->QueryFetchArray("SELECT `id`,`disabled`,`activate`,`auth_key`,`auth_status` FROM `users` WHERE (`username`='".$login."' OR `email`='".$login."') AND `password`='".securePassword($_POST['password'])."' LIMIT 1");

					$ga_status = true;
					if($data['auth_status'] == 1 && !empty($data['auth_key']))
					{
						$ga = new GoogleAuthenticator();
						$checkResult = $ga->verifyCode($data['auth_key'], $_POST['pin'], 2);

						if(!$checkResult)
						{
							$ga_status = false;
						}
					}

					if(empty($data['id']))
					{
						$db->Query("INSERT INTO `wrong_logins` (`ip_address`,`count`,`time`) VALUES ('".$ip_address."','1','".time()."') ON DUPLICATE KEY UPDATE `count`=`count`+'1', `time`='".time()."'");
						$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_17'].'</div>'); 
					}
					elseif($data['disabled'] > 0)
					{
						$reason = $db->QueryFetchArray("SELECT `reason` FROM `ban_reasons` WHERE `user`='".$data['id']."' LIMIT 1");
						$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_15'].' '.$reason['reason'].'</div>'); 
					}
					elseif($data['activate'] != '0')
					{
						$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_16'].'</div>'); 
					}
					elseif($ga_status === false)
					{
						$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_513'].'</div>'); 
					}
					else
					{
						$db->Query("UPDATE `users` SET `log_ip`='".VisitorIP()."', `last_activity`='".time()."' WHERE `id`='".$data['id']."'");
						$db->Query("DELETE FROM `wrong_logins` WHERE `ip_address`='".$ip_address."'");
			
						// Store login info
						$browser = $db->EscapeString($_SERVER['HTTP_USER_AGENT']);
						$db->Query("INSERT INTO `user_logins` (`uid`,`ip`,`info`,`time`) VALUES ('".$data['id']."','".$ip_address."','".$browser."',NOW())");
						
						// Update Session Token
						$hash_key = GenerateKey(16);
						$db->Query("INSERT INTO `users_sessions` (`uid`,`hash`,`browser`,`ip_address`,`timestamp`) VALUES ('".$data['id']."','".$hash_key."','".$browser."','".$ip_address."','".time()."') ON DUPLICATE KEY UPDATE `hash`='".$hash_key."', `browser`='".$browser."', `ip_address`='".$ip_address."', `timestamp`='".time()."'");
						$_SESSION['SesHashKey'] = $hash_key;
						
						// Auto-login user
						if(isset($_POST['remember'])){
							setcookie('SesHashKey', $hash_key, time()+604800, '/');
							setcookie('SesToken', 'ses_id='.$data['id'].'&ses_key='.$hash_key, time()+604800, '/');
						}
						
						// Set Session
						$_SESSION['PT_User'] = $data['id'];
						
						// Multi-account prevent
						setcookie('AccExist', $data['id'], time()+604800, '/');
						
						$resultData = array('status' => 1, 'msg' => '<div class="alert alert-success" role="alert">'.$lang['l_303'].'</div>'); 
					}
				}
			}
		} else {
			$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_304'].'</div>'); 
		}
		
		header('Content-type: application/json');
		echo json_encode($resultData);
	}
	elseif(isset($_POST['a']) && $_POST['a'] == 'register')
	{
		if(!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT']))
		{
			$resultData = array('msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_504'].'</div>', 'status' => 0); 
		}
		elseif(!isset($_POST['access_key']) || ($_POST['access_key'] !== $_SESSION['registration_key']))
		{
			$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_304'].'</div>'); 
		}
		elseif(isset($_POST['token']) && $_POST['token'] === $_SESSION['token'])
		{
			$ip_address = VisitorIP();
			$username = $db->EscapeString($_POST['username']);
			$country = $db->EscapeString($_POST['country']);
			$gender = $db->EscapeString($_POST['gender']);
			$email = $db->EscapeString($_POST['email']);
			
			// validate recaptcha
			$captcha_valid = 1;
			if(!empty($config['recaptcha_sec']))
			{
				if(!isset($_POST['recaptcha']))
				{
					$captcha_valid = 0;
				}
				else
				{
					$recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha_sec']);
					$recaptcha = $recaptcha->verify($_POST['recaptcha'], $_SERVER['REMOTE_ADDR']);
				
					if(!$recaptcha->isSuccess()){
						$captcha_valid = 0;
					}
				}
			}
			
			if(!$captcha_valid)
			{
				$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_142'].'</div>'); 
			}
			elseif(!$_POST['tos'])
			{
				$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_285'].'</div>'); 
			}
			elseif(!isUserID($username))
			{
				$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_300'].'</div>'); 
			}
			elseif(!isEmail($email))
			{
				$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_144'].'</div>'); 
			}
			elseif(!validatePassword($_POST['password']))
			{
				$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_134'].'</div>'); 
			}
			elseif(!checkPwd($_POST['password'],$_POST['password2']))
			{
				$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_521'].'</div>'); 
			}
			elseif($gender < 1 && $gender > 2) 
			{
				$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_147'].'</div>'); 
			}
			else
			{
				$countries = $db->QueryFetchArrayAll("SELECT `id` FROM `list_countries`");
				$ctrs = array();
				foreach($countries as $row) {
					$ctrs[] = $row['id'];
				}

				if($db->QueryGetNumRows("SELECT `id` FROM `users` WHERE `username`='".$username."' OR `email`='".$email."' LIMIT 1") > 0)
				{
					$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_148'].'</div>');
				}
				elseif($config['more_per_ip'] != 1 && isset($_COOKIE['AccExist']) || $config['more_per_ip'] != 1 && $db->QueryGetNumRows("SELECT id FROM `users` WHERE `reg_ip`='".$ip_address."' OR `log_ip`='".$ip_address."' LIMIT 1") > 0)
				{
					$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_149'].'</div>');
				}
				elseif(!in_array($country, $ctrs))
				{
					$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_150'].'</div>');
				}
				else
				{
					$IPData = detectProxy($ip_address);
					if($IPData['status'] != 99 && $IPData['status'] == 1)
					{
						$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_503'].'</div>');
					}
					else
					{
						$referal = (isset($_COOKIE['PT_REF_ID']) ? $db->EscapeString($_COOKIE['PT_REF_ID']) : 0);
						if($referal != 0 && $db->QueryGetNumRows("SELECT `id` FROM `users` WHERE `id`='".$referal."' LIMIT 1") == 0) {
							$referal = 0;
						}

						$ref_source = 0;
						if(isset($_COOKIE['RefSource'])){
							$ref_source = $db->EscapeString($_COOKIE['RefSource']);
						}

						$activate = 0;
						if($config['reg_reqmail'] == 1){
							$activate = GenerateKey(32);
							if($config['mail_delivery_method'] == 1){
								$mailer->isSMTP();
								$mailer->Host = $config['smtp_host'];
								$mailer->Port = $config['smtp_port'];

								if(!empty($config['smtp_auth'])){
									$mailer->SMTPSecure = $config['smtp_auth'];
								}
								$mailer->SMTPAuth = (empty($config['smtp_username']) || empty($config['smtp_password']) ? false : true);
								if($mailer->SMTPAuth){
									$mailer->Username = $config['smtp_username'];
									$mailer->Password = $config['smtp_password'];
								}
							}
							
							$mailer->AddAddress($email, $username);
							$mailer->SetFrom((!empty($config['noreply_email']) ? $config['noreply_email'] : $config['site_email']), $config['site_name']);
							$mailer->Subject = $config['site_logo'].' - Activate your account';
							$mailer->MsgHTML('<html>
												<body style="font-family: Verdana; color: #333333; font-size: 12px;">
													<table style="width: 400px; margin: 0px auto;">
														<tr style="text-align: center;">
															<td style="border-bottom: solid 1px #cccccc;"><h1 style="margin: 0; font-size: 20px;"><a href="'.$config['site_url'].'" style="text-decoration:none;color:#333333"><b>'.$config['site_name'].'</b></a></h1><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">Activate your account</h2></td>
														</tr>
														<tr style="text-align: justify;">
															<td style="padding-top: 15px; padding-bottom: 15px;">
																Hello '.$username.',
																<br /><br />
																Click on this link to activate your account:<br />
																<a href="'.$config['site_url'].'/?activate='.$activate.'">'.$config['site_url'].'/?activate='.$activate.'</a>
															</td>
														</tr>
														<tr style="text-align: right; color: #777777;">
															<td style="padding-top: 10px; border-top: solid 1px #cccccc;">
																Best Regards!
															</td>
														</tr>
													</table>
												</body>
											</html>');
							$mailer->Send();
						}

						$db->Query("INSERT INTO `users`(`email`,`username`,`country_id`,`gender`,`reg_ip`,`password`,`ref`,`reg_time`,`activate`,`ref_source`) VALUES ('".$email."','".$username."','".$country."','".$gender."','".$ip_address."','".securePassword($_POST['password'])."','".$referal."','".time()."','".$activate."','".$ref_source."')");
						$user_id = $db->GetLastInsertId();
						
						if(empty($user_id))
						{
							$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_146'].'</div>');
						}
						else
						{
							// Store IP details
							$db->Query("INSERT INTO `ip_checks` (`user_id`,`ip_address`,`country_code`,`status`,`time`)VALUES('".$user_id."','".$ip_address."','".$IPData['country']."','".$IPData['status']."','".time()."') ON DUPLICATE KEY UPDATE `status`='".$IPData['status']."', `time`='".time()."'");

							if($referal > 0)
							{
								add_notification($referal, 2, $user_id);
							}
							
							if(!isset($_COOKIE['AccExist'])){
								setcookie('AccExist', $user_id, time()+604800, '/');
							}
							
							if($config['reg_reqmail'] != 1 && $user_id > 0) {
								$browser = $db->EscapeString($_SERVER['HTTP_USER_AGENT']);
								$db->Query("INSERT INTO `user_logins` (`uid`,`ip`,`info`,`time`) VALUES ('".$user_id."','".ip2long($ip_address)."','".$browser."',NOW())");
								$db->Query("UPDATE `users` SET `log_ip`='".$ip_address."', `last_activity`='".time()."' WHERE `id`='".$user_id."'");
							
								// Update Session Token
								$hash_key = GenerateKey(16);
								$ip_address = ip2long($ip_address);
								$browser = $db->EscapeString($_SERVER['HTTP_USER_AGENT']);
								$db->Query("INSERT INTO `users_sessions` (`uid`,`hash`,`browser`,`ip_address`,`timestamp`) VALUES ('".$user_id."','".$hash_key."','".$browser."','".$ip_address."','".time()."') ON DUPLICATE KEY UPDATE `hash`='".$hash_key."', `browser`='".$browser."', `ip_address`='".$ip_address."', `timestamp`='".time()."'");

								// Save Sessions
								$_SESSION['SesHashKey'] = $hash_key;
								$_SESSION['PT_User'] = $user_id;
								
								$resultData = array('status' => 1, 'loggedin' => 1, 'msg' => '<div class="alert alert-success" role="alert">'.$lang['l_152'].'</div>'); 
							}
							else
							{
								$resultData = array('status' => 1, 'loggedin' => 0, 'msg' => '<div class="alert alert-success" role="alert">'.$lang['l_151'].'</div>'); 
							}
						}
					}
				}
			}
		} else {
			$resultData = array('status' => 0, 'msg' => '<div class="alert alert-danger" role="alert">'.$lang['l_304'].'</div>'); 
		}
		
		header('Content-type: application/json');
		echo json_encode($resultData);
	}
}
?>