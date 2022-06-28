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

	if(empty($ow_config['adscend_secret']))
	{
		$ow_config['adscend_secret'] = GenerateKey(12);
		$db->Query("UPDATE `offerwall_config` SET `config_value`='".$ow_config['adscend_secret']."' WHERE `config_name`='adscend_secret'");
	}

	if(empty($ow_config['adgem_hash']))
	{
		$ow_config['adgem_hash'] = GenerateKey(12);
		$db->Query("UPDATE `offerwall_config` SET `config_value`='".$ow_config['adgem_hash']."' WHERE `config_name`='adgem_hash'");
	}

	$message = '';
	if(isset($_POST['edit_offerwall'])){
		$posts = $db->EscapeString($_POST['set']);
		foreach ($posts as $key => $value){
			if($ow_config[$key] != $value){
				$db->Query("UPDATE `offerwall_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
				$ow_config[$key] = $value;
			}
		}
		
		$message = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
	}

	if(isset($_POST['settings'])){
		$posts = $db->EscapeString($_POST['set']);
		foreach ($posts as $key => $value){
			if($config[$key] != $value){
				$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
				$config[$key] = $db->EscapeString($value);
			}
		}
		
		$message = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
	}
?>
<section id="content" class="container_12"><?=$message?>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2><a href="https://offerwall.info/?tracking=60c9ac9710619" target="_blank">OfferWall Script</a> Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>API Key</strong></label>
					<div><input type="text" name="set[offerwall_key]" value="<?=$ow_config['offerwall_key']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret Key</strong></label>
					<div><input type="text" name="set[offerwall_secret]" value="<?=$ow_config['offerwall_secret']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Website URL</strong></label>
					<div><input type="text" name="set[offerwall_url]" value="<?=$ow_config['offerwall_url']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2><a href="https://cryptowall.info/?tracking=60c9ac9710619" target="_blank">CryptoWall</a> Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>API Key</strong></label>
					<div><input type="text" name="set[cryptowall_key]" value="<?=$ow_config['cryptowall_key']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret Key</strong></label>
					<div><input type="text" name="set[cryptowall_secret]" value="<?=$ow_config['cryptowall_secret']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Website URL</strong></label>
					<div><input type="text" name="set[cryptowall_url]" value="<?=$ow_config['cryptowall_url']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>OfferDaddy Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>App Token</strong></label>
					<div><input type="text" name="set[offerdaddy_token]" value="<?=$ow_config['offerdaddy_token']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>App Key</strong></label>
					<div><input type="text" name="set[offerdaddy_secret]" value="<?=$ow_config['offerdaddy_secret']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>OfferToro Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Pub ID</strong></label>
					<div><input type="text" name="set[offertoro_pub]" value="<?=$ow_config['offertoro_pub']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>App ID</strong></label>
					<div><input type="text" name="set[offertoro_app]" value="<?=$ow_config['offertoro_app']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret Key</strong></label>
					<div><input type="text" name="set[offertoro_secret]" value="<?=$ow_config['offertoro_secret']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Wannads Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>API Key</strong><small>Wannads API Key</small></label>
					<div><input type="text" name="set[wannads_key]" value="<?=$ow_config['wannads_key']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret</strong><small>Wannads Secret Key</small></label>
					<div><input type="text" name="set[wannads_secret]" value="<?=$ow_config['wannads_secret']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>AdWorkMedia Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Offerwall ID</strong></label>
					<div><input type="text" name="set[adwork_id]" value="<?=$ow_config['adwork_id']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Adscendmedia Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Profile ID</strong></label>
					<div><input type="text" name="set[adscend_profile]" value="<?=$ow_config['adscend_profile']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Publisher ID</strong></label>
					<div><input type="text" name="set[adscend_publisher]" value="<?=$ow_config['adscend_publisher']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>CPALead Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Direct Link</strong></label>
					<div><input type="text" name="set[cpalead_link]" value="<?=$ow_config['cpalead_link']?>" placeholder="https://viral782.com/list/357711" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Postback Password</strong></label>
					<div><input type="text" name="set[cpalead_password]" value="<?=$ow_config['cpalead_password']?>" placeholder="RandomString123" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>KiwiWall Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Offer Wall ID</strong><small>Kiwiwall Offer Wall ID</small></label>
					<div><input type="text" name="set[kiwiwall_id]" value="<?=$ow_config['kiwiwall_id']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>App Secret</strong><small>Matomy App Secret Key</small></label>
					<div><input type="text" name="set[kiwiwall_secret]" value="<?=$ow_config['kiwiwall_secret']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Persona.ly</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>App ID</strong></label>
					<div><input type="text" name="set[personaly_id]" value="<?=$ow_config['personaly_id']?>" placeholder="38510eb84109fa8a31109d3a06edf2d2" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Publisher Hash</strong></label>
					<div><input type="text" name="set[personaly_hash]" value="<?=$ow_config['personaly_hash']?>" placeholder="5c5987cbc7ad2d0016011665" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Publisher Secret Key</strong></label>
					<div><input type="text" name="set[personaly_secret]" value="<?=$ow_config['personaly_secret']?>" placeholder="c5646a96-0420-4532-96f9-b03b34f1070d" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>AdGem</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>App ID</strong></label>
					<div><input type="text" name="set[adgem_app]" value="<?=$ow_config['adgem_app']?>" placeholder="1234" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>TheoremReach</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Api Key</strong></label>
					<div><input type="text" name="set[tr_key]" value="<?=$ow_config['tr_key']?>" placeholder="ffre50dfad4ef58783ab78642721" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret Key</strong></label>
					<div><input type="text" name="set[tr_secret]" value="<?=$ow_config['tr_secret']?>" placeholder="9d082c1b856704ce949403866sd5e6adbe2046" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Monlix</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Api Key</strong></label>
					<div><input type="text" name="set[monlix_api]" value="<?=$ow_config['monlix_api']?>" placeholder="122a1e76ded1a1e89380e3b88a0531fc" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret Key</strong></label>
					<div><input type="text" name="set[monlix_secret]" value="<?=$ow_config['monlix_secret']?>" placeholder="1a71c8050e45d406f54f420f7ec5ae6557ed96d46ff24552103b084c6dfd896e" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_offerwall" value="Submit" />
				</div>
			</div>
		</form>
	</div>
	<div class="grid_6">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Offerwall Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Level Required</strong><small>Level required to unlock offerwalls (0 = disabled)</small></label>
					<div><input type="text" name="set[ow_level]" value="<?=$config['ow_level']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Exchange Rate</strong><small>How many offerwall credits for 1 Faucet Token</small></label>
					<div><input type="text" name="set[credit_exchange_rate]" value="<?=$config['credit_exchange_rate']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="settings" value="Submit" />
				</div>
			</div>
		</form>
		<div class="box">
			<div class="header">
				<h2>OfferWall Script Instructions</h2>
			</div>
			<div class="content">
				<div class="alert information">If you purchased CryptoWall script from <a href="https://offerwall.info/?tracking=60c9ac9710619" target="_blank">OfferWall.info</a> you can integrate your offerwall into this system.</div>
				<p><b>1)</b> Go to your OfferWall website and login.</p>
				<p><b>2)</b> Go to <i>Add Website</i> and configure your new offer wall as you wish.</p>
				<p><b>3)</b> When you create your new offer wall, at step 2, complete the Postback URL with:<br />
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/offerwall.php" onclick="select()" style="width:100%" />
				</p>
				<p><b>4)</b> At step 3 you will find <i>API Key</i> and <i>Secret Key</i>, required to configure your new offer wall.</p>
				<p><b>5)</b> Make sure you complete <i>Website URL</i> field with your OfferWall website URL, without trailing slash (Eg. https://yourofferwallsite.com)</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>CryptoWall Script Instructions</h2>
			</div>
			<div class="content">
				<div class="alert information">If you purchased CryptoWall script from <a href="https://cryptowall.info/?tracking=60c9ac9710619" target="_blank">CryptoWall.info</a> you can integrate your offerwall into this system.</div>
				<p><b>1)</b> Go to your CryptoWall website and login.</p>
				<p><b>2)</b> Go to <i>Add Website</i> and configure your new offer wall as you wish.</p>
				<p><b>3)</b> When you create your new offer wall, at step 2, complete the Postback URL with:<br />
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/cryptowall.php" onclick="select()" style="width:100%" />
				</p>
				<p><b>4)</b> At step 3 you will find <i>API Key</i> and <i>Secret Key</i>, required to configure your new offer wall.</p>
				<p><b>5)</b> Make sure you complete <i>Website URL</i> field with your CryptoWall website URL, without trailing slash (Eg. https://yourcryptowallsite.com)</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>OfferDaddy Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="https://www.offerdaddy.com/signup?r=12156" target="_blank">OfferDaddy</a> and create an account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Offer Wall</i>, click on <i>Add App</i> and configure your new offer wall as you wish.</p>
				<p><b>3)</b> When you create your new offer wall, complete <i>App's Postback</i> with:<br />
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/offerdaddy.php" onclick="select()" style="width:100%" />
				</p>
				<p><b>4)</b> At step 4 you will find required informations to configure your new offer wall.</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>OfferToro Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="https://www.offertoro.com" target="_blank">OfferToro</a> and create an account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>App Placements</i>, click on <i>Add App</i> and configure your new offer wall as you wish.</p>
				<p><b>3)</b> When you create your new offer wall, complete <i>Postback URL</i> with:<br />
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/offertoro.php?id={id}&oid={oid}&amount={amount}&user_id={user_id}&ip_address={ip_address}&payout={payout}" onclick="select()" style="width:100%" />
				</p>
				<p><b>4)</b> After you create your app, click on <i>Edit</i> to find required informations.</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>Wannads Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="https://publishers.wannads.com/?referral_code=GYW0JE" target="_blank">Wannads</a> and create an account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Apps</i> -> <i>Create App</i> and make sure you place URL from bellow at <i>Postback URL</i>
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/wannads.php" onclick="select()" style="width:100%" />
				</p>
				<p><b>3)</b> When you finish you will find API Key and Secret Key.</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>AdWorkMedia Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="http://www.adworkmedia.com/affiliate-publisher.php?ref=82695l" target="_blank">AdWorkMedia</a> and create an account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Tools</i> -> <i>Postback Services</i> -> <i>Add Global Postback</i> and make sure you place URL from bellow at <i>Add New Postback URL</i>
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/adworkmedia.php" onclick="select()" style="width:100%" />
				</p>
				<p><b>3)</b> Go to <i>Tools</i> -> <i>Offer Wall</i> -> <i>New Offer Wall</i> and configure your new offer wall as you wish, but make sure at <i>General Settings</i> -> <i>Postback Site Profile</i> you choose postback created before.</p>
				<p><b>4)</b> Copy Offer Wall ID from Direct Link (last 3-4 characters from URL, <a href="<?php echo $config['site_url']; ?>/admin-panel/img/info/adworkmedia.jpg" target="_blank">click here for example</a>).</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>AdScendMedia Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="https://adscendmedia.com/apply.php?refer=35272" target="_blank">AdScendMedia</a> and create an account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Postbacks</i>, click on <i>Add Postback</i>, check the boxes for <i>Leads</i> and <i>Rewarded Video Leads</i> and complete <i>URL</i> with the URL from bellow:</p>
				<p>
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/adscendmedia.php?secret=<?php echo $ow_config['adscend_secret']; ?>&offerid=[OID]&name=[ONM]&rate=[PAY]&reward=[CUR]&sub1=[SB1]&status=[STS]&transaction=[TID]&ip=[IP]" onclick="select()" style="width:100%" />
				</p>
				<p><b>3)</b> Go to <i>Offer Wall</i>, click on <i>Create New Offer Wall</i> configure your new offer wall as you wish, but make sure to complete <i>Currency Name</i> with <i>Credits</i>.</p>
				<p><b>4)</b> Go to <i>Offer Wall</i>, click on <i>Integration</i> (on your offer wall created before) and you will find out your Profile ID and Publisher ID as on this picture: <a href="<?php echo $config['site_url']; ?>/admin-panel/img/info/adscendmedia.jpg" target="_blank">Click Here</a></p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>CPALead Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="https://cpalead.com/get-started.php?ref=144787" target="_blank">CPALead</a> and create an account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Postback</i>, click on <i>Configuration</i>, complete <i>Password</i> with a random password (the same you add on <i>Postback Password</i> field) and complete <i>Enter Postback URL</i> with the URL from bellow:</p>
				<p>
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/cpalead.php?subid={subid}&payout={payout}&ip_address={ip_address}&lead_id={lead_id}&country_iso={country_iso}&password={password}&virtual_currency={virtual_currency}" onclick="select()" style="width:100%" />
				</p>
				<p><b>3)</b> Go to <i>Offer Wall</i>, click on <i>Create Offer Wall</i> configure your new offer wall as you wish, but make sure to complete <i>Name of {currency}</i> with <i>Credits</i>.</p>
				<p><b>4)</b> After you created your offerwall, complete <i>Direct Link</i> field with link provided by CPALead.</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>Kiwiwall Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="https://www.kiwiwall.com/" target="_blank">Kiwiwall</a> and create an account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Apps</i> -> <i><a href="https://www.kiwiwall.com/apps/create" target="_blank">New App</a></i> and make sure you place URL from bellow at <i>Postback URL</i>
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/kiwiwall.php" onclick="select()" style="width:100%" />
				</p>
				<p><b>3)</b> Copy Offer Wall ID from iFrame Code (about 34 random characters from URL) and <i>Secret Key</i>.</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>Persona.ly Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="https://persona.ly/" target="_blank">Persona.y</a> and create an account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Settings</i> -> <i><a href="https://sdk.persona.ly/app/apps" target="_blank">Ad Settings</a></i> and click on <i>Add New App</i></p>
				<p><b>3)</b> Select <i>Web</i> under <i>Choose platform</i>, complete your website name and URL and make sure you select <i>Rewards</i> on type.</p>
				<p><b>4)</b> Copy <i>ID</i> from your new created app and complete <i>App ID</i> field</p>
				<p><b>5)</b> Click on <i>Edit</i> on your new created offerwall and complete it as on this example: <a href="<?php echo $config['secure_url']; ?>/admin-panel/img/info/personaly.jpg" target="_blank">click here</a></p>
				<p><b>6)</b> At <i>Postback Settings</i> click on <i>Define Postback</i> and complete Postback URL with this:
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/personaly.php?user_id={user_id}&amount={amount}&offer_id={offer_id}&app_id={app_id}&payout={publisher_revenue}&signature={pub_signature}" onclick="select()" style="width:100%" />
				</p>
				<p><b>7)</b> Copy <i>Publisher Hash</i> and <i> Publisher Secret Key</i> and complete fields with the same name on settings.</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>AdGem Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="https://adgem.com/" target="_blank">AdGem.com</a> and create a publisher account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Apps</i>, click on <i>New App</i> then click on <i>My app is not currently on an App Store</i></p>
				<p><b>3)</b> Select <i>Desktop</i> under <i>Platform</i> and complete required informations.</p>
				<p><b>4)</b> Copy <i>App ID</i> from your new created app and complete <i>App ID</i> field</p>
				<p><b>5)</b> Click on <i>Options</i> -> <i>Offerwall</i> on your new created offerwall and configure it as you wish, but make sure you complete <i>Postback URL</i> using following URL:
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/adgem.php?user_id={player_id}&amount={amount}&transaction={transaction_id}&country={country}&payout={payout}&app_id={app_id}&campaign_id={campaign_id}&ip={ip}&hash=<?php echo $ow_config['adgem_hash']; ?>" onclick="select()" style="width:100%" />
				</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>TheoremReach Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="http://www.theoremreach.com/" target="_blank">TheoremReach.com</a> and create a publisher account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Apps</i>, click on <i>New App</i> then configure it as you want.</i></p>
				<p><b>3)</b> Click on your new create app, make sure you set <i>App Platform</i> to <i>Web</i> and complete <i>Callback URL</i> using following URL:
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/theoremreach.php" onclick="select()" style="width:100%" />
				</p>
				<p><b>4)</b> Copy <i>API Key</i> and complete required field here. As soon as they approve your app, make sure you set <i>Callback Options</i> to <i>Server-side</i> and <i>App Status</i> to </i>Live</i> then get your <i>Secret Key</i>.</i></p>
				<p><font color="red">Make sure you set your TheoremReach APP to <b>Live</b> mode before adding api keys here.</font></p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>Monlix Instructions</h2>
			</div>
			<div class="content">
				<p><b>1)</b> Go to <a href="https://publisher.monlix.com/" target="_blank">Monlix.com</a> and create a publisher account (or login if you're already registered).</p>
				<p><b>2)</b> Go to <i>Sites</i>, click on <i>Add New Site</i> then configure it as you want.</i></p>
				<p><b>3)</b> Complete <i>Postback</i> using following URL:
					<input type="text" value="<?php echo $config['secure_url']; ?>/system/gateways/monlix.php?userid={{userId}}&transactionid={{transactionId}}&reward={{rewardValue}}&payout={{payout}}&status={{status}}&userip={{userIp}}&country={{countryCode}}&secret={{secretKey}}" onclick="select()" style="width:100%" />
				</p>
				<p><b>4)</b> After your website gets approved, copy <i>API Key</i> and <i>Secret Key</i> and complete required fields here.</i></p>
			</div>
		</div>
	</div>
</section>