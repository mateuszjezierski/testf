<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$fp_active = (empty($config['fp_api_key']) ? false : true);
	$cb_active = (empty($config['coinbase_withdraw_api']) ? false : true);

	// 2FA authenticator
	$ga = new GoogleAuthenticator();
	if(empty($data['auth_key']))
	{
		$data['auth_key'] = $ga->createSecret();
		$db->Query("UPDATE `users` SET `auth_key`='".$data['auth_key']."' WHERE `id`='".$data['id']."'");
	}

	$errMessage = '';
	if(isset($_POST['change_pass']))
	{
		if (securePassword($_POST['old_password']) != $data['password']) {
			$errMessage = '<div class="alert alert-danger" role="alert">'.$lang['l_50'].'</div>';
		}elseif(!checkPwd($_POST['password'],$_POST['password2'])) {
			$errMessage = '<div class="alert alert-danger" role="alert">'.$lang['l_51'].'</div>';
		}else{
			$enpass = securePassword($_POST['password']);
			$db->Query("UPDATE `users` SET `password`='".$enpass."' WHERE `id`='".$data['id']."'");
			$errMessage = '<div class="alert alert-success" role="alert">'.$lang['l_52'].'</div>';
		}
	}

	if(isset($_POST['change_withdraw']))
	{
		if (securePassword($_POST['old_password']) != $data['password']) {
			$errMessage = '<div class="alert alert-danger" role="alert">'.$lang['l_50'].'</div>';
		}elseif(empty($_POST['faucetpay']) && empty($_POST['coinbase'])) {
			$errMessage = '<div class="alert alert-danger" role="alert">Please complete your FaucetPay Email and / or ExpressCrypto ID!</div>';
		}else{
			$fp_email = $db->EscapeString($_POST['faucetpay']);
			$coinbase = $db->EscapeString($_POST['coinbase']);
			$fp_status = true;

			if(!empty($fp_email))
			{
				$faucetpay = new FaucetPay($config['fp_api_key']);
				$fp_result = $faucetpay->checkAddress($fp_email);
				
				if($fp_result['status'] != 200){
					$fp_status = false;
				}
			}

			if($fp_status == false)
			{
				$errMessage = '<div class="alert alert-danger" role="alert">This Email Address is not associated with any FaucetPay account!</div>';
			}
			elseif(!empty($coinbase) && !isEmail($coinbase))
			{
				$errMessage = '<div class="alert alert-danger" role="alert">Please complete a valid Coinbase Email Address.</div>';
			}
			else
			{
				if(!empty($fp_email) && $fp_status)
				{
					$db->Query("UPDATE `users` SET `fp_email`='".$fp_email."', `fp_hash`='".$fp_result['payout_user_hash']."' WHERE `id`='".$data['id']."'");
					$data['fp_email'] = $fp_email;
				}

				if(!empty($coinbase) && isEmail($coinbase))
				{
					$db->Query("UPDATE `users` SET `cb_email`='".$coinbase."' WHERE `id`='".$data['id']."'");
					$data['cb_email'] = $coinbase;
				}
				
				$errMessage = '<div class="alert alert-success" role="alert">Your withdrawal settings were successfully saved!</div>';
			}
		}
	}

	if(isset($_POST['set_authenticator']))
	{
		$new_status = ($data['auth_status'] == 0 ? 1 : 0);
		$checkResult = $ga->verifyCode($data['auth_key'], $_POST['ga_pin'], 2);
		
		if(!$checkResult ) {
			$errMessage = '<div class="alert alert-danger" role="alert">'.$lang['l_505'].'</div>';
		}else{
			$new_key = ($new_status == 0 ? $ga->createSecret() : $data['auth_key']);

			$db->Query("UPDATE `users` SET `auth_status`='".$new_status."', `auth_key`='".$new_key."' WHERE `id`='".$data['id']."'");
			$data['auth_status'] = $new_status;
			$errMessage = '<div class="alert alert-success" role="alert">'.($new_status == 1 ? $lang['l_506'] : $lang['l_507']).'</div>';
		}
	}
	
	if(isset($_POST['change_email']))
	{
		$email = $db->EscapeString($_POST['email']);
		$password = $db->EscapeString($_POST['password']);

		if (securePassword($_POST['password']) != $data['password']) {
			$errMessage = '<div class="alert alert-danger" role="alert">'.$lang['l_53'].'</div>';
		}elseif(!isEmail($email)) {
			$errMessage = '<div class="alert alert-danger" role="alert">'.$lang['l_54'].'</div>';
		}elseif($db->QueryGetNumRows("SELECT id FROM `users` WHERE `email`='".$email."' LIMIT 1") > 0 && $data['email'] != $email){
			$errMessage = '<div class="alert alert-danger" role="alert">'.$lang['l_55'].'</div>';
		}else{
			$db->Query("UPDATE `users` SET `email`='".$email."' WHERE `id`='".$data['id']."'");
			$errMessage = '<div class="alert alert-success" role="alert">'.$lang['l_56'].'</div>';
		}
	}

    if(isset($_POST['del_acc'])){
        $pass = securePassword($_POST['password']);
        if($db->QueryGetNumRows("SELECT id FROM `users` WHERE `id`='".$data['id']."' AND `password`='".$pass."'") == 0){
            $errMessage = '<div class="alert alert-danger" role="alert">'.$lang['l_49'].'</div>';
        }else{
            $db->Query("INSERT INTO `users_deleted` (`id`,`email`,`login`,`pass`,`sex`,`country_id`,`time`) values('".$data['id']."','".$data['email']."','".$data['username']."','".$data['password']."','".$data['gender']."','".$data['country_id']."',NOW())");
            $db->Query("DELETE FROM `users` WHERE `id` = '".$data['id']."' AND `password`='".$pass."'");
            if(isset($_COOKIE['SesToken'])){
                setcookie('SesToken', '0', time()-604800);
            }
            session_destroy();
            redirect($config['secure_url']);
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
				<?=$errMessage?>
				<div id="grey-box" class="mt-2">
					<div class="title">
						2-Factor Authentication
					</div>
					<div class="content">
						<form method="post">
							<div class="col-md-8 col-12 offset-md-2 offset-0 form-group">
							  <label for="ga_pin">Google Authenticator PIN</label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-key"></i></div></div>
								<input type="text" class="form-control" id="ga_pin" name="ga_pin" placeholder="123456">
								<input type="submit" class="btn btn-<?php echo ($data['auth_status'] == 0 ? 'success' : 'danger'); ?> d-inline" name="set_authenticator" value="<?php echo ($data['auth_status'] == 0 ? $lang['l_508'] : $lang['l_509']); ?>" />
							  </div>
						    </div>
							<div class="col-md-8 col-12 offset-md-2 offset-0 text-center">
								<?php
									$qrCodeUrl = $ga->getQRCodeGoogleUrl($data['username'].' @ '.$config['site_logo'], $data['auth_key'], null, array('width' => 120, 'height' => 120));

									if($data['auth_status'] == 1) {
										echo '<div class="alert alert-success" role="alert">'.$lang['l_510'].'</div>';
									}
									else
									{
										echo '<div class="infobox"><h5>QR Code</h5><img src="'.$qrCodeUrl.'" alt="'.$data['auth_key'].'" /><br /><small>'.$lang['l_511'].'</small></div>';
									}
								?>
							</div>
						</form>
					</div>
				</div>
				<div id="grey-box" class="mt-2">
					<div class="title">
						Withdrawal Settings
					</div>
					<div class="content">
						<form method="post">
						  <div class="form-row">
							<div class="form-group col-md-6">
							  <label for="faucetpay">FaucetPay Email</label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-envelope"></i></div></div>
								<input type="email" class="form-control" id="faucetpay" name="faucetpay" placeholder="FaucetPay Email Address" value="<?=($fp_active ? $data['fp_email'] : 'Unavailable')?>" autocomplete="off"<?=($fp_active ? '' : ' disabled')?>>
							  </div>
							</div>
							<div class="form-group col-md-6">
							  <label for="coinbase">Coinbase Email</label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-envelope"></i></div></div>
								<input type="email" class="form-control" id="coinbase" name="coinbase" placeholder="Coinbase Email Address" value="<?=($cb_active ? $data['cb_email'] : 'Unavailable')?>" autocomplete="off"<?=($cb_active ? '' : ' disabled')?>>
							  </div>
							</div>
						  </div>
						  <div class="form-row">
							<div class="form-group col-md-6">
							  <label for="old_password"><?=$lang['l_61']?></label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-key"></i></div></div>
								<input type="password" class="form-control" id="old_password" name="old_password" placeholder="*******">
								<input type="submit" class="btn btn-primary d-inline" name="change_withdraw" value="Save" />
							  </div>
							</div>
						  </div>
						</form>
					</div>
				</div>
				<div id="grey-box" class="mt-2">
					<div class="title">
						<?=$lang['l_48']?>
					</div>
					<div class="content">
						<form method="post">
						  <div class="form-row">
							<div class="form-group col-md-6">
							  <label for="email"><?=$lang['l_46']?></label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-envelope"></i></div></div>
								<input type="text" class="form-control" id="email" name="email" placeholder="<?=$data['email']?>">
							  </div>
							</div>
							<div class="form-group col-md-6">
							  <label for="password"><?=$lang['l_47']?></label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-key"></i></div></div>
								<input type="password" class="form-control" id="password" name="password" placeholder="******">
								<input type="submit" class="btn btn-primary d-inline" name="change_email" value="<?=$lang['l_48']?>" />
							  </div>
						    </div>
						  </div>
						</form>
					</div>
				</div>
				<div id="grey-box" class="mt-2">
					<div class="title">
						<?=$lang['l_60']?>
					</div>
					<div class="content">
						<form method="post">
						  <div class="form-row">
							<div class="form-group col-md-6">
							  <label for="password"><?=$lang['l_62']?></label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-key"></i></div></div>
								<input type="password" class="form-control" id="password" name="password" placeholder="Shd67SHB">
							  </div>
							</div>
							<div class="form-group col-md-6">
							  <label for="password2"><?=$lang['l_63']?></label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-key"></i></div></div>
								<input type="password" class="form-control" id="password2" name="password2" placeholder="Shd67SHB">
							  </div>
						    </div>
						  </div>
						  <div class="form-row">
							<div class="form-group col-md-6">
							  <label for="old_password"><?=$lang['l_61']?></label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-key"></i></div></div>
								<input type="password" class="form-control" id="old_password" name="old_password" placeholder="*******">
								<input type="submit" class="btn btn-primary d-inline" name="change_pass" value="<?=$lang['l_60']?>" />
							  </div>
							</div>
						  </div>
						</form>
					</div>
				</div>
				<div id="grey-box" class="mt-2">
					<div class="title">
						<?=$lang['l_64']?>
					</div>
					<div class="content">
						<form method="post">
							<div class="form-group col-md-6">
							  <label for="password"><?=$lang['l_47']?></label>
							  <div class="input-group mb-2 mr-sm-2">
								<div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-key"></i></div></div>
								<input type="password" class="form-control" id="password" name="password" placeholder="Shd67SHB">
								<input type="submit" class="btn btn-danger d-inline" name="del_acc" value="<?=$lang['l_64']?>" onclick="return confirm('<?=$lang['l_65']?>');" />
							  </div>
						    </div>
						  </div>
						</form>
					</div>
				</div>
			</div>
	  </div>
    </main>