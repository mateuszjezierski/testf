<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$message = '';
	$scratch_prizes = unserialize($config['scratch_prizes']);
	if(isset($_POST['submit'])){
		$posts = $db->EscapeString($_POST['set']);
		foreach ($posts as $key => $value){
			if($config[$key] != $value){
				$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
				$config[$key] = $db->EscapeString($value);
			}
		}
		
		if(isset($_POST['prize']) && count($_POST['prize']) == 6)
		{
			$prizes = serialize($_POST['prize']);
			$config['scratch_prizes'] = $prizes;
			$db->Query("UPDATE `site_config` SET `config_value`='".$prizes."' WHERE `config_name`='scratch_prizes'");
		}
		
		$message = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
	}
	
	$scratch_prizes = unserialize($config['scratch_prizes']);
?>
<section id="content" class="container_12 clearfix"><?=$message?>
	<h1 class="grid_12">Games Settings</h1>
	<div class="grid_6">
		<form method="post" class="box">
			<div class="header">
				<h2>Dice Game Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Minium Bet</strong><small>In Coins</small></label>
					<div><input type="text" name="set[dice_min_bet]" value="<?php echo $config['dice_min_bet']; ?>" placeholder="10" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Maximum Bet</strong><small>In Coins</small></label>
					<div><input type="text" name="set[dice_max_bet]" value="<?php echo $config['dice_max_bet']; ?>" placeholder="1000" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>House Edge</strong><small>Percentage</small></small></label>
					<div><input type="text" name="set[dice_house_edge]" value="<?php echo $config['dice_house_edge']; ?>" placeholder="5" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="submit" value="Submit" />
				</div>
			</div>
        </form>
		<form method="post" class="box">
			<div class="header">
				<h2>Coin Flip Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Minium Bet</strong><small>In Coins</small></label>
					<div><input type="text" name="set[coinflip_min_bet]" value="<?php echo $config['coinflip_min_bet']; ?>" placeholder="10" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Maximum Bet</strong><small>In Coins</small></label>
					<div><input type="text" name="set[coinflip_max_bet]" value="<?php echo $config['coinflip_max_bet']; ?>" placeholder="1000" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>House Edge</strong><small>Percentage</small></small></label>
					<div><input type="text" name="set[coinflip_edge]" value="<?php echo $config['coinflip_edge']; ?>" placeholder="5" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="submit" value="Submit" />
				</div>
			</div>
        </form>
	</div>
	<div class="grid_6">
		<form method="post" class="box">
			<div class="header">
				<h2>Scratch Tickets Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Ticket Price</strong><small>In Coins</small></label>
					<div><input type="text" name="set[scratch_price]" value="<?php echo $config['scratch_price']; ?>" placeholder="25" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Winning Chance</strong><small>Between 1 and 99</small></label>
					<div><input type="text" name="set[scratch_win_chance]" value="<?php echo $config['scratch_win_chance']; ?>" placeholder="50" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Prize 1</strong><small>Lowest prize in coins</small></label>
					<div><input type="text" name="prize[1]" value="<?php echo $scratch_prizes[1]; ?>" placeholder="25" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Prize 2</strong><small>In Coins</small></label>
					<div><input type="text" name="prize[2]" value="<?php echo $scratch_prizes[2]; ?>" placeholder="50" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Prize 3</strong><small>In Coins</small></label>
					<div><input type="text" name="prize[3]" value="<?php echo $scratch_prizes[3]; ?>" placeholder="100" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Prize 4</strong><small>In Coins</small></label>
					<div><input type="text" name="prize[4]" value="<?php echo $scratch_prizes[4]; ?>" placeholder="150" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Prize 5</strong><small>In Coins</small></label>
					<div><input type="text" name="prize[5]" value="<?php echo $scratch_prizes[5]; ?>" placeholder="200" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Prize 6</strong><small>Highest prize in coins (jackpot)</small></label>
					<div><input type="text" name="prize[6]" value="<?php echo $scratch_prizes[6]; ?>" placeholder="2000" required="required" /></div>
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