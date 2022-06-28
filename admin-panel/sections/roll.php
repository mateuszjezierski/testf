<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$message = '';
	if(isset($_GET['del']) && is_numeric($_GET['del'])){
		$del = $db->EscapeString($_GET['del']);
		$db->Query("DELETE FROM `bonus_roll` WHERE `id`='".$del."'");
	}elseif(isset($_GET['edit'])){
		$edit = $db->EscapeString($_GET['edit']);
		$pack = $db->QueryFetchArray("SELECT * FROM `bonus_roll` WHERE `id`='".$edit."'");
		if(isset($_POST['submit'])){
			$reward = $db->EscapeString($_POST['reward']);
			
			if($reward < 1){
				$message = '<div class="alert error"><span class="icon"></span><strong>ERROR:</strong> Reward can\'t be less than 1 Faucet Token!</div>';
			}else{
				$db->Query("UPDATE `bonus_roll` SET `reward`='".$reward."' WHERE `id`='".$edit."'");

				$message = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Reward was successfully edited!</div>';
			}
		}
	}
	
	$jackMsg = '';
	if(isset($_POST['edit_bonus_roll'])){
		$jackpot_prize = $db->EscapeString($_POST['jackpot_prize']);
		$required_sl = $db->EscapeString($_POST['required_sl']);
		
		if($jackpot_prize < 100){
			$jackMsg = '<div class="alert error"><span class="icon"></span><strong>ERROR:</strong> Jackpot can\'t be less than 100 Faucet Tokens!</div>';
		}else{
			$db->Query("UPDATE `site_config` SET `config_value`='".$jackpot_prize."' WHERE `config_name`='jackpot_prize'");
			$db->Query("UPDATE `site_config` SET `config_value`='".$required_sl."' WHERE `config_name`='bonus_roll_sl_required'");
			$config['jackpot_prize'] = $jackpot_prize;
			$config['bonus_roll_sl_required'] = $required_sl;

			$jackMsg = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Bonus Roll settings were successfully changed!</div>';
		}
	}

	// Get smalled possible number
	$small = 1;
	$checkSmall = $db->QueryFetchArray("SELECT `big` FROM `bonus_roll` ORDER BY `big` DESC LIMIT 1");
	if(!empty($checkSmall))
	{
		$small = $checkSmall['big'] + 1;
	}

	if(isset($_POST['add_chances'])){
		$big = $db->EscapeString($_POST['big']);
		$reward = $db->EscapeString($_POST['reward']);

		if($big > 99998){
			$message = '<div class="alert error"><span class="icon"></span><strong>ERROR:</strong> Your maximum number can\'t be higher than 99.998!</div>';
		}elseif($big < ($small+100)){
			$message = '<div class="alert error"><span class="icon"></span><strong>ERROR:</strong> Your maximum number can\'t be smaller than '.number_format($small+100).'!</div>';
		}elseif($reward < 1){
			$message = '<div class="alert error"><span class="icon"></span><strong>ERROR:</strong> Reward can\'t be less than 1 Faucet Token!</div>';
		}else{
			$db->Query("INSERT INTO `bonus_roll` (`small`,`big`,`reward`) VALUES('".$small."', '".$big."', '".$reward."')");

			$message = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Bonus Roll chances were successfully added!</div>';
		}
	}

	if(isset($_GET['edit']) && $pack['id'] != ''){
?>
<section id="content" class="container_12 clearfix"><?=$message?>
	<div class="grid_12">
		<form action="" method="post" class="box">
			<div class="header">
				<h2>Edit Bonus Roll Reward</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Small Number</strong><small>Cannot be changed</small></label>
					<div><input type="text" value="<?=$pack['small']?>" disabled /></div>
				</div>
				<div class="row">
					<label><strong>Big Number</strong><small>Cannot be changed</small></label>
					<div><input type="text" value="<?=$pack['big']?>" disabled /></div>
				</div>
				<div class="row">
					<label><strong>Reward</strong><small>Faucet Tokens</small></label>
					<div><input type="text" name="reward" value="<?=(isset($_POST['reward']) ? $_POST['reward'] : $pack['reward'])?>" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="submit" value="Submit" />
				</div>
			</div>
        </form>
	</div>
</section>
<?}else{?>
<section id="content" class="container_12 clearfix">
	<h1 class="grid_12">Bonus Roll Winning Chances</h1>
	<div class="grid_5">
		<form method="post" class="box">
			<div class="header">
				<h2>Bonus Roll Settings</h2>
			</div>
			<div class="content"><?=$jackMsg?>
				<div class="row">
					<label><strong>Jackpot Prize</strong><small>In Tokens</small></label>
					<div><input type="text" name="jackpot_prize" value="<?php echo $config['jackpot_prize']; ?>" placeholder="1000" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Required Shortlinks Visits</strong><small>0 = disabled. Works only if shortlinks views reset at midnight</small></small></label>
					<div><input type="text" name="required_sl" value="<?php echo $config['bonus_roll_sl_required']; ?>" placeholder="5" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="edit_bonus_roll" value="Submit" />
				</div>
			</div>
        </form>
	</div>
	<div class="grid_7">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th width="25">#</th>
						<th>Small Number</th>
						<th>Big Number</th>
						<th>Reward</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					  $packs = $db->QueryFetchArrayAll("SELECT * FROM `bonus_roll` ORDER BY `id` ASC");
					  foreach($packs as $pack){
					?>	
					<tr>
						<td><?=$pack['id']?></td>
						<td><?=number_format($pack['small'])?></td>
						<td><?=number_format($pack['big'])?></td>
						<td><?=number_format($pack['reward'], 2)?> Faucet Tokens</td>
						<td class="center">
							<a href="index.php?x=roll&edit=<?=$pack['id']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
							<a href="index.php?x=roll&del=<?=$pack['id']?>" onclick="return confirm('You sure you want to delete it?');" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
						</td>
					 </tr>
					<?php }?>
				</tbody>
			</table>
		</div>

		<form method="post" class="box">
			<div class="header">
				<h2>Add Bonus Roll Chances</h2>
			</div>
			<?php
				if($small > 99898) {
					echo '<div class="content"><div class="alert error">You can\'t add more bonus roll winning chances. If you want to add other bonus roll winning chances, you must remove existing ones first!</div></div>';
				} else {
			?>
			<div class="content"><?=$message?>
				<div class="row">
					<label><strong>Small Number</strong></label>
					<div><input type="text" value="<?php echo $small; ?>" disabled /></div>
				</div>
				<div class="row">
					<label><strong>Big Number</strong></label>
					<div><input type="text" name="big" placeholder="<?php echo ($small+100); ?>" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Reward</strong><small>Faucet Tokens</small></label>
					<div><input type="text" name="reward" placeholder="0" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="add_chances" value="Submit" />
				</div>
			</div>
		<?php } ?>
        </form>
	</div>
</section>
<?php }?>