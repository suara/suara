<?php
class Request implements ArrayAccess {
	public $data = array();

	public $query = array();

	public $url;

	public $base = false;

	public function __construct() {
		$this->_url();
	}

	protected function _url() {
		if (!empty($_SERVER['PATH_INFO'])) {
			return $_SERVER['PATH_INFO'];
		} elseif (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '://') === false) {
			$uri = $_SERVER['REQUEST_URI'];
		} elseif (isset($_SERVER['REQUEST_URI'])) {
			$qPosition = strpos($_SERVER['REQUEST_URI'], '?');
			if ($qPosition !== false && strpos($_SERVER['REQUEST_URI'], '://') > $qPosition) {
				$uri = $_SERVER['REQUEST_URI'];
			} else {

			}
		} elseif (isset($_SERVER['PHP_SELF']) && isset($_SERVER['SCRIPT_NAME'])) {
			$uri = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
		} elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
			$uri = $_SERVER['HTTP_X_REWRITE_URL'];
		} elseif ($var = env('argv')) {
			$uri = $var[0];
		}

		echo $uri;
	}

	protected function _base() {
	}

	public function __call($name, $params) {

	}

	public function __get($name) {

	}

	public function __isset($name) {

	}

	/**
	 * ArrayAccess
	 */
	public function offsetGet($name) {

	}

	public function offsetSet($name, $value) {

	}

	public function offsetExists($name) {

	}

	public function offsetUnset($name) {

	}
}
?>
