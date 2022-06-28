<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$errMessage = '<div class="alert alert-info text-center" role="alert">'.$lang['l_239'].'</div>';
?>
	<main role="main" class="container">
      <div class="row">
		<?php 
			require(BASE_PATH.'/template/'.$config['theme'].'/common/sidebar.php');
		?>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<div class="my-3 p-3 bg-white rounded box-shadow box-style">
			  <div id="grey-box">
				<div class="title">
					<?=$lang['l_160']?>
				</div>
				<div class="content">
					<?=$errMessage?>
					<table class="table table-striped table-sm table-responsive-sm text-center">
						<thead class="thead-dark">
							<tr>
								<th>#</th>
								<th><?php echo $lang['l_318']; ?></th>
								<th><?php echo $lang['l_403']; ?></th>
								<th><?php echo $lang['l_67']; ?></th>
								<th><?php echo $lang['l_329']; ?></th>
							</tr>
						</thead>
						<tbody class="table-primary text-dark">
							<?php
								$requests = $db->QueryFetchArrayAll("SELECT * FROM `withdrawals` WHERE `user_id`='".$data['id']."' ORDER BY `id` DESC LIMIT 25");
								
								if(count($requests) == 0)
								{
									echo '<td colspan="6"><center>'.$lang['l_121'].'</center></td>';
								}
								else
								{
									foreach($requests as $request) 
									{
										echo '<tr><td>'.$request['id'].'</td><td>'.$request['crypto'].' '.strtoupper($request['coin']).' <i class="fa fa-exclamation-circle fa-fw text-info" data-toggle="tooltip" data-placement="top" title="'.number_format($request['bits'], 2).' '.$lang['l_337'].' - $'.$request['amount'].'"></i></td><td>'.paymentMethod($request['method'], 0, $request['coin']).' <i class="fa fa-exclamation-circle fa-fw text-info" data-toggle="tooltip" data-placement="top" title="'.$request['payment_info'].'"></i></td><td>'.($request['status'] == 0 ? '<i class="fas fa-circle-notch fa-spin fa-fw" data-toggle="tooltip" data-placement="top" title="'.$lang['l_166'].'"></i>' : ($request['status'] == 1 ? '<i class="fas fa-check fa-fw text-success" data-toggle="tooltip" data-placement="top" title="'.$lang['l_167'].'"></i>' : ($request['status'] == 3 ? '<i class="fas fa-undo fa-fw text-success" data-toggle="tooltip" data-placement="top" title="'.$lang['l_548'].'"></i>' : '<i class="fas fa-times-circle fa-fw text-danger" data-toggle="tooltip" data-placement="top" title="'.$lang['168'].': '.$request['reason'].'"></i>'))).'</td><td>'.date('d M Y - H:i', $request['time']).'</td></tr>';
									}
								}
								
								$total = $db->QueryFetchArray("SELECT COUNT(*) AS `total`, SUM(`amount`) AS `amount` FROM `withdrawals` WHERE `user_id`='".$data['id']."'");
							?>
						</tbody>
						<tfoot class="bg-info">
							<tr>
								<th colspan="3"><?php echo $lang['l_235'].': '.number_format($total['total'], 0); ?></th>
								<th colspan="3"><?php echo $lang['l_318'].': $'.number_format($total['amount'], 2); ?></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		  </div>
		</div>
	  </div>
    </main>
	<script> $(function () { $('[data-toggle="tooltip"]').tooltip() }); </script>