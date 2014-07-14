<?php
namespace Suara\Libs\System\Session;
use SessionHandlerInterface;

class CacheSession implements SessionHandlerInterface {
	public function open($save_path, $name) {
		return true;
	}

	public function close() {

		return true;
	}

	public function read($session_id) {

		return true;
	}

	public function write($session_id, $data) {

		return true;
	}

	public function destroy($session_id) {
		return true;
	}

	public function gc($maxlifetime) {

		return true;
	}
}


$handler = new CacheSession();
session_set_save_handler($handler, true);
session_start();
session_id();
$_SESSION['aa'] = "ccc";
?>
