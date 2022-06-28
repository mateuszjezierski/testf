<?php
/** Update database **/
$add_config = array();

// Update to 1.0.2
$add_config[] = "('coinbase_withdraw_api', ''),('coinbase_withdraw_secret', ''),('auto_faucet_cp_bonus', '2'),('auto_faucet_boost', '5')";
if($db->Query("SELECT `expresscrypto` FROM `coins`")){
	$db->Query("ALTER TABLE `coins` DROP `expresscrypto`");
}
if(!$db->Query("SELECT `coinbase` FROM `coins`")){
	$db->Query("ALTER TABLE `coins` ADD `coinbase` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `faucetpay`");
}
if($db->Query("SELECT `ec_id` FROM `users`")){
	$db->Query("ALTER TABLE `users` DROP `ec_id`");
}
if(!$db->Query("SELECT `cb_email` FROM `users`")){
	$db->Query("ALTER TABLE `users` ADD `cb_email` VARCHAR(128) NULL DEFAULT NULL AFTER `fp_hash`");
}
if($db->QueryGetNumRows("SHOW KEYS FROM `users` WHERE KEY_NAME = 'ref'") > 0){
	$db->Query("ALTER TABLE `users` ADD INDEX(`ref`)");
}
if($db->QueryGetNumRows("SHOW KEYS FROM `ref_commissions` WHERE KEY_NAME = 'referral'") > 0){
	$db->Query("ALTER TABLE `ref_commissions` ADD INDEX(`referral`)");
}
if($db->QueryGetNumRows("SHOW KEYS FROM `coins` WHERE KEY_NAME = 'coinbase'") > 0){
	$db->Query("ALTER TABLE `coins` ADD INDEX(`coinbase`)");
}
$db->Query("INSERT IGNORE INTO `offerwall_config` (`config_name`, `config_value`) VALUES ('offerwall_key',''),('offerwall_secret',''),('offerwall_url','')");

// Update to 1.0.1
$add_config[] = "('scratch_price', '100'),('scratch_win_chance', '45'),('scratch_prizes', 'a:6:{i:1;s:2:\"75\";i:2;s:3:\"175\";i:3;s:3:\"250\";i:4;s:3:\"400\";i:5;s:3:\"500\";i:6;s:4:\"2000\";}')";
$db->Query("INSERT IGNORE INTO `offerwall_config` (`config_name`, `config_value`) VALUES ('monlix_api',''),('monlix_secret','')");
$db->Query("CREATE TABLE IF NOT EXISTS `scratch_games` ( `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `user_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' , `bet` INT(11) UNSIGNED NOT NULL DEFAULT '0' , `profit` INT(11) NOT NULL DEFAULT '0' , `status` SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0' , `time` INT(11) UNSIGNED NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;");
$db->Query("ALTER TABLE `completed_offers` CHANGE `user_ip` `user_ip` VARCHAR(128) NULL DEFAULT NULL;");

// Insert new configs
$config_values = implode(',', $add_config);
$db->Query("INSERT IGNORE INTO `site_config` (`config_name`, `config_value`) VALUES ".$config_values);

// Remove files
if($db->Connect()){
	eval(base64_decode('QHVubGluayhyZWFscGF0aChkaXJuYW1lKF9fRklMRV9fKSkuJy9ydW5fdXBkYXRlLnBocCcpOw=='));
}
?>