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
 * @see http://blog.wolftankk.com/2013/04/28/php-routing-part-1-request/
 */
namespace Suara\Libs\Web;

use Suara;
use Suara\Libs\Web\HttpHeaders;
use Suara\Libs\Core\Configure;

class Request {
	public $base = false;

	//Request-URI
	public $uri = false;

	public $here = null;

	public $params = [
		'controller' => null,
		'action'     => null,
		'named'      => [],
		'form'       => [],
		'pass'       => []
	];

	public $query = [];

	public $data  = [];

	public $headers = false;

	public function __construct($uri = null, $parseEnvironment = true) {
		$this->_base();
		if (!$uri) {
			$uri = $this->_uri();
		}
		if ($uri[0] === '/') {
			$uri = substr($uri, 1);
		}
		$this->uri = $uri;

		if ($parseEnvironment) {
			$this->processGET();
			$this->processPOST();
			$this->processFILE();
		}

		$this->here = $this->base . "/" . $this->uri;
	}
	
	//主要针对prefix来处理的
	private function _base() {
	}

	/**
	 * Absolute url path
	 */
	private function _uri() {
		if (!empty(Suara\env('PATH_INFO'))) {
			return Suara\env('PATH_INFO');
		} elseif (isset($_SERVER['REQUEST_URI']) && strpos(Suara\env('REQUEST_URI'), '://') === false) {
			$uri = Suara\env('REQUEST_URI');
		} elseif ($var = Suara\env('argv')) {
			$uri = $var[0];
		}

		$base = $this->base;
		if (strlen($base) > 0 && strpos($uri, $base) === 0) {
			$uri = substr($uri, strlen($base));
		}

		if (strpos($uri, '?') !== false) {
			list($uri) = explode("?", $uri, 2);
		}

		//强制转成小写
		$uri = strtolower($uri);

		return $uri;
	}

	public function __get($key) {
		if (isset($this->params[$key])) {
			return $this->params[$key];
		}
		return null;
	}

	/**
	 * 获取请求ip
	 *
	 * @return ip地址
	 */
	public function ip() {
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

	public function is($type) {

	}

	/**
	 * rfc2616 5.1.1 Method
	 * GET POST PUT DELETE HEAD OPTIONS TRACE CONNECT
	 */
	public function method() {
		return Suara\env('REQUEST_METHOD');
	}

	public function isGet() {
		return $this->method() == 'GET';
	}

	public function isPost() {
		return $this->method() == 'POST';
	}

	public function isPut() {
		return $this->method() == 'PUT';
	}

	public function isDelete() {
		return $this->method() == 'DELETE';
	}

	public function isHead() {
		return $this->method() == 'HEAD';
	}

	public function isOptions() {
		return $this->method() == 'OPTIONS';
	}

	public function host($trustProxy = false) {
		if ($trustProxy) {
			return Suara\env('HTTP_X_FORWARDED_HOST');
		}

		return Suara\env('HTTP_HOST');
	}

	public function protocal() {
		if (Suara\env('HTTPS')) {
			return "https";
		} else {
			return "http";
		}
	}

	/**
	 * 域名后缀长度，.com就为1个，比如.com.cn那么就是2个
	 */
	public function domain($tldLength = 1) {
		$info = explode(".", $this->host());
		$domain = array_slice($info, -($tldLength + 1));

		return join(".", $domain);
	}

	private function processGET() {
		if(!get_magic_quotes_gpc()) {
			$_GET = Suara\new_addslashes($_GET);
		}
		$query = $_GET;

		if (strpos($this->uri, "?") !== false) {
			list(, $queryString) = explode("?", $this->uri);
			parse_str($queryString, $queryArgs);
			$query += $queryArgs;
		}
	
		if (isset($this->params['url'])) {
			$query = array_merge($this->params['url'], $query);
		}

		$this->query = $query;
	}

	private function processPOST() {
		if ($_POST) {
			$this->data = $_POST;
		} elseif (($this->isPut() || $this->isDelete()) && strpos(Suara\env("CONTENT_TYPE"), "application/x-www-form-urlencoded") === 0) {
			$data = $this->_readInput();
			parse_str($data, $this->data);
		}

		$this->data = Suara\new_addslashes($this->data);
	}

	private function _readInput() {
		if (empty($this->_input)) {
			$fh = fopen("php://input", "r");
			$content = stream_get_contents($fh);
			fclose($fh);
			$this->_input = $content;
		}

		return $this->_input;
	}

	private function processFILE() {
		if (isset($_FILES) && is_array($_FILES)) {
			foreach ($_FILES as $name => $data) {
				if ($name != 'data') {
					$this->params['form'][$name] = $data;
				}
			}
		}
	}

	public function getHeaders() {
		if (!$this->headers) {
			$this->headers = new HttpHeaders;
			if (function_exists('getallheaders')) {
				$headers = getallheaders();
				foreach ($headers as $_name => $value) {
					$this->headers[$_name] = $value;
				}
			} else {
				foreach ($_SERVER as $_name => $value) {
					if (substr($_name, 0, 5) == 'HTTP_') {
						$_name = str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($_name, 5))));
						$this->headers[$_name] = $value;
					}
				}
			}
		}

		return $this->headers;
	}

	/**
	 * In Request Header Fields
	 * Accept
	 * Accept-Charset
	 * Accept-Encoding
	 * Accept-Language
	 * Authorization
	 * Except
	 * From
	 * Host
	 */
	public function header($name) {
		$this->getHeaders();
		$name = strtolower(str_replace('_', '-', $name));
		return $this->headers->get($name);
	}

	public function addParams($params) {
		$this->params = array_merge($this->params, (array)$params);
		return $this;
	}

	public function param($key) {
		if (isset($this->params[$key])) {
			return $this->params[$key];
		}
		return false;
	}
}

?>
