<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$message = '';
	if(isset($_POST['edit'])){
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
				<h2>FaucetPay</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>API Key</strong><small>Your faucet API Key</small></label>
					<div><input type="text" name="set[fp_api_key]" value="<?=$config['fp_api_key']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Username</strong><small>Your FaucetPay Username</small></label>
					<div><input type="text" name="set[faucetpay_username]" value="<?=$config['faucetpay_username']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Coinbase (Withdraw)</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>API Key</strong><small>Coinbase API Key</small></label>
					<div><input type="text" name="set[coinbase_withdraw_api]" value="<?=$config['coinbase_withdraw_api']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret Key</strong><small>Coinbase shared secret key</small></label>
					<div><input type="text" name="set[coinbase_withdraw_secret]" value="<?=$config['coinbase_withdraw_secret']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Coinbase Commerce (Deposit)</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>API Key</strong><small>Coinbase API Key</small></label>
					<div><input type="text" name="set[coinbase_api]" value="<?=$config['coinbase_api']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret Key</strong><small>Coinbase shared secret key</small></label>
					<div><input type="text" name="set[coinbase_secret]" value="<?=$config['coinbase_secret']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>CoinPayments</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Merchant ID</strong></label>
					<div><input type="text" name="set[cp_id]" value="<?=$config['cp_id']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>IPN Secret</strong></label>
					<div><input type="text" name="set[cp_secret]" value="<?=$config['cp_secret']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>API Public Key</strong></label>
					<div><input type="text" name="set[cp_public_key]" value="<?=$config['cp_public_key']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>API Private Key</strong></label>
					<div><input type="text" name="set[cp_private_key]" value="<?=$config['cp_private_key']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit" value="Submit" />
				</div>
			</div>
		</form>
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Payeer</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Merchant ID</strong></label>
					<div><input type="text" name="set[payeer_key]" value="<?=$config['payeer_key']?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Secret key</strong></label>
					<div><input type="text" name="set[payeer_secret]" value="<?=$config['payeer_secret']?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit" value="Submit" />
				</div>
			</div>
		</form>
	</div>
	<div class="grid_6">
		<div class="box">
			<div class="header">
				<h2>FaucetPay Instructions</h2>
			</div>
			<div class="content">
				<p>1) Login on your <a href="https://faucetpay.io/?r=2233" target="_blank">FaucetPay account</a>, go to <i>Faucet Dashboard</i> then add your Faucet.
				<p>2) After you create your Faucet add your <i>API Key</i> here.</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>Coinbase Withdraw Instructions</h2>
			</div>
			<div class="content">
				<p>1) Login on your <a href="https://www.coinbase.com/join/negrea_d" target="_blank">Coinbase account</a> and go to <i>Settings</i> -> <i>API</i></p>
				<p>2) Click on <i>New API key</i>, select all cryptocurrencies and all API permissions</p>
				<p>3) Copy your api key and secret key and complete fields here.</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>Coinbase Commerce Instructions</h2>
			</div>
			<div class="content">
				<p>1) Login on your <a href="https://commerce.coinbase.com/" target="_blank">Coinbase Commerce account</a> and go to <i>Settings</i></p>
				<p>2) Scroll to <i>API keys</i> and click on <i>Create an API Key</i>, then copy your new key and complete <i>API Key</i> field here.</p>
				<p>3) Scroll to <i>Webhook subscriptions</i>, click on <i>Show shared secret</i>, then copy your webhook secret key and complete <i>Secret Key</i> field here.</p>
				<p>4) Click on <i>Add an endpoint</i>, complete with following URL and save it.</p>
				<p><input type="text" value="<?=$config['secure_url']?>/system/libs/Coinbase/ipn.php" onclick="this.select()" style="width:100%" readonly /></p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>CoinPayments Instructions</h2>
			</div>
			<div class="content">
				<p>1) Login on your <a href="https://www.coinpayments.net/index.php?ref=5fdb20fb2dc0efc7d9a302e517c73dc4" target="_blank">Coinayments account</a> and go to <i>Account</i> -> <i>Account Settings</i></p>
				<p>2) Copy <i>Your Merchant ID</i> and complete <i>Merchant ID</i> field.</p>
				<p>3) Go to <i>Merchant Settings</i>, copy <i>IPN Secret</i> and complete <i>IPN Secret</i> field.</p>
				<p>4) Go to <i>Account</i> -> <i>API Keys</i> and click on <i>Generate New Key</i>.</p>
				<p>5) Click on <i>Edit Permissions</i> of your new generate keys and allow access to those permissions: create_transaction, get_callback_address, balances, create_withdrawal and Allow auto_confirm</p>
				<p>6) Copy required fields here with previous generated keys.</p>
			</div>
		</div>
		<div class="box">
			<div class="header">
				<h2>Payeer Instructions</h2>
			</div>
			<div class="content">
				<p>1) Login on your <a href="https://payeer.com/04641331" target="_blank">Payeer account</a>, go to <i>Dashboard</i> -> <i>Merchant Settings</i> then click on <i>Add Merchant</i>.<br /><strong style="color:red;">ATENTION:</strong> Please copy the <i>Secret key</i>, then paste it here into <i>Secret Key</i> field</p>
				<p>2) Complete first form with required info then confirm your website following provided instructions.</p>
				<p>3) Complete <i>Merchant Settings</i> using URL's from bellow then submit your website for approval.</p>
				<p>
					<ul>
						<li><b>Success URL</b><br /><input type="text" value="<?=GenerateURL('deposits', true)?>" onclick="this.select()" style="width:300px" readonly /></li><br />
						<li><b>Fail URL</b><br /><input type="text" value="<?=GenerateURL('deposits', true)?>" onclick="this.select()" style="width:300px" readonly /></li><br />
						<li><b>Status URL</b><br /><input type="text" value="<?=$config['secure_url']?>/system/libs/Payeer/ipn.php" onclick="this.select()" style="width:300px" readonly /></li>
					</ul>
				</p>
				<p> 4) Complete field <i>Merchant ID</i> with your Payeer Merchant ID for this website.</p>
			</div>
		</div>
	</div>
</section>