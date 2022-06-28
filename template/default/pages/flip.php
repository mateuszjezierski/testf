<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
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
						<?php echo $lang['l_551']; ?>
					</div>
					<div class="content">
						<div class="card">
							<div class="card-body text-dark">
								<p class="text-center"><small>Play Coin Flip Game and multiply your coins. Select your bet and try to predict winning coin. Good luck!</small></p>
								<div class="row">
									<div class="col-md-7">
										<form id="betForm">
											<div id="result"><div class="alert alert-info text-center">Place your bet. Good luck!</div></div>
											<div class="row">
												<div class="col-md-12">
													<label for="betAmount">Bet Amount</label>
													<div class="input-group mb-2 mr-sm-3">
														<div class="input-group-prepend">
															<div class="input-group-text"><i class="fas fa-coins"></i></div>
														</div>
														<input type="number" class="form-control" id="betAmount" value="<?php echo number_format($config['coinflip_min_bet'], 2); ?>" min="<?php echo $config['coinflip_min_bet']; ?>" max="<?php echo $config['coinflip_max_bet']; ?>" step="0.01" placeholder="Bet Amount">
														<div class="input-group-append">
															<button type="button" id="half" class="btn btn-warning btn-sm">/2</button>
														</div>
														<div class="input-group-append">
															<button type="button" id="double" class="btn btn-success btn-sm">x2</button>
														</div>
													</div>
												</div>
												<div class="col-md-6"><button type="submit" id="betBTC" class="btn btn-block btn-warning bet-btn mb-1" data-coin="BTC">Bet on Bitcoin</button></div>
												<div class="col-md-6"><button type="submit" id="betETH" class="btn btn-block btn-info bet-btn mb-1" data-coin="ETH">Bet on Ethereum</button></div>
											</div>
										</form>
									</div>
									<div class="col-md-5">
										<div id="coinflip">
											<div id="bitcoin"></div>
											<div id="ethereum"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card mt-2">
							<div class="card-body">
								<h4 class="card-title mb-4 text-center">Previous Games</h4>
								<div class="table-responsive">
									<table class="table table-striped text-center">
										<thead>
											<tr>
												<td scope="col">#</td>
												<td scope="col">Bet Amount</td>
												<td scope="col">Bet Coin</td>
												<td scope="col">Result</td>
												<td scope="col">Profit</td>
											</tr>
										</thead>
										<tbody id="coinflipHistory">
											<?php
												$records = $db->QueryFetchArrayAll("SELECT * FROM `coinflip_history` ORDER BY `id` DESC LIMIT 10");
												
												foreach($records as $record)
												{
													echo '<tr><td>'.$record['id'].'</td><td>'.$record['bet_amount'].'</td><td>'.$record['coin'].'</td><td>'.$record['result'].'</td><td>'.$record['profit'].'</td></tr>';
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
    </main>
	<script>
		var site_url = "<?php echo $config['secure_url']; ?>";
		$(function () { $('[data-toggle="tooltip"]').tooltip() });
	</script>
	<script src="static/js/coinflip.js"></script>