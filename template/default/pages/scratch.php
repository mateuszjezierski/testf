<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$scratch_prizes = unserialize($config['scratch_prizes']);
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
						<?php echo $lang['l_552']; ?>
					</div>
					<div class="content">
						<div id="scratch-form">
							<div id="status"></div>
							<p class="infobox text-center"><?php echo lang_rep($lang['l_553'], array('-PRICE-' => $config['scratch_price'], '-WIN-' => $scratch_prizes[6])); ?></p>
							<div class="infobox text-center">
								<img src="static/img/scratch/scratch_ticket.png" class="img-fluid" /><br />
								<button class="btn btn-primary mt-1" id="buy-ticket"><i class="fas fa-ticket-alt"></i> <?php echo $lang['l_554']; ?></button>
							</div>
						</div>
						<div class="card mt-3">
							<div class="card-body">
								<h4 class="card-title mb-4 text-center"><?php echo $lang['l_556']; ?></h4>
								<div class="table-responsive">
									<table class="table table-striped text-center" id="myTable">
										<thead>
											<tr>
												<td scope="col">#</td>
												<td scope="col"><?php echo $lang['l_557']; ?></td>
												<td scope="col"><?php echo $lang['l_558']; ?></td>
												<td scope="col"><?php echo $lang['l_421']; ?></td>
												<td scope="col"><?php echo $lang['l_329']; ?></td>
											</tr>
										</thead>
										<tbody>
											<?php
												$records = $db->QueryFetchArrayAll("SELECT * FROM `scratch_games` ORDER BY `id` DESC LIMIT 10");
												
												foreach($records as $record)
												{
													echo '<tr><td>'.$record['id'].'</td><td>'.$record['bet'].' '.$lang['l_337'].'</td><td>'.$record['profit'].' '.$lang['l_337'].'</td><td>'.($record['status'] == 1 ? $lang['l_559'] : $lang['l_560']).'</td><td>'.date('d M Y - H:i', $record['time']).'</td></tr>';
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
	</main>
	<script>
		var token = '<?php echo $token; ?>';
		$(document.body).on('click', '#buy-ticket', function(e){
			$.post('system/ajax.php', {a: 'processScratch', token: token},
			function(data) {
				if(data.status == 200){
					$('#status').html('<div class=\"alert alert-info\" role=\"alert\"><i class=\"fa fa-cog fa-spin fa-fw\"></i> <?php echo $lang['l_145']; ?></div>');
					setTimeout(function(){ 
						$('#scratch-form').html(data.message);
					 }, 500);
				} else {
					$('#status').html(data.message);
				}
			},'json');
			
			return false;
		});
	</script>