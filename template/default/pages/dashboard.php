<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$refs = $db->QueryFetchArray("SELECT COUNT(*) AS `total` FROM `users` WHERE `ref`='".$data['id']."'");
	$cms = $db->QueryFetchArray("SELECT SUM(`commission`) AS `total` FROM `ref_commissions` WHERE `user`='".$data['id']."'");
?>
	<main role="main" class="container">
      <div class="row">
		<?php 
			require(BASE_PATH.'/template/'.$config['theme'].'/common/sidebar.php');
		?>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<div class="my-3 p-3 bg-white rounded box-shadow box-style">
			  <?php
				// Warning message
				if(!empty($data['warn_message'])){
					if($data['warn_expire'] < time()){
						$db->Query("UPDATE `users` SET `warn_message`='', `warn_expire`='0' WHERE `id`='".$data['id']."'");
					}
					
					echo '<div class="alert alert-danger" role="alert">'.$data['warn_message'].'</div>';
				}
				
				// VPN / Proxy Warning
				if(!empty($UserIPData) && $UserIPData['status'] == 1){
					echo '<div class="alert alert-danger text-center" role="alert"><i class="fa fa-exclamation-triangle"></i> <b>'.$lang['l_484'].'</b> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i><br />'.$lang['l_485'].'</div>';
				}

				// Announcement
				$announcement = $db->QueryFetchArray("SELECT * FROM `announcement` ORDER BY `time` DESC LIMIT 1");
				if(!empty($announcement)){
					$style = ($announcement['type'] == 1 ? 'success' : ($announcement['type'] == 2 ? 'danger' : 'info'));
					if(empty($announcement['url'])) {
						echo '<div class="alert alert-'.$style.' text-center" role="alert">'.$announcement['message'].'</div>';
					} else {
						echo '<a href="'.$announcement['url'].'" style="text-decoration:none"><div class="alert alert-'.$style.' text-center" role="alert">'.$announcement['message'].'</div></a>';
					}
				}
			  ?>
			  <div id="grey-box">
				<div class="content">
					<h1 class="text-warning"><i class="fa fa-arrow-down"></i> <?php echo $lang['l_114']; ?> <i class="fa fa-arrow-down"></i></h1>
					<p class="infobox my-4"><?php echo $lang['l_115']; ?></p>
					<div class="row">
						<div class="col-lg-6 col-sm-12 d-flex align-items-stretch my-2">
							<div class="card text-dark text-center w-100">
							  <div class="card-header">
								<b><?php echo $lang['l_528']; ?></b>
							  </div>
							  <div class="card-body py-3 px-1">
									<a class="btn btn-secondary btn-sm w-100" href="<?=GenerateURL('ptc')?>"><i class="fas fa-ad fa-fw"></i> <?php echo $lang['l_95']; ?></a>
									<a class="btn btn-secondary btn-sm w-100 mt-1" href="<?=GenerateURL('shortlinks')?>"><i class="fa fa-link fa-fw"></i> <?php echo $lang['l_425']; ?></a>
									<a class="btn btn-secondary btn-sm w-100 mt-1" href="<?=GenerateURL('offers')?>"><i class="fa fa-list-alt fa-fw"></i> <?php echo $lang['l_464']; ?></a>
									<a class="btn btn-secondary btn-sm w-100 mt-1" href="<?=GenerateURL('mining')?>"><i class="fa fa-calculator fa-fw"></i> <?php echo $lang['l_402']; ?></a>
							  </div>
							</div>
						</div>
						<div class="col-lg-6 col-sm-12 d-flex align-items-stretch my-2">
							<div class="card text-dark text-center w-100">
							  <div class="card-header">
								<b><?php echo $lang['l_235']; ?></b>
							  </div>
							  <div class="card-body py-3 px-1">
									<a class="btn btn-info btn-md w-100 mt-4" href="<?=GenerateURL('manual')?>"><i class="fas fa-mouse fa-fw"></i> <?php echo $lang['l_535']; ?></a>
									<a class="btn btn-success btn-md w-100 mt-1" href="<?=GenerateURL('faucet')?>"><i class="fa fa-bolt fa-fw"></i> <?php echo $lang['l_536']; ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			  </div>
			  <div class="row">
				<div class="col-lg-6 col-sm-12 my-2 d-flex align-items-stretch">
				  <div id="dashboard-info">
					<table class="w-100">
						<tr>
							<td><i class="fa fa-check-circle fa-fw"></i> <?php echo $lang['l_38']; ?>:</td>
							<td class="text-right text-success"><?php echo number_format($data['account_balance'], 2).' '.$lang['l_337']; ?></td>
						</tr>
						<tr>
							<td><i class="fa fa-check-circle fa-fw"></i> <?php echo $lang['l_116']; ?>:</td>
							<td class="text-right text-success"><?php echo number_format($data['today_revenue'], 2).' '.$lang['l_337']; ?></td>
						</tr>
						<tr>
							<td><i class="fa fa-check-circle fa-fw"></i> <?php echo $lang['l_117']; ?>:</td>
							<td class="text-right text-success"><?php echo number_format($data['total_revenue'], 2).' '.$lang['l_337']; ?></td>
						</tr>
					</table>
				  </div>
				</div>
				<div class="col-lg-6 col-sm-12 my-2 d-flex align-items-stretch">
				  <div id="dashboard-info">
					<table class="w-100">
						<tr>
							<td><i class="fa fa-clock-o fa-fw"></i> <?php echo $lang['l_118']; ?>:</td>
							<td class="text-right text-warning"><?php echo number_format($data['today_claims']).' '.$lang['l_84']; ?></td>
						</tr>
						<tr>
							<td><i class="fa fa-clock-o fa-fw"></i> <?php echo $lang['l_119']; ?>:</td>
							<td class="text-right text-warning"><?php echo number_format($data['total_claims']).' '.$lang['l_84']; ?></td>
						</tr>
						<tr>
							<td><i class="fa fa-users fa-fw"></i> <?php echo $lang['l_104']; ?>:</td>
							<td class="text-right text-info"><?php echo number_format($refs['total']).' '.$lang['l_20']; ?></td>
						</tr>
						<tr>
							<td><i class="fa fa-users fa-fw"></i> <?php echo $lang['l_260']; ?>:</td>
							<td class="text-right text-info"><?php echo number_format($cms['total'], 2).' '.$lang['l_530']; ?></td>
						</tr>
					</table>
				  </div>
				</div>
			   </div>
			   <div class="row">
				<div class="col-lg-12 col-sm-12 my-2 d-flex align-items-stretch">
				  <div id="dashboard-info">
					<h1 class="text-warning"><?php echo $lang['l_120']; ?></h1>
					<p><center><?php echo lang_rep($lang['l_44'], array('-COMMISSION-' => $data['ref_com'])); ?></center></p>
					<div class="affiliate-url d-flex justify-content-center">
						<div class="form-group">
							<i class="fa fa-external-link text-dark"></i>
							<input type="text" class="form-control text-center" value="<?php echo $config['secure_url']; ?>/?ref=<?php echo $data['id']; ?>" onclick="this.select()" readonly>
						</div>
					</div>
					<div class="sharethis-inline-share-buttons" data-title="Join now to earn FREE Crypto!" data-description="Register for FREE and start earnings FREE Crypto currencies!" data-url="<?php echo $config['secure_url']; ?>/?ref=<?php echo $data['id']; ?>"></div>
				  </div>
				</div>
			  </div>
			</div>
		</div>
	  </div>
    </main>
	<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=6139fc40fb5a650012810e9c&product=inline-share-buttons" async="async"></script>