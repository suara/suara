<?php
/**
 *
 */
namespace Suara\Libs\Http;
use Suara;

/**
 * 网络请求数据全部从这边走
 */
class Request{
	public $base = false;

	public $url = false;

	public function __construct($url = null, $parseEnvironment = true) {
		if(!get_magic_quotes_gpc()) {
			$_POST = Suara\new_addslashes($_POST);
			$_GET = Suara\new_addslashes($_GET);
			$_REQUEST = Suara\new_addslashes($_REQUEST);
			$_COOKIE = Suara\new_addslashes($_COOKIE);
		}

		//$this->_base();

		$url = $this->_url();

		if ($url[0] === '/') {
			$url = substr($url, 1);
		}
		$this->url = $url;

		if ($parseEnvironment) {
			//process

		}
	}

	private function _base() {
	}

	private function _url() {
		if (!empty(Suara\env('PATH_INFO'))) {
			return Suara\env('PATH_INFO');
		} elseif (isset($_SERVER['REQUEST_URI']) && strpos(Suara\env('REQUEST_URI'), '://') === false) {
			$uri = Suara\env('REQUEST_URI');
		} elseif ($var = Suara\env('argv')) {
			$uri = $var[0];
		}

		$base = $this->base;
		if (strlen($base) == 0 && strpos($uri, $base) === 0) {
			$uri = substr($uri, strlen($base));
		}

		if (strpos($uri, '?') !== false) {
			list($uri) = explode("?", $uri, 2);
		}

		//强制转成小写
		$uri = strtolower($uri);

		return $uri;
	}
}

?>
