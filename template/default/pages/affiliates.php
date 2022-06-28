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
			  <div id="grey-box">
				<div class="title">
					<?=$lang['l_20']?>
				</div>
				<div class="content">
					<div class="infobox">
						<h1><?php echo $lang['l_100']; ?></h1>
						<p><center><?php echo lang_rep($lang['l_102'], array('-COMMISSION-' => $data['ref_com'])); ?></center></p>
						<?php if($config['ref_activity'] > 0) { ?>
							<p><center><?php echo lang_rep($lang['l_240'], array('-TIME-' => $config['ref_activity'])); ?></center></p>
						<?php } ?>
						<div class="affiliate-url d-flex justify-content-center">
							<div class="form-group">
								<i class="fa fa-external-link"></i>
								<input type="email" class="form-control text-center" value="<?php echo $config['secure_url']; ?>/?ref=<?php echo $data['id']; ?>" onclick="this.select()" readonly>
							</div>
						</div>
						<div class="sharethis-inline-share-buttons" data-title="Join now to earn FREE Crypto!" data-description="Register for FREE and start earnings FREE Crypto currencies!" data-url="<?php echo $config['secure_url']; ?>/?ref=<?php echo $data['id']; ?>"></div>
					</div>
					<div id="aff-block" class="infobox pb-4">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="50%">
									<p class="aff_block_p"><?php echo $lang['l_104']; ?>:</p>
									<a class="aff_block_p2" href="<?=GenerateURL('referrals')?>"><?php echo number_format($refs['total']).' '.$lang['l_20']; ?></a>
								</td>
								<td width="50%">
									<p class="aff_block_p"><?php echo $lang['l_105']; ?>:</p>
									<a class="aff_block_p2" href="<?=GenerateURL('referrals')?>"><?php echo number_format($cms['total'], 2).' <font class="text-success">'.$lang['l_530'].'</font>'; ?></a>
								</td>
							</tr>
						</table>
						<div class="text-center mt-2"><?php echo $lang['l_502']; ?></div>
					</div>
					<div class="clearfix"></div>
					<div class="infobox w-100">              
						<div class="aff-banner-title w-50">Banner (468x60)</div><br> 
						<table width="100%" border="0" cellpadding="3" cellspacing="1">
							<tr>
								<td valign="top" align="center">
									<img src="<?=$config['secure_url']?>/promo/468x60.png" class="img-fluid" border="0" />
								</td>
							</tr>
							<tr>    
								<td valign="top" align="center">
									<b>HTML Code</b><br>
									<textarea class="form-control w-75" onclick="this.select()" row="3" readonly="true"><a href="<?=$config['secure_url']?>/?ref=<?=$data['id']?>" target="_blank"><img src="<?=$config['secure_url']?>/promo/468x60.png" alt="<?=$config['site_name']?>" border="0" /></a></textarea>
								</td>
							</tr>
							<tr>    
								<td valign="top" align="center">
									<b>BB Code</b><br>
									<textarea class="form-control w-75" onclick="this.select()" row="1" readonly="true">[url=<?=$config['secure_url']?>/?ref=<?=$data['id']?>][img]<?=$config['secure_url']?>/promo/468x60.png[/img][/url]</textarea>                        
								</td>
							</tr>                   
						</table>
					</div>
					<div class="clearfix"></div>
					<div class="infobox w-100">              
						<div class="aff-banner-title w-50">Banner (728x90)</div><br> 
						<table width="100%" border="0" cellpadding="3" cellspacing="1">
							<tr>
								<td valign="top" align="center">
									<img src="<?=$config['secure_url']?>/promo/728x90.png" class="img-fluid" border="0" />
								</td>
							</tr>
							<tr>    
								<td valign="top" align="center">
									<b>HTML Code</b><br>
									<textarea class="form-control w-75" onclick="this.select()" row="3" readonly="true"><a href="<?=$config['secure_url']?>/?ref=<?=$data['id']?>" target="_blank"><img src="<?=$config['secure_url']?>/promo/728x90.png" alt="<?=$config['site_name']?>" border="0" /></a></textarea>
								</td>
							</tr>
							<tr>    
								<td valign="top" align="center">
									<b>BB Code</b><br>
									<textarea class="form-control w-75" onclick="this.select()" row="1" readonly="true">[url=<?=$config['secure_url']?>/?ref=<?=$data['id']?>][img]<?=$config['secure_url']?>/promo/728x90.png[/img][/url]</textarea>                        
								</td>
							</tr>                   
						</table>
					</div>
					<div class="clearfix"></div>
					<div class="infobox w-100">              
						<div class="aff-banner-title w-50">Banner (200x200)</div><br> 
						<table width="100%" border="0" cellpadding="3" cellspacing="1">
							<tr>
								<td valign="top" align="center">
									<img src="<?=$config['secure_url']?>/promo/200x200.png" class="img-fluid" border="0" />
								</td>
							</tr>
							<tr>    
								<td valign="top" align="center">
									<b>HTML Code</b><br>
									<textarea class="form-control w-75" onclick="this.select()" row="3" readonly="true"><a href="<?=$config['secure_url']?>/?ref=<?=$data['id']?>" target="_blank"><img src="<?=$config['secure_url']?>/promo/200x200.png" alt="<?=$config['site_name']?>" border="0" /></a></textarea>
								</td>
							</tr>
							<tr>    
								<td valign="top" align="center">
									<b>BB Code</b><br>
									<textarea class="form-control w-75" onclick="this.select()" row="1" readonly="true">[url=<?=$config['secure_url']?>/?ref=<?=$data['id']?>][img]<?=$config['secure_url']?>/promo/200x200.png[/img][/url]</textarea>                        
								</td>
							</tr>                   
						</table>
					</div>
					<div class="clearfix"></div>
					<div class="infobox w-100">              
						<div class="aff-banner-title w-50">Banner (320x50)</div><br> 
						<table width="100%" border="0" cellpadding="3" cellspacing="1">
							<tr>
								<td valign="top" align="center">
									<img src="<?=$config['secure_url']?>/promo/320x50.png" class="img-fluid" border="0" />
								</td>
							</tr>
							<tr>    
								<td valign="top" align="center">
									<b>HTML Code</b><br>
									<textarea class="form-control w-75" onclick="this.select()" row="3" readonly="true"><a href="<?=$config['secure_url']?>/?ref=<?=$data['id']?>" target="_blank"><img src="<?=$config['secure_url']?>/promo/320x50.png" alt="<?=$config['site_name']?>" border="0" /></a></textarea>
								</td>
							</tr>
							<tr>    
								<td valign="top" align="center">
									<b>BB Code</b><br>
									<textarea class="form-control w-75" onclick="this.select()" row="1" readonly="true">[url=<?=$config['secure_url']?>/?ref=<?=$data['id']?>][img]<?=$config['secure_url']?>/promo/320x50.png[/img][/url]</textarea>                        
								</td>
							</tr>                   
						</table>
					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
    </main>
	<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=6139fc40fb5a650012810e9c&product=inline-share-buttons" async="async"></script>