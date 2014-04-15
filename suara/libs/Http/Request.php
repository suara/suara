<?php
/**
 * Suara Bootstrap (http://suaraphp.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link          http://suaraphp.com
 * @package       Suara.Libs.Http
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @author        wolftankk@gmail.com		  
 */
namespace Suara\Libs\Http;
use Suara;

/**
 * @see http://blog.wolftankk.com/2013/04/28/php-routing-part-1-request/
 * Request is a http base module.
 *
 * Request includes header, GET, POST, FILE etc..
 *
 * Request *ONLY* get a value
 * Respone *ONLY* set a value
 */
class Request {
	public $base = false;

	public $url = false;

	public $here = null;

	public $params = [
		'controller' => null,
		'action'     => null
	];

	/**
	 * 通过指定的环境变量来匹配是否复合所对应的值。
	 * 在http行为上一般包含有 get, post, put, delete, head, options 除了前两个是常用
	 * 的之外，后者都是针对与RestFul网络模型。
	 */
	protected $_methods = [
		'get'    => ['REQUEST_METHOD', 'GET'],
		'post'   => ['REQUEST_METHOD', 'POST'],
		'put'    => ['REQUEST_METHOD', 'PUT'],
		'delete' => ['REQUEST_METHOD', 'DELETE'],
		'head'   => ['REQUEST_METHOD', 'HEAD'],
		'options'=> ['REQUEST_METHOD', 'OPTIONS']
	];

	public function __construct($url = null, $parseEnvironment = true) {
		//if(!get_magic_quotes_gpc()) {
		//	$_GET = Suara\new_addslashes($_GET);
		//	$_POST = Suara\new_addslashes($_POST);
		//	$_REQUEST = Suara\new_addslashes($_REQUEST);
		//	$_COOKIE = Suara\new_addslashes($_COOKIE);
		//}
		$this->_base();
		if (!$url) {
			$url = $this->_url();
		}
		if ($url[0] === '/') {
			$url = substr($url, 1);
		}
		$this->url = $url;

		if ($parseEnvironment) {
		}

		//current address
		//$this->here = $this->base . $this->url;
	}

	/**
	 * 返回一个基础URL。
	 * 这里的基础url，用作与下面_url相对路径的切割，这样能正确的获取
	 * 到controller.
	 * 例如： http://api.example.com/v1/users/show
	 * 它的绝对路径应该是 /users/show
	 * 而前面http://api.example.com/v1是指定到具体的apps层，做为一个基础url而存在的。
	 * @return string 
	 */
	private function _base() {

	}

	public function __get($key) {
		if (isset($this->params[$key])) {
			return $this->params[$key];
		}
		return null;
	}

	/**
	 * Absolute url path
	 */
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

	/**
	 * 获取请求ip
	 *
	 * @return ip地址
	 */
	public function clientIP() {
		if(Suara\env('HTTP_CLIENT_IP') && strcasecmp(Suara\env('HTTP_CLIENT_IP'), 'unknown')) {
			$ip = Suara\env('HTTP_CLIENT_IP');
		} elseif(Suara\env('HTTP_X_FORWARDED_FOR') && strcasecmp(Suara\env('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$ip = Suara\env('HTTP_X_FORWARDED_FOR');
		} elseif(Suara\env('REMOTE_ADDR') && strcasecmp(Suara\env('REMOTE_ADDR'), 'unknown')) {
			$ip = Suara\env('REMOTE_ADDR');
		} elseif(Suara\env('REMOTE_ADDR') && strcasecmp(Suara\env('REMOTE_ADDR'), 'unknown')) {
			$ip = Suara\env('REMOTE_ADDR');
		}
		return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
	}

	public function addParams($params) {
		$this->params = array_merge($this->params, (array)$params);
		return $this;
	}

	public function addPaths($paths) {

	}

	public function referer() {

	}

	public function domain() {

	}

	public function method() {
		return Suara\env('REQUEST_METHOD');
	}

	public function host($trustProxy = false) {
		if ($trustProxy) {
			return Suara\env('HTTP_X_FORWARDED_HOST');
		}

		return Suara\env('HTTP_HOST');
	}

	public function processGET() {

	}

	public function processPOST() {

	}

	public function processFILE() {

	}

	public function param($key) {
		if (isset($this->params[$key])) {
			return $this->params[$key];
		}
		return null;
	}

	public function onlyAllow($methods) {
		
	}

	private function _readInput() {
		$fh = fopen("php://input", 'r');
		$content = stream_get_contents($fh);
		fclose($fh);
	}
}

?>
