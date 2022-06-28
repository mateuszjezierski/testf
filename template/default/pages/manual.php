<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$secureFormID = array(
			'toggleCaptcha' => GenerateKey(rand(10,15)),
			'captcha' => GenerateKey(rand(10,15)),
			'claimButton' => GenerateKey(rand(10,15)),
			'payoutInput' => GenerateKey(rand(10,15)),
			'currencyInput' => GenerateKey(rand(10,15)),
			'tokensInput' => GenerateKey(rand(10,15)),
			'rollFaucet' => GenerateKey(rand(10,15))
		);
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
						Manual Claim
					</div>
					<div class="content">
						<div class="claim-area">
							<form name="form-manual" id="form-manual">
								<p class="desc mt-0"><?php echo $lang['l_537']; ?></p>
								<p class="desc"><?php echo $lang['l_538']; ?></p>
								<hr />
								<div class="alert alert-info mt-2" role="alert" id="loadingFaucet"><i class="fa fa-cog fa-spin fa-fw"></i> <?php echo $lang['l_85']; ?></div>
								<div id="faucetMessage"></div>
								<div class="d-none" id="<?php echo $secureFormID['rollFaucet']; ?>">
									<div class="mid-300">
										<label for="<?php echo $secureFormID['payoutInput']; ?>"><?php echo $lang['l_287']; ?></label>
										<select class="form-control" name="<?php echo $secureFormID['payoutInput']; ?>" id="<?php echo $secureFormID['payoutInput']; ?>" required>
											<option value="1"><?php echo $lang['l_38']; ?> (<?php echo $lang['l_337']; ?>)</option>
											<option value="2">FaucetPay</option>
										</select>
									</div>
									<div class="mid-300 d-none" id="currencies"></div>
									<div class="mid-300">
										<label for="<?php echo $secureFormID['tokensInput']; ?>"><?php echo $lang['l_531']; ?></label>
											<div class="input-group">
												<input type="number" name="<?php echo $secureFormID['tokensInput']; ?>" id="<?php echo $secureFormID['tokensInput']; ?>" class="form-control" placeholder="0" min="1" max="<?php echo ($config['manual_faucet_max'] > $data['tokens'] ? $data['tokens'] : $config['manual_faucet_max']); ?>" required />
												<button class="btn btn-dark" type="button" id="max-tokens">MAX</button>
											</div>
									</div>
									<div class="mid-300" id="estimated"></div>
									<div class="mid-300">
										<?php
											if($config['faucet_recaptcha'] == 1 || $config['faucet_solvemedia'] == 1 || $config['faucet_raincaptcha'] == 1) 
											{
										?>
										<label for="<?php echo $secureFormID['toggleCaptcha']; ?>"><?php echo $lang['l_291']; ?></label>
										<select class="form-control captcha-select" id="<?php echo $secureFormID['toggleCaptcha']; ?>">
											<option selected disabled><?php echo $lang['l_539']; ?></option>
											<?php
												echo ($config['faucet_solvemedia'] == 1 ? '<option value="0">SolveMedia</option>' : '').($config['faucet_recaptcha'] == 1 ? '<option value="1">reCaptcha</option>' : '').($config['faucet_raincaptcha'] == 1 ? '<option value="2">rainCaptcha</option>' : '');
											?>
										</select>
										<div class="d-inline-block justify-content-center show-captcha">
											<?php
												if($config['faucet_solvemedia'] == 1)
												{
													echo '<div id="'.$secureFormID['captcha'].'_0" class="load_captcha mt-1 d-none"></div>';
												}
												if($config['faucet_recaptcha'] == 1)
												{
													echo '<div id="'.$secureFormID['captcha'].'_1" class="load_captcha mt-1 d-none"><div class="g-recaptcha" data-sitekey="'.$config['recaptcha_pub'].'"></div></div>';
												}
												if($config['faucet_raincaptcha'] == 1)
												{
													echo '<div id="'.$secureFormID['captcha'].'_2" class="load_captcha mt-1 d-none"><script src="https://raincaptcha.com/base.js" type="application/javascript" async></script><div id="rain-captcha" data-key="'.$config['raincaptcha_public'].'"></div></div>';
												}
											?>
										</div>
										<?php } ?>
									</div>
									<div class="mid-300 text-center">
										<button type="submit" class="btn btn-primary mt-1" id="<?php echo $secureFormID['claimButton']; ?>"><i class="fa fa-forward fa-fw"></i> <?php echo $lang['l_208']; ?> <i class="fa fa-backward fa-fw"></i></button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
    </main>
	<script>
<?php
	$script = "var RC_response = false;
	window.addEventListener('load', function(){if ('rainCaptcha' in window) {rainCaptcha.on('complete', function(data){RC_response = data;}); } }, false);

	$(document).ready(function() {
		$('#".$secureFormID['claimButton']."').on('click',function(e){
			var captcha = $('#".$secureFormID['toggleCaptcha']."').find(':selected').val();
			var payout = $('#".$secureFormID['payoutInput']."').find(':selected').val();
			var currency = $('#".$secureFormID['currencyInput']."').find(':selected').val();
			var ftokens = $('#".$secureFormID['tokensInput']."').val();
			var challenge = false;
			var response = false;
			var options = {
				useEasing: true,
				useGrouping: true,
				separator: ',',
				decimal: '.',
			};

			if ('rainCaptcha' in window) { 
				rainCaptcha.on('complete', function(data){
					response = data;
				}); 
			}

			if(captcha == 0) {
				challenge = $(\"[name='adcopy_challenge']\").val();
				response = $(\"[name='adcopy_response']\").val();
			} else if (captcha == 1) {
				response = grecaptcha.getResponse();
			} else if (captcha == 2) {
				response = RC_response;
			}

			$('#".$secureFormID['rollFaucet']."').hide();
			$('#faucetMessage').html('<div class=\"alert alert-info\" role=\"alert\"><i class=\"fa fa-cog fa-spin fa-fw\"></i> ".$lang['l_145']."</div>');		

			$.post('system/ajax.php', {a: 'getFaucet', token: '".$token."', ftokens: ftokens, currency: currency, payout: payout, captcha: captcha, challenge: challenge, response: response},
			function(data) {
				if(data.status == 200){
					$('#faucetMessage').html(data.message);
					setTimeout(function(){ location.reload(); }, 2500);
				} else {
					if (captcha == 1) {
						grecaptcha.reset();
					}
					$('#".$secureFormID['rollFaucet']."').show();
					$('#faucetMessage').html(data.message);
				}
			},'json');
			
			return false;
		});
		
		$('#max-tokens').on('click',function(e){
			$('#".$secureFormID['tokensInput']."').val('".($config['manual_faucet_max'] > $data['tokens'] ? $data['tokens'] : $config['manual_faucet_max'])."');
			calcEarnings();
		});

		setTimeout(function(){
			".($config['faucet_solvemedia'] == 1 ? 'ACPuzzle.create("'.$config['solvemedia_c'].'", "'.$secureFormID['captcha'].'_0");' : '')."
			$('#loadingFaucet').addClass('d-none');
			$('#".$secureFormID['rollFaucet']."').removeClass('d-none');
		}, 2500);

		$('#".$secureFormID['toggleCaptcha']."').on('change', function() {
			var captcha = this.value;
			$('.load_captcha').each(function(i, o) {
				if ($(this).attr('id') == '".$secureFormID['captcha']."_'+captcha) {
					$(this).removeClass('d-none');
				} else {
					$(this).addClass('d-none');
				}
			});
		});
		
		$('#".$secureFormID['payoutInput']."').on('change', function() {
			var payout = $('#".$secureFormID['payoutInput']."').find(':selected').val();
			var currency = $('#".$secureFormID['currencyInput']."').find(':selected').prop('selected', false);
			if(payout == 1) {
				$('#currencies').addClass('d-none');
				$('#".$secureFormID['tokensInput']."').attr('disabled', false);
			} else {
				$.ajax({
					type: 'GET',
					url: 'system/ajax.php',
					data: {a: 'getManualCurrencies', token: '".$token."', payout: payout, type: 'manual'},
					dataType: 'json',
					success: function(data) {
						if(data.status == 300){
							$('#".$secureFormID['tokensInput']."').attr('disabled', true);
							$('#currencies').html(data.message).removeClass('d-none');
						}else if(data.status == 200){
							$('#".$secureFormID['tokensInput']."').attr('disabled', false);
							$('#currencies').html('<label for=\"".$secureFormID['currencyInput']."\">".$lang['l_540']."</label><select class=\"form-control\" name=\"".$secureFormID['currencyInput']."\" id=\"".$secureFormID['currencyInput']."\" required>'+data.message+'</select>').removeClass('d-none');
						}else{
							$('#".$secureFormID['currencyInput']."').html(data.message);
							$('#currencies').addClass('d-none');
						}
					}
				});
			}
			calcEarnings();
		});
		
		$('#".$secureFormID['currencyInput']."').on('change', function() {
			calcEarnings();
		});
		
		$('#".$secureFormID['tokensInput']."').on('input', function() {
			calcEarnings();
		});
	});

	function calcEarnings() {
		var currency = $('#".$secureFormID['currencyInput']."').find(':selected').val();
		var payout = $('#".$secureFormID['payoutInput']."').find(':selected').val();
		var ftokens = $('#".$secureFormID['tokensInput']."').val();
		$.ajax({
			type: 'GET',
			url: 'system/ajax.php',
			data: {a: 'manualEarnings', token: token, ftokens: ftokens, currency: currency, payout: payout},
			dataType: 'json',
			success: function(data) {
				if(data.status == 200){
					$('#estimated').html(data.message);
				} else {
					$('#estimated').html('');
				}
			}
		});
	}";

	$packer = new JavaScriptPacker($script, 'Normal', true, false);
	$packed = $packer->pack();

	echo $packed;
?>
</script>