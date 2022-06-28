<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$message = '';
	if(isset($_POST['submit'])){
		$id = $db->EscapeString($_POST['membership_id']);
		$membership = $db->EscapeString($_POST['membership']);
		$multiplier = $db->EscapeString($_POST['multiplier']);
		$ref_com = $db->EscapeString($_POST['ref_com']);
		$direct_crypto_manual = $db->EscapeString($_POST['direct_crypto_manual']);
		$direct_crypto_auto = $db->EscapeString($_POST['direct_crypto_auto']);
		$withdraw_wait_time = $db->EscapeString($_POST['withdraw_wait_time']);
		$bonus_roll = $db->EscapeString($_POST['bonus_roll']);
		$hash_rate = $db->EscapeString($_POST['hash_rate']);
		$hide_ads = $db->EscapeString($_POST['hide_ads']);
		$price = $db->EscapeString(isset($_POST['price']) ? $_POST['price'] : 0);

		$db->Query("UPDATE `memberships` SET `membership`='".$membership."', `multiplier`='".$multiplier."', `bonus_roll`='".$bonus_roll."', `ref_com`='".$ref_com."', `direct_crypto_auto`='".$direct_crypto_auto."', `withdraw_wait_time`='".$withdraw_wait_time."', `hash_rate`='".$hash_rate."', `hide_ads`='".$hide_ads."', `direct_crypto_manual`='".$direct_crypto_manual."', `price`='".$price."' WHERE `id`='".$id."'");

		$message = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
	}

	$memberships = $db->QueryFetchArrayAll("SELECT * FROM `memberships` ORDER BY `id` ASC LIMIT 4");
?>
<section id="content" class="container_12"><?=$message?>
	<div class="grid_3">
		<form action="" method="post" class="box">
			<input type="hidden" name="membership_id" value="1" />
			<div class="header">
				<h2>Basic Membership</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Membership</strong></label>
					<div><input type="text" name="membership" value="<?=$memberships[0]['membership']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Faucet Multiplier</strong></label>
					<div><input type="text" name="multiplier" value="<?=$memberships[0]['multiplier']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Referral Commission</strong><small>Percentage</small></label>
					<div><input type="text" name="ref_com" value="<?=$memberships[0]['ref_com']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Manual Faucet Crypto Claim</strong></label>
					<div><select name="direct_crypto_manual"><option value="0">Disabled</option><option value="1"<?=($memberships[0]['direct_crypto_manual'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Auto Faucet Crypto Claim</strong></label>
					<div><select name="direct_crypto_auto"><option value="0">Disabled</option><option value="1"<?=($memberships[0]['direct_crypto_auto'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Coins Withdraw Time</strong><small>In Days (0 = instant)</small></label>
					<div><input type="text" name="withdraw_wait_time" value="<?=$memberships[0]['withdraw_wait_time']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>CPU Mining Hash Rate</strong></label>
					<div><input type="text" name="hash_rate" value="<?=$memberships[0]['hash_rate']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Bonus Roll Time</strong><small>In Minutes</small></label>
					<div><input type="text" name="bonus_roll" value="<?=$memberships[0]['bonus_roll']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Hide Popup Ads</strong></label>
					<div><select name="hide_ads"><option value="0">Disabled</option><option value="1"<?=($memberships[0]['hide_ads'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Monthly Price</strong></label>
					<div><input type="text" name="price" value="<?=$memberships[0]['price']?>" disabled /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
	</div>
	<div class="grid_3">
		<form action="" method="post" class="box">
			<input type="hidden" name="membership_id" value="2" />
			<div class="header">
				<h2>Membership Pack 1</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Membership</strong></label>
					<div><input type="text" name="membership" value="<?=$memberships[1]['membership']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Faucet Multiplier</strong></label>
					<div><input type="text" name="multiplier" value="<?=$memberships[1]['multiplier']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Referral Commission</strong><small>Percentage</small></label>
					<div><input type="text" name="ref_com" value="<?=$memberships[1]['ref_com']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Manual Faucet Crypto Claim</strong></label>
					<div><select name="direct_crypto_manual"><option value="0">Disabled</option><option value="1"<?=($memberships[1]['direct_crypto_manual'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Auto Faucet Crypto Claim</strong></label>
					<div><select name="direct_crypto_auto"><option value="0">Disabled</option><option value="1"<?=($memberships[1]['direct_crypto_auto'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Coins Withdraw Time</strong><small>In Days (0 = instant)</small></label>
					<div><input type="text" name="withdraw_wait_time" value="<?=$memberships[1]['withdraw_wait_time']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>CPU Mining Hash Rate</strong></label>
					<div><input type="text" name="hash_rate" value="<?=$memberships[1]['hash_rate']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Bonus Roll Time</strong><small>In Minutes</small></label>
					<div><input type="text" name="bonus_roll" value="<?=$memberships[1]['bonus_roll']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Hide Popup Ads</strong></label>
					<div><select name="hide_ads"><option value="0">Disabled</option><option value="1"<?=($memberships[1]['hide_ads'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Monthly Price</strong></label>
					<div><input type="text" name="price" value="<?=$memberships[1]['price']?>" required /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
	</div>
	<div class="grid_3">
		<form action="" method="post" class="box">
			<input type="hidden" name="membership_id" value="3" />
			<div class="header">
				<h2>Membership Pack 2</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Membership</strong></label>
					<div><input type="text" name="membership" value="<?=$memberships[2]['membership']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Faucet Multiplier</strong></label>
					<div><input type="text" name="multiplier" value="<?=$memberships[2]['multiplier']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Referral Commission</strong><small>Percentage</small></label>
					<div><input type="text" name="ref_com" value="<?=$memberships[2]['ref_com']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Manual Faucet Crypto Claim</strong></label>
					<div><select name="direct_crypto_manual"><option value="0">Disabled</option><option value="1"<?=($memberships[2]['direct_crypto_manual'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Auto Faucet Crypto Claim</strong></label>
					<div><select name="direct_crypto_auto"><option value="0">Disabled</option><option value="1"<?=($memberships[2]['direct_crypto_auto'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Coins Withdraw Time</strong><small>In Days (0 = instant)</small></label>
					<div><input type="text" name="withdraw_wait_time" value="<?=$memberships[2]['withdraw_wait_time']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>CPU Mining Hash Rate</strong></label>
					<div><input type="text" name="hash_rate" value="<?=$memberships[2]['hash_rate']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Bonus Roll Time</strong><small>In Minutes</small></label>
					<div><input type="text" name="bonus_roll" value="<?=$memberships[2]['bonus_roll']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Hide Popup Ads</strong></label>
					<div><select name="hide_ads"><option value="0">Disabled</option><option value="1"<?=($memberships[2]['hide_ads'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Monthly Price</strong></label>
					<div><input type="text" name="price" value="<?=$memberships[2]['price']?>" required /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
	</div>
	<div class="grid_3">
		<form action="" method="post" class="box">
			<input type="hidden" name="membership_id" value="4" />
			<div class="header">
				<h2>Membership Pack 3</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Membership</strong></label>
					<div><input type="text" name="membership" value="<?=$memberships[3]['membership']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Faucet Multiplier</strong></label>
					<div><input type="text" name="multiplier" value="<?=$memberships[3]['multiplier']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Referral Commission</strong><small>Percentage</small></label>
					<div><input type="text" name="ref_com" value="<?=$memberships[3]['ref_com']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Manual Faucet Crypto Claim</strong></label>
					<div><select name="direct_crypto_manual"><option value="0">Disabled</option><option value="1"<?=($memberships[3]['direct_crypto_manual'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Auto Faucet Crypto Claim</strong></label>
					<div><select name="direct_crypto_auto"><option value="0">Disabled</option><option value="1"<?=($memberships[3]['direct_crypto_auto'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Coins Withdraw Time</strong><small>In Days (0 = instant)</small></label>
					<div><input type="text" name="withdraw_wait_time" value="<?=$memberships[3]['withdraw_wait_time']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>CPU Mining Hash Rate</strong></label>
					<div><input type="text" name="hash_rate" value="<?=$memberships[3]['hash_rate']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Bonus Roll Time</strong><small>In Minutes</small></label>
					<div><input type="text" name="bonus_roll" value="<?=$memberships[3]['bonus_roll']?>" required /></div>
				</div>
				<div class="row">
					<label><strong>Hide Popup Ads</strong></label>
					<div><select name="hide_ads"><option value="0">Disabled</option><option value="1"<?=($memberships[3]['hide_ads'] == 1 ? ' selected' : '')?>>Enabled</option></select></div>
				</div>
				<div class="row">
					<label><strong>Monthly Price</strong></label>
					<div><input type="text" name="price" value="<?=$memberships[3]['price']?>" required /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</form>
	</div>
</section>