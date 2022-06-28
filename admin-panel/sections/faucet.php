<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$message = '';
	if(isset($_GET['delete']) && is_numeric($_GET['delete']))
	{
		$frequencies = unserialize($config['auto_faucet_frequency']);
		unset($frequencies[$_GET['delete']]);
		$frequencies = serialize($frequencies);
		$config['auto_faucet_frequency'] = $frequencies;
		
		$db->Query("UPDATE `site_config` SET `config_value`='".$frequencies."' WHERE `config_name`='auto_faucet_frequency'");
			
		$message = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Frequency successfully removed</div>';
	}
	
	if(isset($_POST['submit'])){
		$posts = (isset($_POST['set']) ? $db->EscapeString($_POST['set']) : null);
		foreach ($posts as $key => $value){
			if($config[$key] != $value){
				$db->Query("UPDATE `site_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
				$config[$key] = $db->EscapeString($value);
			}
		}
		
		if(isset($_POST['frequency']))
		{
			$frequencies = serialize($_POST['frequency']);
			$config['auto_faucet_frequency'] = $frequencies;
			$db->Query("UPDATE `site_config` SET `config_value`='".$frequencies."' WHERE `config_name`='auto_faucet_frequency'");
		}
		
		$message = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Settings successfully changed</div>';
	}
	
	if(isset($_POST['add_frequency'])){
		if(empty($_POST['time']) || !is_numeric($_POST['time']))
		{
			$message = '<div class="alert danger"><span class="icon"></span><strong>ERROR:</strong> Please complete frequency time.</div>';
		}
		else
		{
			$bonus = (isset($_POST['bonus']) && is_numeric($_POST['bonus']) ? $_POST['bonus'] : 0);
			$frequencies = unserialize($config['auto_faucet_frequency']);
			$frequencies[] = array(0 => $bonus, 1 => $_POST['time']);
			$frequencies = serialize($frequencies);
			$config['auto_faucet_frequency'] = $frequencies;
			
			$db->Query("UPDATE `site_config` SET `config_value`='".$frequencies."' WHERE `config_name`='auto_faucet_frequency'");
			
			$message = '<div class="alert success"><span class="icon"></span><strong>Success!</strong> Frequency successfully added</div>';
		}
	}
?>
<section id="content" class="container_12 clearfix"><?=$message?>
	<h1 class="grid_12">Faucet Settings</h1>
	<div class="grid_6">
		<form method="post" class="box">
			<div class="header">
				<h2>Auto Faucet Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Faucet Price</strong><small>How many tokens does 1 coin worth, earned every minute</small></label>
					<div><input type="text" name="set[auto_faucet_price]" value="<?php echo $config['auto_faucet_price']; ?>" placeholder="15" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Coins Payout Bonus</strong><small>Bonus percentage received for Coins payout</small></label>
					<div><input type="text" name="set[auto_faucet_cp_bonus]" value="<?php echo $config['auto_faucet_cp_bonus']; ?>" placeholder="15" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Max. Currencies Claim</strong><small>How many cryptocurrencies can be claimed at once</small></small></label>
					<div><input type="text" name="set[auto_faucet_max]" value="<?php echo $config['auto_faucet_max']; ?>" placeholder="10" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Max. Boost</strong><small>What is the maximum boost allowed</small></label>
					<div><input type="text" name="set[auto_faucet_boost]" value="<?php echo $config['auto_faucet_boost']; ?>" placeholder="15" required="required" /></div>
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
				<h2>Manual Faucet Settings</h2>
			</div>
			<div class="content">
				<div class="row">
					<label><strong>Faucet Price</strong><small>How many tokens does 1 coin worth</small></label>
					<div><input type="text" name="set[manual_faucet_price]" value="<?php echo $config['manual_faucet_price']; ?>" placeholder="25" required="required" /></div>
				</div>
				<div class="row">
					<label><strong>Max. Claim</strong><small>How many tokens can be claimed at once</small></small></label>
					<div><input type="text" name="set[manual_faucet_max]" value="<?php echo $config['manual_faucet_max']; ?>" placeholder="1000" required="required" /></div>
				</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="submit" value="Submit" />
				</div>
			</div>
        </form>
	</div>
	<h1 class="grid_12">Auto Faucet Frequencies</h1>
	<div class="grid_6">
		<form method="post" class="box">
			<div class="header">
				<h2>Frequencies</h2>
			</div>
			<div class="content">
				<?php
					$frequencies = unserialize($config['auto_faucet_frequency']);
					asort($frequencies);
					foreach($frequencies as $key => $frequency)
					{
						echo '<div class="row">
								<label>
									<strong>'.$frequency[1].' Minutes Bonus</strong>
									<small><a href="index.php?x=faucet&delete='.$key.'" style="color:red" onclick="return confirm(\'You sure you want to delete this frequency?\');">Remove</a></small>
								</label>
								<div>
									<input type="text" name="frequency['.$key.'][0]" value="'.$frequency[0].'" placeholder="0" required="required" />
									<input type="hidden" name="frequency['.$key.'][1]" value="'.$frequency[1].'" />
								</div>
							</div>';
					}
				?>
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
				<h2>Add Frequency</h2>
			</div>
			<div class="content">
				<div class="content">
				<div class="row">
					<label><strong>Time</strong></label>
					<div><input type="text" name="time" value="<?=(isset($_POST['time']) ? $_POST['time'] : '')?>" placeholder='10' required="required" /></div>
				</div>		
				<div class="row">
					<label><strong>Bonus</strong></label>
					<div><input type="text" name="bonus" value="<?=(isset($_POST['bonus']) ? $_POST['bonus'] : '')?>" placeholder='3' required="required" /></div>
				</div>									
			</div>
			</div>
			<div class="actions">
				<div class="right">
					<input type="submit" name="add_frequency" value="Add" />
				</div>
			</div>
		</form>
	</div>
</section>