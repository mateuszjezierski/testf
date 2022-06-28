<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$secureFormID = array(
			'toggleCaptcha' => GenerateKey(rand(10,15)),
			'captcha' => GenerateKey(rand(10,15)),
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
						Bonus Roll
					</div>
					<div class="content">
						<p class="infobox mb-4"><?php echo lang_rep($lang['l_542'], array('-MIN-' => $data['bonus_roll'])); ?></p>
						<div class="row">
							<div class="col-lg-6 col-sm-12 d-flex align-items-stretch my-2">
								<div class="card text-dark text-center w-100">
								  <div class="card-header">
									<b><?php echo $lang['l_543']; ?></b>
								  </div>
								  <div class="card-body py-3 px-1">
									<?php
										$faucetLocked = false;
										if($data['bonus_roll_time'] > (time()-($data['bonus_roll']*60))) 
										{
											$last_claim = $db->QueryFetchArray("SELECT `number`,`reward` FROM `bonus_roll_claims` WHERE `user_id`='".$data['id']."' ORDER BY `time` DESC LIMIT 1");
									?>
										<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle fa-fw"></i> <?php echo $lang['l_429']; ?> <span id="claimTime"><?php echo remainingTime(($data['bonus_roll_time']+($data['bonus_roll']*60))-time()); ?></span></div>
										<small><i><?php echo lang_rep($lang['l_428'], array('-ROLL-' => number_format($last_claim['number']), '-SUM-' => number_format($last_claim['reward'], 2))); ?></i></small>
									<?php
										}
										elseif($config['bonus_roll_sl_required'] > 0 && $data['sl_today'] < $config['bonus_roll_sl_required'] && $count_sl >= ($config['bonus_roll_sl_required']-$data['sl_today']))
										{
											$faucetLocked = true;
											echo '<div class="alert alert-warning" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> <b>'.$lang['l_426'].'</b> <i class="fa fa-exclamation-triangle fa-fw"></i><br />'.lang_rep($lang['l_427'], array('-SUM-' => $config['bonus_roll_sl_required'] - $data['sl_today'])).'</span></div>';
											echo '<a href="'.GenerateURL('shortlinks').'" class="btn btn-info btn-md w-100 mt-2"><i class="fa fa-link fa-fw"></i> '.$lang['l_425'].'</a>';
										} 
										else 
										{
									?>
										<div class="alert alert-info mt-2" role="alert" id="loadingFaucet"><i class="fa fa-cog fa-spin fa-fw"></i> Faucet loading, please wait...</div>
										<div id="claimFaucet" class="d-none">
											<div id="luckyNumber">99,999</div>
											<div id="faucetMessage"></div>
											<div id="<?php echo $secureFormID['rollFaucet']; ?>">
												<?php
													if($config['faucet_recaptcha'] == 1 || $config['faucet_solvemedia'] == 1 || $config['faucet_raincaptcha'] == 1) 
													{
														echo '<select class="form-control form-control-sm custom-select mb-1" id="'.$secureFormID['toggleCaptcha'].'">'.($config['faucet_solvemedia'] == 1 ? '<option value="0">SolveMedia</option>' : '').($config['faucet_recaptcha'] == 1 ? '<option value="1">reCaptcha</option>' : '').($config['faucet_raincaptcha'] == 1 ? '<option value="2">rainCaptcha</option>' : '').'</select>';
														
														echo '<div class="d-flex justify-content-center">';
														if($config['faucet_solvemedia'] == 1)
														{
															echo '<div id="'.$secureFormID['captcha'].'_0" class="load_captcha"></div>';
														}
														if($config['faucet_recaptcha'] == 1)
														{
															echo '<div id="'.$secureFormID['captcha'].'_1" class="load_captcha'.($config['faucet_solvemedia'] == 0 ? '' : ' d-none').'"><div class="g-recaptcha" data-sitekey="'.$config['recaptcha_pub'].'"></div></div>';
														}
														if($config['faucet_raincaptcha'] == 1)
														{
															echo '<div id="'.$secureFormID['captcha'].'_2" class="load_captcha'.($config['faucet_solvemedia'] == 0 && $config['faucet_recaptcha'] == 0 ? '' : ' d-none').'"><script src="https://raincaptcha.com/base.js" type="application/javascript" async></script><div id="rain-captcha" data-key="'.$config['raincaptcha_public'].'"></div></div>';
														}
														echo '</div>';
													} 
												?>
												<button type="button" class="btn btn-danger btn-md w-100 mt-2"><i class="fa fa-forward fa-fw"></i> Roll &amp; Win <i class="fa fa-backward fa-fw"></i></button>
											</div>
										</div>
									<?php } ?>
								  </div>
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 d-flex align-items-stretch my-2">
								<div class="card text-center text-dark w-100">
									<div class="table-responsive">
										<table class="table table-striped table-hover table-light text-dark borderless text-center mb-0">
										  <thead>
											<tr>
											  <th scope="col">Lucky Number</th>
											  <th scope="col">Reward</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
												$prizes = $db->QueryFetchArrayAll("SELECT * FROM `bonus_roll` ORDER BY `id` ASC");

												foreach($prizes as $prize) {
													echo '<tr><td>Roll '.number_format($prize['small']).' to '.number_format($prize['big']).'</td><th scope="row">'.number_format($prize['reward']).' '.$lang['l_530'].'</th></tr>';
												}
											?>
										  </tbody>
										</table>
									</div>
									<p class="text-danger mt-4"><?php echo lang_rep($lang['l_218'], array('-BITS-' => number_format($config['jackpot_prize']))); ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
    </main>
	<script src="static/js/countUp.min.js" type="text/javascript"></script>
	<script>
<?php
	if($faucetLocked === false) 
	{
		if($data['bonus_roll_time'] < (time()-($data['bonus_roll']*60))) 
		{
			$script = "var RC_response = false;
			window.addEventListener('load', function(){if ('rainCaptcha' in window) {rainCaptcha.on('complete', function(data){RC_response = data;}); } }, false);

			$(document).ready(function() {
				$('#".$secureFormID['rollFaucet']." button').on('click',function(e){
					var captcha = $('#".$secureFormID['toggleCaptcha']."').find(':selected').val();
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
					$('#captcha').hide();
					$('#faucetMessage').html('<div class=\"alert alert-info\" role=\"alert\"><i class=\"fa fa-cog fa-spin fa-fw\"></i> Please wait...</div>');		

					$.post('system/ajax.php', {a: 'getBonusRoll', token: '".$token."', captcha: captcha, challenge: challenge, response: response},
					function(response) {
						if(response.status == 200){			
							window.setTimeout(function() {
								$('#faucetMessage').html(response.message);
							}, 2000);

							var roll = new CountUp('luckyNumber', 1, response.number, 0, 2, options);
							if (!roll.error) {
								roll.start();
							}
						} else if(response.status == 400){
							$('#faucetMessage').html(response.message);
						} else {
							$('#".$secureFormID['rollFaucet']."').show();
							$('#captcha').show();
							$('#faucetMessage').html(response.message);
						}
					},'json');
				});

				setTimeout(function(){
					".($config['faucet_solvemedia'] == 1 ? 'ACPuzzle.create("'.$config['solvemedia_c'].'", "'.$secureFormID['captcha'].'_0");' : '')."
					$('#loadingFaucet').addClass('d-none');
					$('#claimFaucet').removeClass('d-none');
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
			});";

			$packer = new JavaScriptPacker($script, 'Normal', true, false);
			$packed = $packer->pack();

			echo $packed;
		} else { 
?>
		$(document).ready(function () {
            $("#claimTime").countdown(<?php echo (($data['bonus_roll_time']+($data['bonus_roll']*60))*1000); ?>, {elapse: true}).on('update.countdown', function(event) {
                    if (event.elapsed) {
                        window.location.reload();
                    }
                    $(this).text(
                        event.strftime('<?php echo (($data['bonus_roll_time']+($data['bonus_roll']*60))-time() > 3600 ? '%H hours, %M minutes and %S seconds' : '%M minutes and %S seconds'); ?>')
                    );
            });
		});
<?php
		}
	}
?>
	</script>