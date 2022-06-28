<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$message = '';
	$session = $db->QueryFetchArray("SELECT * FROM `dice_history` WHERE `user_id`='".$data['id']."' AND `open`='0' ORDER BY `id` DESC LIMIT 1");
	if(empty($session))
	{
		$game = diceGame();
		$db->Query("INSERT INTO `dice_history` (`user_id`,`salt`,`roll`,`claim_time`) VALUES ('".$data['id']."','".$game['salt']."','".$game['percent']."','".time()."')");
		$proof = sha1($game['salt'] . '+' . $game['percent']);
	}
	else
	{
		$proof = sha1($session['salt'] . '+' . $session['roll']);
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
						<?php echo $lang['l_550']; ?>
					</div>
					<div class="content">
						<div class="card text-dark">
							<div class="card-body">
								<p class="text-center"><small>Multiply your Coins by playing a <b>PROVABLY FAIR</b> HI-LO game! Set a Bet Amount and click Roll Over or Roll Under to roll the dice and <b>Mutiply your Coins</b>!</small></p><hr />
								<form id="diceForm">
									<div class="dice-row">
										<div class="row">
											<div class="col-md-5">
												<label for="multiplier">Win Chance</label>
												<div class="input-group mb-2 mr-sm-3">
													<div class="input-group-prepend">
														<div class="input-group-text"><i class="fas fa-percent"></i></div>
													</div>
													<input type="number" id="multiplier" class="form-control" value="49.5" min="2" max="97" step="0.1" placeholder="Win Chance">
												</div>

												<label for="profit">Profit</label>
												<div class="input-group mb-2 mr-sm-3">
													<div class="input-group-prepend">
														<div class="input-group-text"><i class="fas fa-trophy"></i></div>
													</div>
													<input type="text" id="profit" class="form-control" readonly>
												</div>

												<label for="betAmount">Bet Amount</label>
												<div class="input-group mb-2 mr-sm-3">
													<div class="input-group-prepend">
														<div class="input-group-text"><i class="fas fa-coins"></i></div>
													</div>
													<input type="number" class="form-control" id="betAmount" value="<?php echo $config['dice_min_bet']; ?>" min="<?php echo $config['dice_min_bet']; ?>" max="<?php echo $config['dice_max_bet']; ?>" step="0.01" placeholder="Bet Amount">
													<div class="input-group-append">
														<button type="button" id="half" class="btn btn-warning btn-sm">/2</button>
													</div>
													<div class="input-group-append">
														<button type="button" id="double" class="btn btn-success btn-sm">x2</button>
													</div>
												</div>
											</div>
											<div class="col-md-7">
												<div class="row">
													<div class="col-md-12 mb-2 mt-2">
														<label></label>
														<div id="result">
															<div class="alert alert-success text-center">You are ready to roll!</div>
														</div>
													</div>
													<div class="col-md-12 text-center">
														<div id="rollNumber">0000</div>
													</div>
													<div class="col-md-6">
														<button type="button" id="rollLo" class="btn btn-primary btn-outline bet-btn btn-block mt-1">Roll Lo</button>
													</div>
													<div class="col-md-6">
														<button type="button" id="rollHi" class="btn btn-secondary btn-outline bet-btn btn-block mt-1">Roll Hi</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<p class="text-center mt-3">Hash of Next Roll - <span class="font-weight-bold" id="hashRoll"><?php echo $proof; ?></span></p>
									<p class="text-center mb-0"><small>House Edge: <?php echo $config['dice_house_edge']; ?>% | <a href="javascript:void(0)" onclick="showRound(0)">Provably Fair</a></small></p>
								</form>
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
												<td scope="col">Target</td>
												<td scope="col">Bet</td>
												<td scope="col">Roll</td>
												<td scope="col">Profit</td>
												<td scope="col">Verify</td>
											</tr>
										</thead>
										<tbody id="diceHistory">
											<?php
												$history = $db->QueryFetchArrayAll("SELECT * FROM `dice_history` WHERE `user_id`='".$data['id']."' AND `open`='1' ORDER BY `id` DESC LIMIT 10");
												
												foreach ($history as $dice) {
													$target = ($dice['type'] == 1 ? '&lt;' : '&gt;') . $dice['target'];
													echo '<tr><th scope="row" data-toggle="tooltip" data-placement="top" title="'.$dice["salt"].'">'.$dice["id"].'</th><td>'.$target.'</td><td>'.$dice['bet'].'</td><td>' . $dice['roll'] . '</td><td>'.$dice['profit'].'</td><td><button type="button" class="btn btn-info btn-sm" onclick="showRound('.$dice['id'].');">Verify</button></td></tr>';
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
	<div id="verifyModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Verify Dice Roll <span id="rollID"></span></h5>
				</div>
				<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
				<div class="modal-body text-center" id="verifyForm">
					<form autocomplete="off" class="mb-2">
						<div class="row align-items-center">
							<div class="col-md-6">
								<label for="secret">Secret</label>
								<input type="text" name="secret" class="form-control" id="secret" placeholder="Secret">
							</div>
							<div class="col-md-1"><label>&nbsp;</label><br />+</div>
							<div class="col-md-3">
								<label for="roll">Roll</label>
								<input type="text" name="roll" class="form-control" id="roll" placeholder="Roll">
							</div>
							<div class="col-md-2">
								<label>&nbsp;</label><br />
								<button id="verifyRoll" class="btn btn-primary w-100">Verify</button>
							</div>
						</div>
					</form>
					<div id="verifyResult"></div>
					<p>This tool will help you easily verify a roll. Simply paste in the secret and the roll and press verify. This will output the same SHA1 hash that was displayed before you pressed the Roll Lo or Roll Hi button.</p>
					<p>The outcome of the next roll is displayed as a hash before you place your wager. This means the server decides it will roll a 20 before it knows how much you are betting or what your target is. You can verify a roll wasn't changed by copying the "Hash of next roll", then after playing that roll combine the "secret" and plus sign "+" and the roll "20" and perform a SHA1 hash. The resulting hash will be the same as the "Hash of next roll" that was displayed before you played that game.</p>
					<p>[secret]+[roll]=HASH</p>
					<p>Example: <small>9d45f162f6e735a1ee946ac1c4460526e3e7f2c2+43.47=61529ce3ee447392520fb6e4c59ba3ba3b4cb122</small></p>
				</div>
			</div>
		</div>
	</div>
	<script>
		var site_url = "<?php echo $config['secure_url']; ?>";
		var edge = "<?php echo $config['dice_house_edge']; ?>";
		var waitMsg = '<div class="alert alert-info" role="alert"><i class="fa fa-cog fa-spin fa-fw"></i> <?php echo $lang['l_145']; ?></div>';
		$(function () { $('[data-toggle="tooltip"]').tooltip() });
	</script>
	<script src="static/js/odometer.js"></script>
	<script src="static/js/dice.js"></script>