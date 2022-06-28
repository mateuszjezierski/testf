<?php
/* 
	CoinBase PHP Library
	
	CHANGELOG:
	v1.0: - First version released

	This library was created by ScriptStore.xyz
*/
class CoinbaseAPI
{
	protected $apiKey;
	protected $apiSecret;
	protected $timestamp;
	protected $api_base = 'https://api.coinbase.com';
	protected $api_version = '2020-06-23';
	
	public function __construct($apiKey, $apiSecret, $verify_peer = false) {
		$this->apiKey = $apiKey;
		$this->apiSecret = $apiSecret;
		$this->verify_peer = $verify_peer;
		$this->timestamp = time();
	}

	public function getAccountID($coin)
	{
		$body = '';
		$path = '/v2/accounts/'.$coin;
		$message = $this->timestamp.'GET'.$path.$body;
		$signature = hash_hmac('SHA256', $message, $this->apiSecret);

		$headers = array(
			'CB-ACCESS-SIGN: ' . $signature,
			'CB-ACCESS-TIMESTAMP: ' . $this->timestamp,
			'CB-ACCESS-KEY: ' . $this->apiKey,
			'CB-VERSION: ' . $this->api_version
		); 

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $this->api_base . $path);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		$data = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($data, true);
		return $data['data']['id'];
	}

	public function SendPayment($to, $amount, $coin, $description = false)
	{
		$accountid = $this->getAccountID($coin);
		$path = '/v2/accounts/'.$accountid.'/transactions';
		
		$params = array(
			'to' => $to,
			'amount' => $amount,
			'currency' => $coin,
			'type' => 'send',
			'skip_notifications' => true
		);
		
		if($description)
		{
			$params['description'] = $description;
		}
		
		$message = $this->timestamp.'POST'.$path.json_encode($params);
		$signature = hash_hmac('SHA256', $message, $this->apiSecret);

		$headers = array(
			'CB-ACCESS-SIGN: ' . $signature,
			'CB-ACCESS-TIMESTAMP: ' . $this->timestamp,
			'CB-ACCESS-KEY: ' . $this->apiKey,
			'CB-VERSION: ' . $this->api_version,
			'Content-Type: application/json'
		); 

		$ch = curl_init($this->api_base . $path);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$data = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($data, true);
		return $data;
	}
}
?>