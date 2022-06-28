<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$message = '';
	if(isset($_POST['add_coin']))
	{
		$coin = $db->EscapeString($_POST['coin']);
		$name = $db->EscapeString($_POST['name']);
		$stock = $db->EscapeString($_POST['stock']);
		$symbol = $db->EscapeString($_POST['symbol']);
		$icon_class = $db->EscapeString($_POST['icon_class']);
		$coingecko_id = $db->EscapeString($_POST['coingecko_id']);
		$faucetpay = $db->EscapeString($_POST['faucetpay']);
		$coinbase = $db->EscapeString($_POST['coinbase']);
		$status = $db->EscapeString($_POST['status']);
	
		if(!empty($coin) && !empty($name) && !empty($stock) && !empty($symbol) && !empty($icon_class) && !empty($coingecko_id)){
			$db->Query("INSERT IGNORE INTO `coins`(`coin`,`name`,`stock`,`symbol`,`icon_class`,`coingecko_id`,`faucetpay`,`coinbase`,`status`) VALUES ('".$coin."', '".$name."', '".$stock."', '".$symbol."', '".$icon_class."', '".$coingecko_id."', '".$faucetpay."', '".$coinbase."', '".$status."')");
			$message = '<div class="alert success"><span class="icon"></span><strong>SUCCESS:</strong> Your cryptocurrency was successfully added!</div>';
		}else{
			$message = '<div class="alert error"><span class="icon"></span><strong>ERROR:</strong> You have to complete all fields!</div>';
		}
	}
	
	if(isset($_GET['edit']))
	{
		$id = $db->EscapeString($_GET['edit']);
		$edit = $db->QueryFetchArray("SELECT * FROM `coins` WHERE `coin`='".$id."'");
		
		if(!empty($edit['coin']))
		{
			if(isset($_POST['edit_coin']))
			{
				$coin = $db->EscapeString($_POST['coin']);
				$name = $db->EscapeString($_POST['name']);
				$stock = $db->EscapeString($_POST['stock']);
				$symbol = $db->EscapeString($_POST['symbol']);
				$icon_class = $db->EscapeString($_POST['icon_class']);
				$coingecko_id = $db->EscapeString($_POST['coingecko_id']);
				$faucetpay = $db->EscapeString($_POST['faucetpay']);
				$coinbase = $db->EscapeString($_POST['coinbase']);
				$status = $db->EscapeString($_POST['status']);
			
				if(!empty($coin) && !empty($name) && !empty($stock) && !empty($symbol) && !empty($icon_class) && !empty($coingecko_id)){
					$db->Query("UPDATE `coins` SET `coin`='".$coin."', `name`='".$name."', `stock`='".$stock."', `symbol`='".$symbol."', `icon_class`='".$icon_class."', `coingecko_id`='".$coingecko_id."', `faucetpay`='".$faucetpay."', `coinbase`='".$coinbase."', `status`='".$status."' WHERE `coin`='".$edit['coin']."'");

					$message = '<div class="alert success"><span class="icon"></span><strong>SUCCESS:</strong> Your cryptocurrency was successfully updated!</div>';
				}else{
					$message = '<div class="alert error"><span class="icon"></span><strong>ERROR:</strong> You have to complete all fields!</div>';
				}
			}
		}
	}
	
	if(isset($_GET['del'])){
		$del = $db->EscapeString($_GET['del']);
		$db->Query("DELETE FROM `coins` WHERE `coin`='".$del."'");
	}
?>
<section id="content" class="container_12 clearfix">
	<h1 class="grid_12">Crypto Currencies</h1>
	<div class="grid_8">
		<div class="box">
			<table class="styled">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Stock</th>
						<th>Symbol</th>
						<th>Icon</th>
						<th>FaucetPay</th>
						<th>Coinbase</th>
						<th>Current Value</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$coins = $db->QueryFetchArrayAll("SELECT * FROM `coins`");

						if(count($coins) == 0) {
							echo '<tr><td colspan="8" style="text-align: center">Nothing here yet!</td></tr>';
						}

						foreach($coins as $coin){
					?>	
					<tr>
						<td><?=$coin['coin']?></td>
						<td><b><?=($coin['status'] == 1 ? '<font color="green">'.$coin['name'].'</font>' : '<font color="red">'.$coin['name'].'</font>')?></b></td>
						<td style="text-align:center"><?=$coin['stock']?></td>
						<td style="text-align:center"><?=$coin['symbol']?></td>
						<td style="text-align:center"><i class="<?=$coin['icon_class']?>"></i></td>
						<td style="text-align:center"><?=($coin['faucetpay'] == 1 ? '<font color="green">Enabled</font>' : '<font color="red">Disabled</font>')?></td>
						<td style="text-align:center"><?=($coin['coinbase'] == 1 ? '<font color="green">Enabled</font>' : '<font color="red">Disabled</font>')?></td>
						<td><b><?=(empty($coin_value[$coin['coin']]) ? 'Unknown' : '$'.$coin_value[$coin['coin']])?></b></td>
						<td class="center">
							<a href="index.php?x=coins&edit=<?=$coin['coin']?>" class="button small grey tooltip" data-gravity=s title="Edit"><i class="icon-pencil"></i></a>
							<a href="index.php?x=coins&del=<?=$coin['coin']?>" onclick="return confirm('Are you sure you want to delete this currency?');" class="button small grey tooltip" data-gravity=s title="Remove"><i class="icon-remove"></i></a>
						</td>
					</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="grid_4"><?=$message?>
		<form action="" method="post" class="box">
		<div class="header">
			<h2><?=(isset($_GET['edit']) && !empty($edit['coin']) ? 'Edit Currency' : 'Add Currency')?></h2>
		</div>
		<div class="content">
			<div class="row">
				<label><strong>Coin ID</strong><small>Lowercase stock name</small></label>
				<div><input type="text" name="coin" placeholder="btc" value="<?=(isset($_GET['edit']) && !empty($edit['coin']) ? (isset($_POST['coin']) ? $_POST['coin'] : $edit['coin']) : '')?>" required="required" /></div>
			</div>
			<div class="row">
				<label><strong>Name</strong></label>
				<div><input type="text" name="name" placeholder="Bitcoin" value="<?=(isset($_GET['edit']) && !empty($edit['coin']) ? (isset($_POST['name']) ? $_POST['name'] : $edit['name']) : '')?>" required="required" /></div>
			</div>
			<div class="row">
				<label><strong>Stock Name</strong></label>
				<div><input type="text" name="stock" placeholder="BTC" value="<?=(isset($_GET['edit']) && !empty($edit['coin']) ? (isset($_POST['stock']) ? $_POST['stock'] : $edit['stock']) : '')?>" required="required" /></div>
			</div>
			<div class="row">
				<label><strong>Symbol</strong></label>
				<div><input type="text" name="symbol" placeholder="BTC" value="<?=(isset($_GET['edit']) && !empty($edit['coin']) ? (isset($_POST['symbol']) ? $_POST['sybol'] : $edit['symbol']) : '')?>" required="required" /></div>
			</div>
			<div class="row">
				<label><strong>FontAwesome Icon Class</strong><small><a href="https://fontawesome.com/" target="_blank">Click here</a> for full list</small></label>
				<div><input type="text" name="icon_class" placeholder="fas fa-coins" value="<?=(isset($_GET['edit']) && !empty($edit['coin']) ? (isset($_POST['icon_class']) ? $_POST['icon_class'] : $edit['icon_class']) : '')?>" required="required" /></div>
			</div>
			<div class="row">
				<label><strong>Coingecko ID</strong><small><a href="https://docs.google.com/spreadsheets/d/1wTTuxXt8n9q7C4NDXqQpI3wpKu1_5bGVmP9Xz0XGSyU/edit#gid=0" target="_blank">Click here</a> for full list</small></label>
				<div><input type="text" name="coingecko_id" placeholder="bitcoin" value="<?=(isset($_GET['edit']) && !empty($edit['coin']) ? (isset($_POST['coingecko_id']) ? $_POST['coingecko_id'] : $edit['coingecko_id']) : '')?>" required="required" /></div>
			</div>
			<div class="row">
				<label><strong>FaucetPay</strong></label>
				<div><select name="faucetpay"><option value="0">Disabled</option><option value="1"<?=(isset($_GET['edit']) && !empty($edit['coin']) ? (isset($_POST['faucetpay']) ? ($_POST['faucetpay'] == 1 ? ' selected' : '') : ($edit['faucetpay'] == 1 ? ' selected' : '')) : '')?>>Enabled</option></select></div>
			</div>
			<div class="row">
				<label><strong>Coinbase</strong></label>
				<div><select name="coinbase"><option value="0">Disabled</option><option value="1"<?=(isset($_GET['edit']) && !empty($edit['coin']) ? (isset($_POST['coinbase']) ? ($_POST['coinbase'] == 1 ? ' selected' : '') : ($edit['coinbase'] == 1 ? ' selected' : '')) : '')?>>Enabled</option></select></div>
			</div>
			<div class="row">
				<label><strong>Status</strong></label>
				<div><select name="status"><option value="0">Disabled</option><option value="1"<?=(isset($_GET['edit']) && !empty($edit['coin']) ? (isset($_POST['status']) ? ($_POST['status'] == 1 ? ' selected' : '') : ($edit['status'] == 1 ? ' selected' : '')) : ' selected')?>>Enabled</option></select></div>
			</div>
		</div>
		<div class="actions">
			<div class="right">
				<input type="submit" value="<?=(isset($_GET['edit']) && !empty($edit['coin']) ? 'Edit Currency' : 'Add Currency')?>" name="<?=(isset($_GET['edit']) && !empty($edit['coin']) ? 'edit_coin' : 'add_coin')?>" />
			</div>
		</div>
	</form>
	</div>
</section>