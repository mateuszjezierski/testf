<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$activeClaim = false;
	if(isset($_GET['x']) && $_GET['x'] == 'claim')
	{
		$claimSession = $db->QueryFetchArray("SELECT * FROM `faucet_sessions` WHERE `user_id`='".$data['id']."' LIMIT 1");
		if(!empty($claimSession['time']))
		{
			$frequencies = unserialize($config['auto_faucet_frequency']);
			$remainingTime = ($claimSession['time'] + ($frequencies[$claimSession['frequency']][1]*60)) - time();
			
			if($remainingTime > 0)
			{
				$progressBar = percent($remainingTime, $frequencies[$claimSession['frequency']][1]*60);
				$level_multiplier = userLevel($data['id'], 3, $data['total_claims']);
				$multiplier = ($data['multiplier'] + $level_multiplier) - 1;
				$frequencies = unserialize($config['auto_faucet_frequency']);
				$activeClaim = true;

				$currencies = empty($claimSession['coin']) ? 1 : count(unserialize($claimSession['coin']));
				$costs = ($frequencies[$claimSession['frequency']][1] * $config['auto_faucet_price'] * $claimSession['boost']) * $currencies;
				$bonus = $claimSession['payout'] == 1 ? ($frequencies[$claimSession['frequency']][1] / 100 * $config['auto_faucet_cp_bonus']) : 0;
				$earnings = (($frequencies[$claimSession['frequency']][1] + $bonus + ($frequencies[$claimSession['frequency']][0] / 100 * $frequencies[$claimSession['frequency']][1])) * $claimSession['boost']) * $multiplier * $currencies;
				$earnings = number_format($earnings, 2, '.', '').'  <span>Coins</span>';
			}
			else
			{
				redirect(GenerateURL('faucet'));
			}
		}
	}
?> 
 <main role="main" class="container">
      <div class="row">
		<?php 
			if($is_online) {
				require(BASE_PATH.'/template/'.$config['theme'].'/common/sidebar.php');
			}
		?>
		<div class="<?=($is_online ? 'col-xl-9 col-lg-8 col-md-7' : 'col-12')?>">
			<div class="my-3 p-3 bg-white rounded box-shadow box-style">
				<div id="grey-box">
					<div class="title">
						Auto Faucet
					</div>
					<div class="content">
						<?php
							if($activeClaim)
							{
						?>
						<div class="claim-area">
							<p class="title-faucet">Auto Claim is running!</p>
							<p class="desc">The auto claiming is started. Please leave this window open to get the payouts.</p>
							<hr />
							<div class="next-payout">
								<p>Next payout in <span id="time"><?php echo gmdate("i:s", $remainingTime); ?></span></p>
							</div>
							<div class="progress">
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" id="progress" style="width: <?php echo $progressBar; ?>%" aria-valuenow="<?php echo $progressBar; ?>" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<hr />
							<span id="claim-result"></span>
							<center><button type="button" class="btn btn-danger btn-sm mt-2 p-2" id="close-session"><i class="fas fa-window-close fa-fw"></i> Close Session</button></center>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="claim-area pb-2">
									<div class="content-box">
										<p class="head">Cost Per Claim</p>
										<p class="amount"><?php echo $costs; ?> <span>Tokens</span></p>
									</div>
									<div class="icon-area green">
										<i class="fas fa-check-circle icon"></i>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-6">
								<div class="claim-area pb-2">
									<div class="content-box">
										<p class="head">Earnings Per Claim <small class="text-danger">*</small></p>
										<p class="amount"><?php echo $earnings; ?></p>
									</div>
									<div class="icon-area green">
										<i class="fas fa-coins icon"></i>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
						<p><small><i><span class="text-danger">*</span> If you selected cryptocurrency payout, total currencies payout is approximated in coins.</i></small></p>
						<?php
							}
							else
							{
						?>
						<form name="form-auto" id="form-auto">
							<div class="faucet-form">
							<p class="title-s text-center"><i class="fas fa-arrow-right icon"></i> How do you like to get the payouts?</p>
							<div class="clearfix"></div>
							<p class="desc-s">We provide different payout options!</p>
							<div class="clearfix"></div>
							<fieldset id="payout">
								<div class="row">
									<div class="col">
										<label class="rwrapper">
											<input type="radio" id="auto-payout" name="payout" value="1" checked /> Coins
										</label>
										<?php 
											if($config['auto_faucet_cp_bonus'] > 0) 
											{
												echo '<div class="info-s">('.$config['auto_faucet_cp_bonus'].'% bonus)</div>';
											}
										?>
									</div>
									<div class="col">
										<label class="rwrapper">
											<input type="radio" id="auto-payout" name="payout" value="2"> FaucetPay
										</label> 
									</div>
								</div>
							</fieldset>
							<hr>
							<span id="currencies" class="d-none">
								<p class="title-s text-center"><i class="fas fa-arrow-right icon"></i>Choose your desired currencies to get paid</p>
								<div class="clearfix"></div>
								<p class="desc-s">Please select your desired currencies to get paid. You can select up to <?php echo $config['auto_faucet_max']; ?> currencies!</p>
								<div class="clearfix"></div>
								<div class="row" id="list-currencies"></div>
								<hr>
							</span>
							<p class="title-s text-center"><i class="fas fa-arrow-right icon"></i>Choose the frequency</p>
							<div class="clearfix"></div>
							<p class="desc-s">Select how often do you want to claim. Longer time means higher reward!</p>
							<div class="clearfix"></div>
							<fieldset id="frequency">
								<div class="row">
									<?php
										$frequencies = unserialize($config['auto_faucet_frequency']);
										asort($frequencies);
										foreach($frequencies as $key => $frequency)
										{
											echo '<div class="col">
														<label class="rwrapper">
															<input type="radio" name="frequency" id="auto-frequency" value="'.$key.'"> '.$frequency[1].' minutes
														</label>
														'.($frequency[0] > 0 ? '<div class="info-s">('.$frequency[0].'% bonus)</div>' : '').'
													</div>';
										}
									?>
								</div>
							</fieldset>
							<hr>
							<p class="title-s text-center"><i class="fas fa-arrow-right icon"></i>Payment Boost</p>
							<div class="clearfix"></div>
							<p class="desc-s">You can boost the payment by choosing one of them. As much as it is boosted, the Faucet Token cost will be increased!</p>
							<div class="clearfix"></div>
							<fieldset id="boost">
								<div class="row">
									<?php
										for($i = 1; $i <= $config['auto_faucet_boost']; $i++)
										{
											echo '<div class="col"><label class="rwrapper"><input type="radio" name="boost" id="auto-boost" value="'.$i.'"> '.$i.'x</label></div>';
										}
									?>
								</div>
							</fieldset>
							<div id="earnings"></div>
							<div class="text-center mt-2">
								<button type="submit" class="btn btn-primary" id="start-claim" disabled><i class="fas fa-arrow-right"></i> Start Auto Claim <i class="fas fa-arrow-left"></i></button>
							</div>
							</div>
							<div class="clearfix"></div>
						</form>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	  </div>
    </main>
<?php
	$script = "var faucet_token = '".GenAutoFaucetToken()."';";
	if($activeClaim) {
		// Protect Javascript
		$script .= "function startTimer(duration, total) {
			var timer = duration, minutes, seconds;
			int = setInterval(function () {
				minutes = parseInt(timer / 60, 10);
				seconds = parseInt(timer % 60, 10);
				
				var totalSeconds = total * 60, remainingSeconds = minutes * 60 + seconds;
				$('#progress').css('width', (remainingSeconds*100/totalSeconds) + '%');
				
				minutes = minutes < 10 ? '0' + minutes : minutes;
				seconds = seconds < 10 ? '0' + seconds : seconds;

				$('#time').html(minutes + ':' + seconds);

				if (--timer < 0) {
					clearInterval(int);
					timer = duration;
					validateClaim();
				}
			}, 1000);
		}
		function validateClaim() {
			$('#close-session').prop('disabled', true);
			$.ajax({
				type: 'POST',
				url: 'system/ajax.php',
				data: {a: 'validateClaim', token: faucet_token},
				dataType: 'json',
				success: function(data) {
					if(data.status != 0){
						var result = data.message;
						if(data.status == 200){
							result = result + '<div class=\"alert alert-success\" role=\"alert\"><i class=\"fas fa-circle-notch fa-spin fa-fw\"></i> Auto Claim Session is loading, please wait...</div>';
						} else {
							result = result + '<div class=\"alert alert-danger\" role=\"alert\"><i class=\"fas fa-circle-notch fa-spin fa-fw\"></i> Auto Claim Session is closing, please wait...</div>';
						}
						
						$('#claim-result').html(result);
						setTimeout(function(){ location.reload(); }, 3000);
					}else{
						$('#claim-result').html(data.message);
						$('#close-session').prop('disabled', false);
					}
				}
			});
		}
		$('#close-session').on('click',function(){
			$('#close-session').prop('disabled', true);
			$.ajax({
				type: 'POST',
				url: 'system/ajax.php',
				data: {a: 'endClaim', token: faucet_token},
				dataType: 'json',
				success: function(data) {
					if(data.status == 200){
						$('#claim-result').html(data.message);
						setTimeout(function(){ window.location.replace('".GenerateURL('faucet')."'); }, 1000);
					}else{
						$('#claim-result').html(data.message);
						$('#close-session').prop('disabled', false);
					}
				}
			});

			return false;
		});
		startTimer(".$remainingTime.", ".$frequencies[$claimSession['frequency']][1].");";
	}
	else 
	{
		$script .= "var coinLimit = ".$config['auto_faucet_max'].";
		$('#form-auto').on('change', 'input', function() {
			if ($('#auto-currency:checked').length > coinLimit) {
				$(this).prop('checked', false);
				alert('You can\'t claim more than '+coinLimit+' currencies at once!');
			}
			
		   var payout = $('#auto-payout:checked', '#form-auto').val();
		   var frequency = $('#auto-frequency:checked', '#form-auto').val();
		   var boost = $('#auto-boost:checked', '#form-auto').val();
		   var currency = $('#auto-currency:checked', '#form-auto').map(function(){
				 return this.value;
			}).get();

		   if(payout && frequency && boost) {
			   $('#start-claim').prop('disabled', false);
			   $.ajax({
					type: 'GET',
					url: 'system/ajax.php',
					data: {a: 'calcEarnings', token: faucet_token, payout: payout, currency: currency, frequency: frequency, boost: boost},
					dataType: 'json',
					success: function(data) {
						if(data.status == 200){
							$('#earnings').html(data.message);
						}else{
							$('#earnings').html(data.message);
							$('#start-claim').prop('disabled', true);
						}
					}
				});
		   }
		});

		$('input[name=payout]').on('change', function() {
			var payout = $('#auto-payout:checked', '#form-auto').val();

			$.each($(\"input[name='currency[]']\"),function(){
				$(this).prop('checked', false);
			});

			$('#currencies').addClass('d-none');
			if(payout == 2) {
				$.ajax({
					type: 'GET',
					url: 'system/ajax.php',
					data: {a: 'getCurrencies', token: faucet_token, payout: payout},
					dataType: 'json',
					success: function(data) {
						if(data.status == 200){
							$('#list-currencies').html(data.message);
							$('#currencies').removeClass('d-none');
						}else{
							$('#list-currencies').html(data.message);
						}
					}
				});
		   }
		});

		$('#form-auto').on('submit',function(){
			$('#start-claim').html('<i class=\"fas fa-circle-notch fa-spin\"></i> Please wait...').prop('disabled', true);
			var payout = $('#auto-payout:checked', '#form-auto').val();
			var frequency = $('#auto-frequency:checked', '#form-auto').val();
			var boost = $('#auto-boost:checked', '#form-auto').val();
			var currency = $('#auto-currency:checked', '#form-auto').map(function(){
				 return this.value;
			}).get();

			if(payout && frequency && boost) {
				$.ajax({
					type: 'POST',
					url: 'system/ajax.php',
					data: {a: 'startClaim', token: faucet_token, payout: payout, currency: currency, frequency: frequency, boost: boost},
					dataType: 'json',
					success: function(data) {
						if(data.status == 200){
							window.location.replace('".GenerateURL('faucet&x=claim')."');
						}else{
							$('#earnings').html(data.message);
							$('#start-claim').html('<i class=\"fas fa-arrow-right\"></i> Start Auto Claim <i class=\"fas fa-arrow-left\"></i>').prop('disabled', false);
						}
					}
				});
			}

			return false;
		});";
	}
	
	$packer = new JavaScriptPacker($script, 'Normal', true, false);
	$packed = $packer->pack();
		
	echo '<script>'.$packed.'</script>';
?>