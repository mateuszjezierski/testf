<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$page = (isset($_GET['p']) ? $_GET['p'] : 0);
	$limit = 25;
	$start = (is_numeric($page) && $page > 0 ? ($page-1)*$limit : 0);

	$total_pages = $db->QueryGetNumRows("SELECT `id` FROM `faucet_claims`");
	include('../system/libs/Paginator.php');

	$urlPattern = GetHref('p=(:num)');
	$paginator = new Paginator($total_pages, $limit, $page, $urlPattern);
	$paginator->setMaxPagesToShow(5);
?>
<section id="content" class="container_12 clearfix">
	<h1 class="grid_12">Faucet Claims (<?=number_format($total_pages)?>)</h1>
	<div class="grid_12">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="25">#</th>
						<th>User</th>
						<th>Tokens</th>
						<th>Reward</th>
						<th>Payout</th>
						<th>Currencies</th>
						<th>Type</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$trans = $db->QueryFetchArrayAll("SELECT a.*, b.username FROM faucet_claims a LEFT JOIN users b ON b.id = a.user_id ORDER BY a.time DESC LIMIT ".$start.",".$limit."");
					
					if(!count($trans))
					{
						echo '<tr><td colspan="7"><center>There is no faucet claim yet!</center></td></tr>';
					}

					foreach($trans as $tra)
					{
						$coins = '';
						if(is_serialized($tra['coin']))
						{
							$getCurrencies = unserialize($tra['coin']);
							$coins = strtoupper(implode(', ', $getCurrencies));
						}
						else
						{
							$coins = empty($tra['coin']) ? 'Coins' : strtoupper($tra['coin']);
						}
				?>	
					<tr>
						<td><?=$tra['id']?></td>
						<td><?=('<a href="index.php?x=users&edit='.$tra['user_id'].'">'.$tra['username'].'</a>')?></td>
						<td><?=number_format($tra['tokens'])?> Tokens</td>
						<td><?=number_format($tra['reward'], 2)?> Coins</td>
						<td style="text-align:center"><?=($tra['payout'] == 2 ? 'FaucetPay' : ($tra['payout'] == 3 ? 'ExpressCrypto' : 'Account Balance'))?></td>
						<td style="text-align:center"><?=$coins?></td>
						<td style="text-align:center"><?=($tra['type'] == 1 ? '<font color="green">Auto Faucet</font>' : '<font color="blue">Manual Faucet</font>')?></td>
						<td><?=date('d M Y - H:i:s', $tra['time'])?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php if($total_pages > $limit){ ?>
			<div class="dataTables_wrapper">
			<div class="footer">
				<div class="dataTables_paginate paging_full_numbers">
				<?php 
					if ($paginator->getPrevUrl()) {
						echo '<a class="first paginate_button" href="'.$paginator->getPrevUrl().'">&laquo; Previous</a></li>';
					} else {
						echo '<a class="first paginate_button">&laquo; Previous</a>';
					}
					
					echo '<span>';

					foreach ($paginator->getPages() as $page) {
						if ($page['url']) {
							if($page['isCurrent']) {
								echo '<a class="paginate_active">'.$page['num'].'</a>';
							} else {
								echo '<a class="paginate_button" href="'. $page['url'].'">'.$page['num'].'</a>';
							}
						} else {
							echo '<a class="paginate_active">'.$page['num'].'</a>';
						}
					}
					
					echo '<span>';
					
					if ($paginator->getNextUrl()) {
						echo '<a class="last paginate_button" href="'.$paginator->getNextUrl().'">Next &raquo;</a></li>';
					}
				?>
				</div>
			</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>