<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	/* Load offerwall settings */
	$ow_config = array();
	$ow_configs = $db->QueryFetchArrayAll("SELECT config_name,config_value FROM `offerwall_config`");
	foreach ($ow_configs as $con)
	{
		$ow_config[$con['config_name']] = $con['config_value'];
	}
	unset($ow_configs); 
	
	$method = (isset($_GET['x']) ? $_GET['x'] : 'none');
	$title = $lang['l_464'];
	$offer_wall = '<div class="alert alert-info mb-0 text-center" role="alert">'.$lang['l_465'].'</div>';
	switch($method) {
		case 'adworkmedia' :
			if(!empty($ow_config['adwork_id'])) {
				$title = 'AdWorkMedia';
				$offer_wall = '<iframe src="https://lockwall.xyz/wall/'.$ow_config['adwork_id'].'/'.$data['id'].'" width="100%" height="900px" style="border:0; padding:0; border-radius:5px; margin:0;" frameborder="0"></iframe>';
			}
			break;
		case 'cpalead' :
			if(!empty($ow_config['cpalead_link'])) {
				$title = 'CPALead';
				$offer_wall = '<iframe src="'.$ow_config['cpalead_link'].'&subid='.$data['id'].'" width="100%" height="900px" style="border:0; padding:0; border-radius:5px; margin:0;" frameborder="0"></iframe>';
			}
			break;
		case 'adscendmedia' :
			if(!empty($ow_config['adscend_publisher']) && !empty($ow_config['adscend_profile'])) {
				$title = 'AdscendMedia';
				$offer_wall = '<iframe src="https://asmwall.com/adwall/publisher/'.$ow_config['adscend_publisher'].'/profile/'.$ow_config['adscend_profile'].'?subid1=cb-'.$data['id'].'" width="100%" height="900px" frameborder="0" allowfullscreen></iframe>';
			}
			break;
		case 'wannads' :
			if(!empty($ow_config['wannads_key'])) {
				$title = 'Wannads';
				$offer_wall = '<iframe src="https://wall.wannads.com/wall?apiKey='.$ow_config['wannads_key'].'&userId='.$data['id'].'" style="width:100%;height:690px;border:0;border-radius:5px;"></iframe>';
			}
			break;
		case 'personaly' :
			if(!empty($ow_config['personaly_id']) && !empty($ow_config['personaly_secret'])) {
				$title = 'Personaly';
				$offer_wall = '<iframe src="https://persona.ly/widget/?appid='.$ow_config['personaly_id'].'&userid='.$data['id'].'&gender='.($data['gender'] == 2 ? 'f' : 'm').'" style="width:100%;height:690px;border:0;border-radius:5px;"></iframe>';
			}
			break;
		case 'cryptowall' :
			if(!empty($ow_config['cryptowall_url']) && !empty($ow_config['cryptowall_key']) && !empty($ow_config['cryptowall_secret'])) {
				$title = 'CryptoWall';
				$offer_wall = '<iframe src="'.$ow_config['cryptowall_url'].'/offerwall/'.$ow_config['cryptowall_key'].'/'.$data['id'].'" style="width:100%;height:690px;border:0;border-radius:5px;"></iframe>';
			}
			break;
		case 'offerwall' :
			if(!empty($ow_config['offerwall_url']) && !empty($ow_config['offerwall_key']) && !empty($ow_config['offerwall_secret'])) {
				$title = 'CryptoWall';
				$offer_wall = '<iframe src="'.$ow_config['offerwall_url'].'/offerwall/'.$ow_config['offerwall_key'].'/'.$data['id'].'" style="width:100%;height:690px;border:0;border-radius:5px;"></iframe>';
			}
			break;
		case 'theoremreach' :
			if(!empty($ow_config['tr_key'])) {
				$title = 'TheoremReach';
				$offer_wall = '<iframe src="https://theoremreach.com/respondent_entry/direct?api_key='.$ow_config['tr_key'].'&user_id='.$data['id'].'" style="width:100%;height:690px;border:0;border-radius:5px;"></iframe>';
			}
			break;
		case 'kiwiwall' :
			if(!empty($ow_config['kiwiwall_id'])) {
				$title = 'KiwiWall';
				$offer_wall = '<iframe src="https://www.kiwiwall.com/wall/'.$ow_config['kiwiwall_id'].'/'.$data['id'].'" style="width:100%;height:690px;border:0;border-radius:5px;"></iframe>';
			}
			break;
		case 'offerdaddy' :
			if(!empty($ow_config['offerdaddy_token'])) {
				$title = 'OfferDaddy';
				$offer_wall = '<iframe src="https://www.offerdaddy.com/wall/'.$ow_config['offerdaddy_token'].'/'.$data['id'].'/" style="height: 690px;width:100%;border:0;border-radius:5px;"></iframe>';
			}
			break;
		case 'offertoro' :
			if(!empty($ow_config['offertoro_pub']) && !empty($ow_config['offertoro_app'])) {
				$title = 'OfferToro';
				$offer_wall = '<iframe src="https://www.offertoro.com/ifr/show/'.$ow_config['offertoro_pub'].'/'.$data['id'].'/'.$ow_config['offertoro_app'].'" style="height: 690px;width:100%;border:0;border-radius:5px;"></iframe>';
			}
			break;
		case 'adgem' :
			if(!empty($ow_config['adgem_app'])) {
				$title = 'AdGem';
				$offer_wall = '<iframe src="https://api.adgem.com/v1/wall?playerid='.$data['id'].'&appid='.$ow_config['adgem_app'].'" style="height: 690px;width:100%;border:0;border-radius:5px;"></iframe>';
			}
			break;
		case 'monlix' :
			if(!empty($ow_config['monlix_api'])) {
				$title = 'Monlix';
				$offer_wall = '<iframe src="https://offers.monlix.com/?appid='.$ow_config['monlix_api'].'&userid='.$data['id'].'" style="height: 690px;width:100%;border:0;border-radius:5px;"></iframe>';
			}
			break;
		default :
			$offer_wall = '<div class="alert alert-info mb-0 text-center" role="alert">Please select your desired offerwall from above menu!</div>';
			break;
	}
	
	if(userLevel($data['id'], 1, $data['total_claims']) < $config['ow_level'])
	{
		$title = $lang['l_464'];
		$offer_wall = '<div class="alert alert-warning mb-0 text-center" role="alert">'.lang_rep($lang['l_527'], array('-LEVEL-' => $config['ow_level'])).'</div>';
	}

	$errMessage = '';
	if(isset($_POST['exchange']))
	{
		$credits = $db->EscapeString($_POST['credit']);
		
		if(!is_numeric($credits) || $credits < $config['credit_exchange_rate'])
		{
			$errMessage = '<div class="alert alert-danger mt-0 mb-2" role="alert"><i class="fa fa-exclamation-triangle"></i> <b>ERROR:</b> You can\'t exchange less than '.$config['credit_exchange_rate'].' credits!</div>';
		}
		elseif($credits > $data['ow_credits'])
		{
			$errMessage = '<div class="alert alert-danger mt-0 mb-2" role="alert"><i class="fa fa-exclamation-triangle"></i> <b>ERROR:</b> You don\'t have enough credits!</div>';
		}
		else
		{
			$bits = round($credits / $config['credit_exchange_rate']);
			$db->Query("UPDATE `users` SET `tokens`=`tokens`+'".$bits."', `ow_credits`=`ow_credits`-'".$credits."' WHERE `id`='".$data['id']."'");

			// Referral Commission
			if($data['ref'] > 0) {
				$ref_data = $db->QueryFetchArray("SELECT `last_activity` FROM `users` WHERE `id` = '".$data['ref']."' LIMIT 1");
				
				if(!empty($ref_data['last_activity']) && $ref_data['last_activity'] > (time() - ($config['ref_activity']*3600))) {
					$commission = number_format(($data['ref_com']/100)*$bits, 2, '.', '');
					ref_commission($data['ref'], $data['id'], $commission);
				}
			}
			
			$errMessage = '<div class="alert alert-success mt-0 mb-2" role="alert"><i class="fa fa-check"></i> <b>SUCCESS:</b> You exchanged '.number_format($credits, 2).' credits and you received '.number_format($bits).' tokens!</div>';
		}
	}
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
					<?php echo $title; ?>
				</div>
				<div class="content">
					<?php echo $errMessage; ?>
					<div class="card text-center text-dark w-100 mb-2">
						<table class="table table-striped text-center mb-0">
							<thead>
								<tr>
								  <th scope="col">Credits</th>
								  <th scope="col">Exchange Credits</th>
								  <th scope="col">Receive</th>
								  <th scope="col"></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<form method="post">
									  <td class="text-info"><?php echo number_format($data['ow_credits'], 2); ?> credits</td>
									  <td> <input autocomplete="off" type="text" min="0" name="credit" id="credit" class="form-control form-control-sm" placeholder="0"> </td>
									  <td class="text-warning align-middle"><span id="receiveBits">0</span> Tokens</td>
									  <td><button class="btn btn-warning btn-sm" type="submit" name="exchange"><i class="fa fa-exchange"></i></button></td>
									</form>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="infobox mb-2 text-center">
						<?php echo (!empty($ow_config['offerwall_url']) ? '<a href="'.GenerateURL('offers&x=offerwall').'" class="btn btn-secondary mb-1'.($method == 'offerwall' ? ' active' : '').'">OfferWall</a>' : ''); ?>
						<?php echo (!empty($ow_config['cryptowall_url']) ? '<a href="'.GenerateURL('offers&x=cryptowall').'" class="btn btn-secondary mb-1'.($method == 'cryptowall' ? ' active' : '').'">CryptoWall</a>' : ''); ?>
						<?php echo (!empty($ow_config['wannads_key']) ? '<a href="'.GenerateURL('offers&x=wannads').'" class="btn btn-secondary mb-1'.($method == 'wannads' ? ' active' : '').'">Wannads</a>' : ''); ?>
						<?php echo (!empty($ow_config['adgem_app']) ? '<a href="'.GenerateURL('offers&x=adgem').'" class="btn btn-secondary mb-1'.($method == 'adgem' ? ' active' : '').'">AdGem</a>' : ''); ?>
						<?php echo (!empty($ow_config['offertoro_pub']) ? '<a href="'.GenerateURL('offers&x=offertoro').'" class="btn btn-secondary mb-1'.($method == 'offertoro' ? ' active' : '').'">OfferToro</a>' : ''); ?>
						<?php echo (!empty($ow_config['offerdaddy_token']) ? '<a href="'.GenerateURL('offers&x=offerdaddy').'" class="btn btn-secondary mb-1'.($method == 'offerdaddy' ? ' active' : '').'">OfferDaddy</a>' : ''); ?>
						<?php echo (!empty($ow_config['kiwiwall_id']) ? '<a href="'.GenerateURL('offers&x=kiwiwall').'" class="btn btn-secondary mb-1'.($method == 'kiwiwall' ? ' active' : '').'">KiwiWall</a>' : ''); ?>
						<?php echo (!empty($ow_config['cpalead_link']) ? '<a href="'.GenerateURL('offers&x=cpalead').'" class="btn btn-secondary mb-1'.($method == 'cpalead' ? ' active' : '').'">CPALead</a>' : ''); ?>
						<?php echo (!empty($ow_config['tr_key']) ? '<a href="'.GenerateURL('offers&x=theoremreach').'" class="btn btn-secondary mb-1'.($method == 'theoremreach' ? ' active' : '').'">TheoremReach</a>' : ''); ?>
						<?php echo (!empty($ow_config['adwork_id']) ? '<a href="'.GenerateURL('offers&x=adworkmedia').'" class="btn btn-secondary mb-1'.($method == 'adworkmedia' ? ' active' : '').'">AdWorkMedia</a>' : ''); ?>
						<?php echo (!empty($ow_config['adscend_publisher']) ? '<a href="'.GenerateURL('offers&x=adscendmedia').'" class="btn btn-secondary mb-1'.($method == 'adscendmedia' ? ' active' : '').'">AdscendMedia</a>' : ''); ?>
						<?php echo (!empty($ow_config['personaly_id']) ? '<a href="'.GenerateURL('offers&x=personaly').'" class="btn btn-secondary mb-1'.($method == 'personaly' ? ' active' : '').'">Persona.ly</a>' : ''); ?>
						<?php echo (!empty($ow_config['monlix_api']) ? '<a href="'.GenerateURL('offers&x=monlix').'" class="btn btn-secondary mb-1'.($method == 'monlix' ? ' active' : '').'">Monlix</a>' : ''); ?>
					</div>
					<div class="card text-center text-dark w-100">
						<?php echo $offer_wall; ?>
					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
    </main>
	<script type="text/javascript" >
	  $(document).ready(function(){
			$("#credit").on('keyup',function(){
				var totalcostm= $("#credit").val() / <?php echo $config['credit_exchange_rate']; ?>;
				$("#receiveBits").html(Math.round(totalcostm));
			})
      });
  </script>