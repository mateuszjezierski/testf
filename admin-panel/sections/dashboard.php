<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	// Load Users Stats
	$users = array();
	$users['reg_today'] = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE `reg_time` >= '".strtotime(date('d M Y'))."'");
	$users['on_today'] = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE `last_activity` >= '".strtotime(date('d M Y'))."'");
	$users['online'] = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE `last_activity` >= '".(time()-900)."'");
	$users['disabled'] = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE `disabled` = '1'");
	$users['vip'] = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE `membership` > '0'");
	$users['total'] = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users`");
	$users['proxy'] = $db->QueryGetNumRows("SELECT a.id FROM ip_checks a LEFT JOIN users b ON b.id = a.user_id WHERE a.status = '1' AND a.checked = '0' AND b.disabled = '0' GROUP BY a.user_id");
	$users['multi_acc'] = $db->QueryGetNumRows("SELECT COUNT(*) AS total_accounts FROM users WHERE log_ip != '' AND log_ip != 0 AND disabled = '0' GROUP BY log_ip HAVING total_accounts > '1'");	

	// Load Income / Outcome Stats
	$deposits = $db->QueryFetchArray("SELECT SUM(`amount`) AS `amount`, COUNT(*) AS `total` FROM `deposits` WHERE `status`>'0'");
	$sent_money = $db->QueryFetchArray("SELECT SUM(`amount`) AS `amount`, COUNT(*) AS `total` FROM `withdrawals` WHERE `status`='1'");
	$rejected_money = $db->QueryFetchArray("SELECT SUM(`amount`) AS `amount`, COUNT(*) AS `total` FROM `withdrawals` WHERE `status`='2'");
	$pending_money = $db->QueryFetchArray("SELECT SUM(`amount`) AS `amount`, COUNT(*) AS `total` FROM `withdrawals` WHERE `status`='0'");
	$offers_income = $db->QueryFetchArray("SELECT SUM(`revenue`) AS `money`, COUNT(*) AS `total` FROM `completed_offers`");

	// Game Stats
	$game = array();
	$game['dice'] = $db->QueryFetchArray("SELECT SUM(`bet`) AS `bet`, SUM(`profit`) AS `profit`, COUNT(`id`) AS `total` FROM `dice_history` WHERE `open`='1'");
	$game['flip'] = $db->QueryFetchArray("SELECT SUM(`bet_amount`) AS `bet`, SUM(`profit`) AS `profit`, COUNT(`id`) AS `total` FROM `coinflip_history`");
	$game['scratch'] = $db->QueryFetchArray("SELECT SUM(`bet`) AS `bet`, SUM(`profit`) AS `profit`, COUNT(`id`) AS `total` FROM `scratch_games`");

	// Faucet Stats
	$faucet = array();
	$faucet['claims'] = $db->QueryFetchArray("SELECT SUM(`total_claims`) AS `total`, SUM(`today_claims`) AS `today`, SUM(`today_revenue`) AS `today_revenue` FROM `users` WHERE `disabled`='0'");
	$faucet['revenue'] = $db->QueryFetchArray("SELECT SUM(`total_revenue`) AS `total` FROM `users` WHERE `total_revenue`>'0' AND `disabled`='0'");
	$faucet['users'] = $db->QueryFetchArray("SELECT COUNT(`id`) AS `total` FROM `users` WHERE `last_claim`>='".strtotime(date('d M Y'))."'");
	$faucet['active_sites'] = $db->QueryFetchArray("SELECT COUNT(`id`) AS `total` FROM `ptc_websites` WHERE `received`<`total_visits` AND `status`='1'");
	$faucet['finished_sites'] = $db->QueryFetchArray("SELECT COUNT(`id`) AS `total` FROM `ptc_websites` WHERE `received`>=`total_visits` AND `status`='1'");

	$faucet['total_claims'] = ($faucet['claims']['total']);
	$faucet['today_claims'] = ($faucet['claims']['today']);
	
	// Shortlinks Stats
	$shortlink = $db->QueryFetchArray("SELECT SUM(`sl_total`) AS `total`, SUM(`sl_today`) AS `today`, SUM(`sl_earnings`) AS `earnings`, SUM(`sl_today_earnings`) AS `today_earnings` FROM `users`");
	
	// Sales reports
	$income_month = $db->QueryFetchArray("SELECT SUM(`amount`) AS `amount` FROM `deposits` WHERE `time` >= '".strtotime(date('M Y'))."' AND `status`>'0'");
	$income_month = (!empty($income_month['amount']) ? $income_month['amount'] : 0);
	$income_today = $db->QueryFetchArray("SELECT SUM(`amount`) AS `amount` FROM `deposits` WHERE `time` >= '".strtotime(date('d M Y'))."' AND `status`>'0'");
	$income_today = (!empty($income_today['amount']) ? $income_today['amount'] : 0);
	
	// Last 7 days Users
	$stats_reg = $db->QueryFetchArrayAll("SELECT COUNT(*) AS `total`, DATE(FROM_UNIXTIME(`reg_time`)) AS `day` FROM `users` GROUP BY `day` ORDER BY `day` DESC LIMIT 7");
	$stats_del= $db->QueryFetchArrayAll("SELECT COUNT(*) AS `total`, DATE(`time`) AS `day` FROM `users_deleted` GROUP BY `day` ORDER BY `day` DESC LIMIT 7");
	$stats_log= $db->QueryFetchArrayAll("SELECT COUNT(DISTINCT `uid`) AS `total`, DATE(`time`) AS `day` FROM `user_logins` GROUP BY `day` ORDER BY `day` DESC LIMIT 7");
	$faucet_history = $db->QueryFetchArrayAll("SELECT * FROM `faucet_history` ORDER BY `date` DESC LIMIT 7");
	
	$dates = array();
	for ($i = 0; $i < 7; $i++) {
		$dates[] = date('Y-m-d', time() - 86400 * $i);
	}
	$today = date('Y-m-d');
	$dates = array_reverse($dates);

	$rStatsT = '';
	$rStatsU = '';
	$rStatsD = '';
	$rStatsL = '';
	foreach($dates as $date) {
		$result = 0;
		$rStatsT .= '<th>'.$date.'</th>';
		foreach($stats_reg as $stat) {
			if($date == $stat['day']) {
				$result = $stat['total'];
			}
		}
		$rStatsU .= '<td>'.$result.'</td>';
		$result = 0;
		
		foreach($stats_del as $stat) {
			if($date == $stat['day']) {
				$result = $stat['total'];
			}
		}
		$rStatsD .= '<td>'.$result.'</td>';
		$result = 0;
		
		foreach($stats_log as $stat) {
			if($date == $stat['day']) {
				$result = ($today == $date ? $users['on_today']['total'] : $stat['total']);
			}
		}
		$rStatsL .= '<td>'.$result.'</td>';
	}

	$dates = array();
	for ($i = 1; $i <= 7; $i++) {
		$dates[] = date('Y-m-d', time() - 86400 * $i);
	}
	$dates = array_reverse($dates);

	$mhStatsM = ''; $mhStatsU = ''; $mhStatsT = ''; $mhStatsS = '';
	foreach($dates as $date) {
		$result = 0; $result2 = 0; $result3 = 0;
		$mhStatsT .= '<th>'.$date.'</th>';

		foreach($faucet_history as $stat) {
			if($date == $stat['date']) {
				$result = ($stat['total_claims']);
				$result2 = ($stat['total_users']);
				$result3 = ($stat['total_link']);
			}
		}
		$mhStatsM .= '<td>'.$result.'</td>';
		$mhStatsU .= '<td>'.$result2.'</td>';
		$mhStatsS .= '<td>'.$result3.'</td>';
	}
?>
<section id="content" class="container_12 clearfix" data-sort=true>
	<ul class="stats not-on-phone">
		<li>
			<strong><?=number_format($users['total']['total'])?></strong>
			<small>Total Users</small>
			<span <?=($users['reg_today']['total'] > 0 ? 'class="green" ' : '')?>style="margin:4px 0 -10px 0"><?=$users['reg_today']['total']?> today</span>
		</li>
		<li>
			<strong><?=number_format($users['on_today']['total'])?></strong>
			<small>Active Today</small>
			<span class="green" style="margin:4px 0 -10px 0"><?=percent($users['on_today']['total'], $users['total']['total'])?>%</span>
		</li>
		<li>
			<strong>$<?=number_format($deposits['amount'], 2)?></strong>
			<small>Deposits</small>
			<span <?=($deposits['total'] > 0 ? 'class="green" ' : '')?>style="margin:4px 0 -10px 0"><?=number_format($deposits['total'])?> deposits</span>
		</li>
		<li>
			<strong><?=number_format($faucet['active_sites']['total'])?></strong>
			<small>Active PTC Websites</small>
			<span <?=($faucet['finished_sites']['total'] > 0 ? 'class="red" ' : '')?>style="margin:4px 0 -10px 0"><?=number_format($faucet['finished_sites']['total'])?> finished</span>
		</li>
		<li>
			<strong><?=number_format($faucet['total_claims'])?></strong>
			<small>Faucet Claims</small>
			<span class="green" style="margin:4px 0 -10px 0"><?=number_format($faucet['today_claims'])?> today</span>
		</li>
		<li>
			<strong><?=number_format($shortlink['total'])?></strong>
			<small>Shortlinks Visits</small>
			<span class="green" style="margin:4px 0 -10px 0"><?=number_format($shortlink['today'])?> today</span>
		</li>
	</ul>

	<div class="alert note" id="version_alert" style="margin-top:10px;padding-top:10px;padding-bottom:10px;font-size:14px;text-align:center;display:none"><a href="https://mn-shop.com/account/download" target="_blank"><strong>There is a new version of this script available for download! Download latest version from MN-Shop.com</strong></a></div>

	<h1 class="grid_12 margin-top">Dashboard</h1>
	<div class="grid_7">
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/users.png" width="16" height="16">Users statistics</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$users['online']['total'].','.($users['online']['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Online Members","color":"green"},{"val":<?=$users['vip']['total'].','.($users['vip']['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Upgraded Members"},{"val":<?=$users['disabled']['total'].','.($users['disabled']['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Banned Members","color":"red"},{"val":<?=$users['reg_today']['total'].','.($users['reg_today']['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Registered Today"}]' data-flexiwidth=true></div>
				</div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=number_format($users['proxy'], 0, '.', '').','.($users['proxy'] > 999 ? '"format":"0,0",' : '')?>"title":"Users with VPN / Proxy","color":"red"},{"val":<?=$users['multi_acc'].','.($users['multi_acc'] > 999 ? '"format":"0,0",' : '')?>"title":"Users with multiple accounts"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/orders.png" width="16" height="16">Withdrawals</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$pending_money['total'].','.($pending_money['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Pending"},{"val":<?=$sent_money['total'].','.($sent_money['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Sent","color":"green"},{"val":<?=$rejected_money['total'].','.($rejected_money['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Rejected","color":"red"}]' data-flexiwidth=true></div>
				</div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":"<?=number_format($pending_money['amount'], 2, '.', '')?>","format":"$0.00","title":"Total Pending"},{"val":"<?=number_format($sent_money['amount'], 2, '.', '')?>","format":"$0.00","title":"Total Sent","color":"green"},{"val":"<?=number_format($rejected_money['amount'], 2, '.', '')?>","format":"$0.00","title":"Total Rejected","color":"red"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/mining.png" width="16" height="16">Faucet Statistics</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=number_format($faucet['users']['total'], 0, '.', '').','.($faucet['users']['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Users Active Today","color":"red"},{"val":<?=number_format($faucet['today_claims'], 0, '.', '').','.($faucet['today_claims'] > 999 ? '"format":"0,0",' : '')?>"title":"Today Claims"},{"val":<?=number_format($faucet['total_claims'], 0, '.', '').','.($faucet['total_claims'] > 999 ? '"format":"0,0",' : '')?>"title":"Total Claims"}]' data-flexiwidth=true></div>
				</div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=number_format($faucet['claims']['today_revenue'], 2, '.', '')?>,"format":"0.00","title":"Coins Earned Today","color":"green"},{"val":<?=number_format($faucet['revenue']['total'], 2, '.', '')?>,"format":"0.00","title":"Total Coins Earned","color":"green"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/coins.png" width="16" height="16">Deposits statistics</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$income_today?>,"format":"$0.00","title":"Deposited Today","color":"green"},{"val":<?=$income_month?>,"format":"$0.00","title":"This Month"},{"val":<?=number_format($deposits['amount'], 2)?>,"format":"$0.00","title":"Total Income","color":"red"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/orders.png" width="16" height="16">Games statistics</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$game['dice']['total'].','.($game['dice']['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Dice Games"},{"val":<?=number_format($game['dice']['bet'], 2, '.', '')?>,"format":"0.00 Coins","title":"Total Bets","color":"green"},{"val":<?=number_format($game['dice']['profit'], 2, '.', '')?>,"format":"0.00 Coins","title":"Total Users Profit","color":"red"}]' data-flexiwidth=true></div>
				</div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$game['flip']['total'].','.($game['flip']['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Coin Flip Games"},{"val":<?=number_format($game['flip']['bet'], 2, '.', '')?>,"format":"0.00 Coins","title":"Total Bets","color":"green"},{"val":<?=number_format($game['flip']['profit'], 2, '.', '')?>,"format":"0.00 Coins","title":"Total Users Profit","color":"red"}]' data-flexiwidth=true></div>
				</div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=$game['scratch']['total'].','.($game['scratch']['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Scratch Tickets"},{"val":<?=number_format($game['scratch']['bet'], 2, '.', '')?>,"format":"0.00 Coins","title":"Total Bets","color":"green"},{"val":<?=number_format($game['scratch']['profit'], 2, '.', '')?>,"format":"0.00 Coins","title":"Total Users Profit","color":"red"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
	</div>
	<div class="grid_5">
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/jobs.png">News</h2>
			</div>
			<div class="content">
				<div class="news" id="loadNews"><center><img src="img/ajax-loader.gif" style="margin-top: 25px;" /></center></div>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/users.png" width="16" height="16">Users activity in past 7 days</h2>
			</div>
			<div class="content">
				<table class="chart" data-type="bars" style="height: 280px;">
					<thead>
						<tr>
							<th></th>
							<?=$rStatsT?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>Registered Users</th>
							<?=$rStatsU?>
						</tr>
						<tr>
							<th>Deleted Users</th>
							<?=$rStatsD?>
						</tr>
						<tr>
							<th>Active Users</th>
							<?=$rStatsL?>
						</tr>
					</tbody>	
				</table>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/mining.png" width="16" height="16">Faucet activity in past 7 days</h2>
			</div>
			<div class="content">
				<table class="chart styled borders" style="height: 300px;">
					<thead>
						<tr>
							<th></th>
							<?=$mhStatsT?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>Faucet Claims</th>
							<?=$mhStatsM?>
						</tr>
						<tr>
							<th>Shortlinks Visits</th>
							<?=$mhStatsS?>
						</tr>
						<tr>
							<th>Active Users</th>
							<?=$mhStatsU?>
						</tr>
					</tbody>	
				</table>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2><img class="icon" src="img/icons/packs/fugue/16x16/jobs.png" width="16" height="16">Completed Offers</h2>
			</div>
			<div class="content">
				<div class="spacer"></div>
				<div class="full-stats">
					<div class="stat hlist" data-list='[{"val":<?=number_format($offers_income['total'], 0, '.', '').','.($offers_income['total'] > 999 ? '"format":"0,0",' : '')?>"title":"Completed Offers"},{"val":<?=number_format($offers_income['money'], 2, '.', '')?>,"format":"~$0.00","title":"Offers Income","color":"green"}]' data-flexiwidth=true></div>
				</div>
			</div>
		</div>
	</div>
</section>