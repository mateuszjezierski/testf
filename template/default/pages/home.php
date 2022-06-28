<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$faqs = $db->QueryFetchArrayAll("SELECT question,answer FROM `faq` ORDER BY id ASC LIMIT 5");
	$users = $db->QueryFetchArray("SELECT COUNT(*) AS `total`, SUM(`sl_total`) AS `short` FROM `users`");
	$claims = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `faucet_claims`");
	$offers = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `completed_offers`");
	$sent_money = $db->QueryFetchArray("SELECT SUM(`amount`) AS `amount` FROM `withdrawals` WHERE `status`='1'");
	$getCurrencies = $db->QueryFetchArrayAll("SELECT `coin`,`stock`,`name` FROM `coins` WHERE `status`='1'");
	$requests = $db->QueryFetchArrayAll("SELECT * FROM `withdrawals` WHERE `status`='1' ORDER BY `id` DESC LIMIT 10");
?>
	<main role="main" class="container">
      <div class="row">
		<div class="col-12">
			<div class="my-3 rounded">
				<div id="home-box">
					<div class="content">
						<div class="row">
							<div class="col-md-6">
								<h2 class="text-warning text-center"><i class="fa fa-arrow-down"></i> <?php echo $lang['home_1']; ?> <i class="fa fa-arrow-down"></i></h2>
								<p class="text-center mb-4"><?php echo $lang['home_2']; ?></p><br /><br />
								<h4 class="text-warning text-center mt-4"><?php echo lang_rep($lang['home_8'], array('-SUM-' => '$'.number_format($sent_money['amount'], 2))); ?></h2>
								<p class="mt-4 text-center"><a class="btn btn-warning btn-lg" href="javascript:void(0)" data-toggle="modal" data-target="#registrationModal"><b><?php echo $lang['home_17']; ?></b> <i class="fa fa-mouse-pointer"></i></a></p>
							</div>
							<div class="col-md-6">
								<p class="mt-3 text-center"><img src="static/img/intro.png" class="img-fluid" alt="" /></p>
							</div>
							</div>
					</div>
				</div>
			</div>
			<div class="row home-stats-block">
				<div class="col-md-3">
					<div class="home-stats">
						<span><i class="fa fa-users"></i> <?php echo number_format($users['total']); ?></span><br /><?php echo $lang['home_18']; ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="home-stats">
						<span><i class="fa fa-clock-o"></i> <?php echo number_format($claims['total']); ?></span><br /><?php echo $lang['home_19']; ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="home-stats">
						<span><i class="fa fa-briefcase"></i> <?php echo number_format($offers['total']); ?></span><br /><?php echo $lang['home_20']; ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="home-stats">
						<span><i class="fa fa-external-link"></i> <?php echo number_format($users['short']); ?></span><br /><?php echo $lang['home_21']; ?>
					</div>
				</div>
			</div>
			<div class="my-3">
				<div id="home-box">
					<div class="content">
						<div class="row text-center mt-3">
							<div class="col-lg-4 col-sm-12">
								<h1 class="text-light"><?php echo $lang['home_4']; ?></h1><hr class="global" />
								<p><?php echo $lang['home_5']; ?></p>
							</div>
							<div class="col-lg-4 col-sm-12">
								<h1 class="text-light"><?php echo $lang['home_6']; ?></h1><hr class="global" />
								<p><?php echo $lang['home_7']; ?></p>
							</div>
							<div class="col-lg-4 col-sm-12">
								<h1 class="text-light"><?php echo $lang['home_9']; ?></h1><hr class="global" />
								<p><?php echo $lang['home_10']; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-md-6 mb-3">
					<div id="home-info-box">
						<div class="content">
						<h2><?php echo $lang['home_11']; ?></h2>
						<hr />
						<?php echo $lang['home_13']; ?>
						<h5 class="text-warning text-center mb-0"><?php echo $lang['home_15']; ?></h5>
						</div>
					</div>
				</div>
				<div class="col-md-6 mb-3">
					<div id="home-info-box">
						<div class="content">
						<h2><?php echo $lang['home_12']; ?></h2>
						<hr />
						<?php echo lang_rep($lang['home_14'], array('-MIN-' => $config['faucet_time'])); ?>
						<h5 class="text-warning text-center mb-0"><?php echo lang_rep($lang['home_16'], array('-COINS-' => count($getCurrencies))); ?></h5>
						</div>
					</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-12">
					<div id="home-info-box">
						<div class="content">
							<h2 class="text-warning text-center"><i class="fas fa-money-bill"></i> <?php echo $lang['l_437']; ?> <i class="fas fa-money-bill"></i></h2>
							<div class="card mt-3 text-dark w-100">
								<table class="table table-striped table-sm table-responsive-lg table-light text-dark borderless text-center">
									<thead>
										<tr>
											<th><?php echo $lang['l_337']; ?></th>
											<th><?php echo $lang['l_318']; ?></th>
											<th><?php echo $lang['l_403']; ?></th>
											<th><?php echo $lang['l_329']; ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
											if(count($requests) == 0)
											{
												echo '<tr><td colspan="4"><center>'.$lang['l_121'].'</center></td></tr>';
											}
											else
											{
												foreach($requests as $request) 
												{
													echo '<tr><td>'.number_format($request['bits'], 2).' '.$lang['l_337'].'</td><td>'.$request['crypto'].' '.strtoupper($request['coin']).' <i class="fa fa-exclamation-circle fa-fw text-info" data-toggle="tooltip" data-placement="top" title="'.number_format($request['bits'], 2).' '.$lang['l_337'].' - $'.number_format($request['amount'], 4).'"></i></td><td><a href="'.paymentMethod($request['method'], 1, $request['coin'], $request['payment_info']).'" target="_blank" data-toggle="tooltip" data-placement="top" title="'.(empty($request['payout_id']) ? '' : $request['payout_id']).'">'.paymentMethod($request['method']).'</a></td><td>'.date('d M Y - H:i', $request['time']).'</td></tr>';
												}
											}
										?>
									</tbody>
								</table>
							</div>
							<p class="text-right mt-3 mb-0"><small><a href="<?php echo GenerateURL('payments'); ?>" title="<?php echo $lang['l_439']; ?>" class="text-light"><?php echo $lang['l_441']; ?></a></small></p>
						</div>
					</div>
				</div>
			</div>
			<div class="mb-3">
				<div id="home-box">
					<div class="content">
						<h2 class="text-warning text-center"><?php echo $lang['l_547']; ?></h2>
						<div class="row mt-3">
							<div class="col-lg-5 col-sm-12">
								<a href="<?php echo GenerateURL('faq'); ?>"><img src="static/img/faq.png" class="img-fluid" alt="" /></a>
							</div>
							<div class="col-lg-7 col-sm-12">
								<?php
									if(count($faqs) == 0){
										echo '<div class="alert alert-info" role="alert">'.$lang['l_121'].'</div>';
									}
								?>
								 <div class="accordion" id="faq">
								  <?php
									$j = 0;
									foreach($faqs as $faq){
										$j++;
								  ?>
									<div class="card">
										<div class="card-header" id="faqhead<?=$j?>">
											<a href="#" class="btn btn-header-link<?=($j > 1 ? ' collapsed' : '')?>" data-toggle="collapse" data-target="#faq<?=$j?>" aria-expanded="true" aria-controls="faq<?=$j?>"><?=$faq['question']?></a>
										</div>
										<div id="faq<?=$j?>" class="collapse<?=($j > 1 ? '' : ' show')?>" aria-labelledby="faqhead<?=$j?>" data-parent="#faq">
											<div class="card-body">
												<?=BBCode(nl2br($faq['answer']))?>
											</div>
										</div>
									</div>
								  <?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="home-box">
				<div class="content">
					<h2 class="text-warning text-center mt-0 mb-3">Supported Currencies</h2>
					<div class="row currencies mt-3">
						<?php
							foreach($getCurrencies as $currency)
							{
						?>
							<div class="col-md-3 col-sm-6">
								<div class="item">
									<div class="text-center">
										<img src="files/coins/<?php echo $currency['coin']; ?>-logo.png" alt="<?php echo $currency['name']; ?>">
									</div>
									<h5 class="text-center mt-1"><?php echo $currency['name']; ?></h5>
									<p class="text-center mt-2"><small>$<?php echo $coin_value[$currency['coin']]; ?></small></p>
								</div>
							</div>
						<?php
							}
						?>
					</div>
				</div>
			</div>
		</div>
	  </div>
    </main>
	<script> $(function () { $('[data-toggle="tooltip"]').tooltip() }); </script>