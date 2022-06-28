<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$notifications = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `notifications` WHERE `user_id`='".$data['id']."' AND `read`='0'");
	$total_levels = $db->QueryGetNumRows("SELECT `id` FROM `levels`");
	$level_stats = userLevel($data['id'], 4 , $data['total_claims']);
?>
	<div class="col-xl-3 col-lg-4 col-md-5">
		<div id="sidebar-block" class="box-shadow box-style mt-3"> 
			<div class="user">
				<div class="info">
					<a href="<?=GenerateURL('notifications')?>"<?=($notifications['total'] > 0 ? ' class="notification show-count" data-count="'.$notifications['total'].'"' : ' class="notification"')?>></a>
					<span>
						<a href="<?=GenerateURL('membership')?>" class="text-success"><?php echo $data['username'].($data['membership_id'] > 1 ? ' <i class="fa fa-star text-warning"></i>' : ''); ?></a><br />
						<span class="logout">
							<a href="<?=GenerateURL('account')?>"><i class="fa fa-cog"></i> <?php echo $lang['l_35']; ?></a> | <a href="<?=$config['secure_url']?>/?logout"><i class="fa fa-power-off"></i> <?php echo $lang['l_36']; ?></a>
						</span>
					</span>
				</div>
			</div>
			<div class="inner">
				<div class="block">
					<div class="data">
						<div class="row">
							<div class="col-3"><i class="fas fa-check-circle fa-2x fa-fw text-success"></i></div>
							<div class="col-9 no-space"><?=$lang['l_531']?> <div class="text-success"><b><?php echo number_format($data['tokens']); ?> <?=$lang['l_530']?></b></div></div>	           
						</div>
					</div>
					<div class="data">
						<div class="row">
							<div class="col-3"><i class="fa fa-coins fa-2x fa-fw text-primary"></i></div>
							<div class="col-9 no-space"><?=$lang['l_38']?> <div class="text-primary"><b id="sidebarCoins"><?php echo number_format($data['account_balance'], 2).' '.$lang['l_337']; ?></b> - <small class="text-success">$<?php echo number_format(($data['account_balance']*$config['bits_rate']), 2); ?></small></div></div>      
						</div>
					</div>
				</div>
				<div class="level">
					<div class="row">
						<div class="col-3 justify-content-center align-self-center">
							<a href="<?=GenerateURL('levels')?>"><img src="<?=$level_stats['image']?>" title="<?=$lang['l_338'].' '.$level_stats['level']?>" border="0" /></a>
						</div>
						<div class="col-9 mt-2 no-space">
							<b><?=$lang['l_338'].' '.$level_stats['level']?></b> / <?=($lang['l_338'].' '.$total_levels)?>
							<p class="mt-2"><?php echo $lang['l_216']; ?> <b>x<?=$level_stats['reward']?></b></p>
						</div>
					</div>
					<div class="progress position-relative">
						<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?=$level_stats['progress']?>%" aria-valuenow="<?=$level_stats['progress']?>" aria-valuemin="0" aria-valuemax="100"></div>
						<small class="justify-content-center d-flex position-absolute w-100 mt-2"><?=lang_rep($lang['l_469'], array('-CLAIMS-' => $level_stats['remaining_claims'], '-LEVEL-' => $level_stats['next_level']))?></small>
					</div>
				</div>
				<button type="button" class="btn btn-success btn-sm w-100 mt-1" onclick="showWithdraw();"><i class="fa fa-btc fa-fw"></i> <?php echo $lang['l_37']; ?></button>
			</div>
		</div>
		<?php 
			if($config['pollfish_enabled']) {
		?>
		<div id="sidebar-block" class="box-shadow rounded box-style mt-2">
			<div class="title"><i class="fa fa-poll-h fa-lg fa-fw"></i> <?php echo $lang['l_517']; ?></div>
			<div class="content">
				<button type="button" class="btn btn-warning w-100 mb-1" id="pollfishSurveys" disabled><i class="fa fa-cog fa-spin fa-fw"></i> <?php echo $lang['l_145']; ?></button>
				<div class="block">
					<div class="data text-center">
						<b><?php echo lang_rep($lang['l_515'], array('-CREDITS-' => number_format($data['ow_credits'], 2))); ?></b><br /><a href="<?php echo GenerateURL('offers&x=cryptowall'); ?>"><?php echo $lang['l_516']; ?></a>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		  var pollfishConfig = {api_key: "<?php echo $config['pollfish_key']; ?>", indicator_position: "TOP_LEFT", uuid: "<?php echo $data['id']; ?>", offerwall: true, ready: onOfferwallReady, surveyNotAvailable: onSurveyNotAvailable};
		  function onOfferwallReady(data) {$('#pollfishSurveys').removeAttr('disabled').html('<i class="fa fa-poll-h fa-fw"></i> <?php echo $lang['l_514'] ?>').on('click', function() {Pollfish.showFullSurvey()})}
		  function onSurveyNotAvailable() {$('#pollfishSurveys').html('<i class="fa fa-ban fa-fw"></i> <?php echo $lang['l_518'] ?>')}
		</script>
		<?php } ?>
		<div id="sidebar-block" class="box-shadow rounded box-style mt-2"> 
			<div class="title"><i class="fa fa-external-link fa-lg fa-fw"></i> <?php echo $lang['l_18']; ?></div>
			<div class="content">
				<div class="inner">
					<div class="block">
						<div class="data pt-2 mt-0">
							<div class="row">
								<div class="col-3"><i class="fa fa-shopping-cart fa-2x fa-fw text-success"></i></div>
								<div class="col-9 no-space"><?=$lang['l_332']?> <div class="text-success"><b>$<?php echo number_format($data['purchase_balance'], 4); ?></b></div></div>	           
							</div>
						</div>
					</div>
					<div class="dropdown">
					  <button class="btn btn-sm dropdown-toggle btn-success w-100 mt-1" type="button" id="advertiseMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-ad"></i> <?php echo $lang['l_18']; ?>
					  </button>
					  <div class="dropdown-menu w-100" aria-labelledby="advertiseMenu">
						<a class="dropdown-item" href="<?=GenerateURL('advertise')?>"><i class="fa fa-link fa-fw"></i> <?php echo $lang['l_22']; ?></a>
						<a class="dropdown-item" href="<?=GenerateURL('banners')?>"><i class="fa fa-picture-o fa-fw"></i> <?php echo $lang['l_333']; ?></a>
					  </div>
					</div>
					<button type="button" class="btn btn-warning btn-sm w-100 mt-1" onclick="showDeposit();"><i class="fa fa-shopping-cart fa-fw"></i> <?php echo $lang['l_327']; ?></button>
					<?php if($config['transfer_status'] == 1) { ?>
					<button type="button" class="btn btn-info btn-sm w-100 mt-1" onclick="showTransfer();"><i class="fa fa-exchange fa-fw"></i> <?php echo $lang['l_253']; ?></button>
					<?php } ?>
				</div>
			</div>
		</div>
		<div id="sidebar-block" class="box-shadow rounded box-style mt-2"> 
			<div class="title"><i class="fa fa-share-alt-square fa-lg fa-fw"></i> <?php echo $lang['l_43']; ?></div>
			<div class="content">
				<p><?php echo lang_rep($lang['l_44'], array('-COMMISSION-' => $data['ref_com'])); ?></p><hr />
				<input type="text" value="<?php echo $config['secure_url']; ?>/?ref=<?php echo $data['id']; ?>" onclick="this.select()" readonly="true" class="form-control" /><hr />
				<div class="text-right"><a href="<?=GenerateURL('contest')?>" class="lottery_sidebar_link"><?php echo $lang['l_279']; ?></a></div>
			</div>
		</div>
		<div id="sidebar-footer">
			<?php echo $lang['l_316']; ?>: <b id="serverTime"><?php echo date('M d, Y - H:i:s'); ?></b>
		</div>
		<?php
			$ad_banner = $db->QueryFetchArray("SELECT `code` FROM `ad_codes` WHERE `status`='1' AND `size`='4' ORDER BY rand() LIMIT 1");
			if(!empty($ad_banner['code']))
			{
				echo '<div id="sidebar-ads" class="container mt-2 d-flex justify-content-center"> '.html_entity_decode($ad_banner['code'], ENT_QUOTES).'</div>';
			}
		?>
	</div>
	<div id="transModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
				<div class="modal-body text-center" id="transForm">
					<div class="alert alert-info" role="alert"><i class="fa fa-cog fa-spin fa-fw"></i> <?php echo $lang['l_145']; ?></div>
				</div>
			</div>
		</div>
	</div>
	<?php
		$withdraw_options = '';
		if(!empty($config['fp_api_key']))
		{
			$withdraw_options .= '<option value="1">FaucetPay</option>';
		}
		if(!empty($config['coinbase_withdraw_api']) && !empty($config['coinbase_withdraw_secret']))
		{
			$withdraw_options .= '<option value="3">Coinbase</option>';
		}
	?>
	<script>
		var token = '<?php echo $token; ?>';
		var bitsRate = <?php echo $config['bits_rate']; ?>;
		var minDepVal = <?php echo $config['deposit_min']; ?>;
		var minTransfer = <?php echo (int)$config['transfer_min']; ?>;
		var accBalance = <?php echo $data['account_balance']; ?>;
		var serverdate = new Date('<?php echo date("F d, Y H:i:s", time())?>');
		var montharray = new Array("Jan","Feb","Mar","Apr","May","June","July","Aug","Sep","Oct","Nov","Dec");
		var depositForm = '<div id="depositAlert"><div class="alert alert-info" role="alert"><?php echo $lang['l_476']; ?></div></div><form class="justify-content-center" onsubmit="proccessDeposit(); return false;"><div class="form-row"><div class="form-group col-md-6">  <label for="depositAmount"><?php echo $lang['l_159']; ?></label>	<input type="number" min="<?php echo $config['deposit_min']; ?>" step="0.01" class="form-control my-1 mr-sm-2" id="depositAmount" placeholder="in USD (eg. <?php echo $config['deposit_min']; ?>)" required></div><div class="form-group col-md-6"><label for="depositMethod"><?php echo $lang['l_473']; ?></label><select class="form-control custom-select my-1 mr-sm-2" id="depositMethod"><?php echo (empty($config['cp_id']) ? '' : "<option value=\"0\">CoinPayments</option>"); ?><?php echo (empty($config['faucetpay_username']) ? '' : "<option value=\"1\">FaucetPay</option>"); ?><?php echo (empty($config['payeer_key']) ? '' : "<option value=\"2\">Payeer</option>"); ?><?php echo (empty($config['coinbase_api']) ? '' : "<option value=\"3\">Coinbase</option>"); ?></select></div></div><button type="submit" class="btn btn-primary w-100"><i class="fa fa-credit-card"></i> <?php echo $lang['l_474']; ?></button></form><small id="depositHelp" class="form-text text-muted"><?php echo lang_rep($lang['l_475'], array('-AMOUNT-' => $config['deposit_min'])); ?></small>';
		var depositMin = '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> <?php echo lang_rep($lang['l_472'], array('-AMOUNT-' => $config['deposit_min'])); ?></div>';
		var transferForm = '<div id="convertAlert"><div class="alert alert-info" role="alert"><?php echo $lang['l_478']; ?></div></div><form class="form-inline justify-content-center" onsubmit="sendTransfer(); return false;"><label for="convertAmount" class="my-1 mr-2"><?php echo $lang['l_159']; ?></label><input type="number" step="any" class="form-control my-1 mr-sm-2" id="convertAmount" placeholder="in Coins" required><button type="submit" class="btn btn-primary"><i class="fa fa-exchange"></i> <?php echo $lang['l_480']; ?></button></form><small id="convertHelp" class="form-text text-muted">Min. transfer <?php echo $config['transfer_min']; ?> Coins</small>';
		var transferAlertMin = '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> <?php echo lang_rep($lang['l_459'], array('-NUM-' => (int)$config['transfer_min'])); ?></div>';
		var transferAlertMax = '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> <?php echo addslashes($lang['l_406']); ?></div>';
		var waitMessage = '<div class="alert alert-info" role="alert"><i class="fa fa-cog fa-spin fa-fw"></i> <?php echo $lang['l_145']; ?></div>';
		<?php
			if($data['total_claims'] < $config['withdraw_min_claims'])
			{
				echo 'var withdrawForm = \'<div class="alert alert-warning mb-0" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> <b>WARNING!</b> <i class="fa fa-exclamation-triangle fa-fw"></i><br />'.lang_rep($lang['l_463'], array('-NUM-' => number_format($config['withdraw_min_claims']))).'</div>\';';
			}
			else
			{
				echo 'var withdrawForm = \'<div id="withdrawAlert"><div class="alert alert-info" role="alert">'.$lang['l_477'].'</div></div><form class="form-inline justify-content-center" onsubmit="proccessWithdraw(); return false;"><div class="form-group mb-2"><label for="withdrawMethod" class="my-1 mr-2">'.$lang['l_479'].'</label><select class="form-control custom-select my-1 mr-sm-2" id="withdrawMethod">'.$withdraw_options.'</select></div><button type="submit" class="btn btn-primary mb-2">'.$lang['l_37'].'</button></form>\';';
			}
		?>

		$(document).ready(function() {
			setInterval("serverTime()", 1000);
			<?php if($notifications['total'] > 0) { ?>
			var el = document.querySelector('.notification');
			el.classList.remove('notify');
			el.offsetWidth = el.offsetWidth;
			el.classList.add('notify');
			<?php } ?>
		});
	</script>
	<script src="static/js/side.js"></script>