<?php
if (!function_exists("hex2bin")) {
	function hex2bin($hexstr) {
		$n = strlen($hexstr);
		$sbin="";  
		$i=0;
		while($i<$n){      
			$a =substr($hexstr,$i,2);          
			$c = pack("H*",$a);
			if ($i==0){
				$sbin=$c;
			} else {
				$sbin.=$c;
			}
			$i+=2;
		}
		return $sbin;
	} 
}

class plucrypto {
	private $encryptionKey;// = "0F10F6CB2F5369C14D14FA07BAD302267901240CC8C845DD2C645FBD149A11C9";
	private $validationKey;// = "C985085862F161091EEEFE30F7DC9D62";

	private function toByte($str) {
		$hex = "";
		for ($i = 0; $i < strlen($str) / 2; $i++) {
			$k = substr($str, $i*2, 2);
			$hex .= hex2bin($k);
		}
		return $hex;
	}

	public function __construct($encryptionKey, $validationKey) {
		$this->encryptionKey = $this->toByte($encryptionKey);
		$this->validationKey = $this->toByte($validationKey);
	}

	public function updateKeys($encryptionKey, $validationKey) {
		$this->encryptionKey = $this->toByte($encryptionKey);
		$this->validationKey = $this->toByte($validationKey);
	}

	public function encrypt($clearData) {
		//AES
		$cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		$ciphername = mcrypt_enc_get_algorithms_name($cipher);
		//随机生产iv 16
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($cipher), MCRYPT_DEV_URANDOM);
		mcrypt_generic_init($cipher, $this->encryptionKey, $iv);
		//PADDING PCK5
		$block_size = mcrypt_enc_get_block_size($cipher);
		$padding = $block_size - (strlen($clearData) % $block_size);
		$clearData .= str_repeat(chr($padding), $padding);
		$data = mcrypt_generic($cipher, $clearData);
		mcrypt_generic_deinit($cipher);
		mcrypt_module_close($cipher);
		$hashData = $iv . $data;
		//取16位
		$hash = hash_hmac("sha256", $hashData, $this->validationKey);
		$encryptData = bin2hex($iv) . bin2hex($data) . substr($hash, 0, 16);
		return $encryptData;
	}

	public function decrypt($encryptData) {
		if (empty($encryptData)) { 
			return false; 
		}
		$encryptData = $this->toByte($encryptData);
		$encryptionAlgorithm = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		$iv_length = mcrypt_enc_get_block_size($encryptionAlgorithm);
		$hash_size = 8;
		$hash = bin2hex(substr($encryptData, - $hash_size));

		$need_hash_data = substr($encryptData, 0, strlen($encryptData) - $hash_size);
		if ($hash != substr(hash_hmac("sha256", $need_hash_data, $this->validationKey), 0, 16)) {
			return false;
		}
		$iv = substr($encryptData, 0, $iv_length);
		mcrypt_generic_init($encryptionAlgorithm, $this->encryptionKey, $iv);
		$_data = substr($encryptData, $iv_length, strlen($encryptData) - $iv_length - $hash_size);
		$data = mdecrypt_generic($encryptionAlgorithm, $_data);
		mcrypt_generic_deinit($encryptionAlgorithm);
		mcrypt_module_close($encryptionAlgorithm);

		$padding = ord($data{strlen($data)-1});  
		if ($padding > strlen($data)) { 
			return false;  
		}
		if (strspn($data,chr($padding),strlen($data)-$padding) != $padding) {  
			return false;
		}
		return substr($data, 0, -1 * $padding);  
	}
}

if (PHP_SAPI == 'cli') {
	//CLI test
	$C = new plucrypto();
	$clearData = 313131;
	$a = $C->encrypt($clearData);
	echo $a."\n";
	echo $C->decrypt($a);
}
?>
