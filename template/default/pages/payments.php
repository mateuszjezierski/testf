<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$page = (isset($_GET['x']) ? $_GET['x'] : 0);
	$limit = 25;
	$start = (is_numeric($page) && $page > 0 ? ($page-1)*$limit : 0);
	$total_pages = $db->QueryGetNumRows("SELECT * FROM `withdrawals` WHERE `status`='1'");
	include(BASE_PATH.'/system/libs/Paginator.php');

	$urlPattern = GenerateURL('payments&x=(:num)');
	$paginator = new Paginator($total_pages, $limit, $page, $urlPattern);
	$paginator->setMaxPagesToShow(4);
	
	$sent_money = $db->QueryFetchArray("SELECT SUM(`amount`) AS `amount`, COUNT(`id`) AS `total` FROM `withdrawals` WHERE `status`='1'");
	$requests = $db->QueryFetchArrayAll("SELECT * FROM `withdrawals` WHERE `status`='1' ORDER BY `id` DESC LIMIT ".$start.",".$limit."");
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
						<?php echo $lang['l_439']; ?>
					</div>
					<div class="content">
						<h3 class="text-warning text-center"><?php echo lang_rep($lang['l_440'], array('-SUM-' => number_format($sent_money['total']), '-BTC-' => '$'.number_format($sent_money['amount'], 2))); ?></h3>
						<div class="card text-dark w-100 mt-4">
							<table class="table table-striped table-sm table-responsive-lg table-light text-dark borderless text-center">
								<thead>
									<tr>
										<th><?php echo $lang['l_337']; ?></th>
										<th><?php echo $lang['l_163']; ?> <i class="fa fa-exclamation-circle fa-fw text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $lang['l_161']; ?>"></i></th>
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
												echo '<tr><td>'.number_format($request['bits'], 2).' '.$lang['l_337'].'</td><td>'.$request['crypto'].' '.strtoupper($request['coin']).' <i class="fa fa-exclamation-circle fa-fw text-info" data-toggle="tooltip" data-placement="top" title="$'.number_format($request['amount'], 4).'"></i></td><td><a href="'.paymentMethod($request['method'], 1, $request['coin'], $request['payment_info']).'" target="_blank" data-toggle="tooltip" data-placement="top" title="'.(empty($request['payout_id']) ? '' : $request['payout_id']).'">'.paymentMethod($request['method']).'</a></td><td>'.date('d M Y - H:i', $request['time']).'</td></tr>';
											}
										}
									?>
								</tbody>
							</table>
						</div>
						<?php if($total_pages > $limit){ ?>
							<nav aria-label="Page navigation example">
								<ul class="pagination justify-content-center mt-3">
									<?php 
										if ($paginator->getPrevUrl()) {
											echo '<li class="page-item"><a class="page-link" href="'.$paginator->getPrevUrl().'">&laquo; Previous</a></li>';
										} else {
											echo '<li class="page-item disabled"><a href="#" class="page-link">&laquo; Previous</a></li>';
										}

										foreach ($paginator->getPages() as $page) {
											if ($page['url']) {
												if($page['isCurrent']) {
													echo '<li class="page-item active"><a class="page-link">'.$page['num'].'</a></li>';
												} else {
													echo '<li class="page-item"><a class="page-link" href="'. $page['url'].'">'.$page['num'].'</a></li>';
												}
											} else {
												echo '<li class="page-item disabled"><a class="page-link" href="#">'.$page['num'].'</a></li>';
											}
										}

										if ($paginator->getNextUrl()) {
											echo '<li class="page-item"><a class="page-link" href="'.$paginator->getNextUrl().'">Next &raquo;</a></li>';
										}
									?>
								</ul>
							</nav>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	  </div>
    </main>
	<script> $(function () { $('[data-toggle="tooltip"]').tooltip() }); </script>