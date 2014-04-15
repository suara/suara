<?php
/**
 * 输出控制
 *
 */
namespace Suara\Libs\Http;


class Response {

	/**
	 * HTTP Status code definitions
	 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	 */
	protected $_statusCodes = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
	];

	protected $_protocol = 'HTTP/1.1';

	protected $_status = 200;

	protected $_contentType = 'text/html';

	protected $_headers = [];

	protected $_cookies = [];

	protected $_body = null;

	protected $_charset = 'UTF-8';
	
	public function __construct($options = []) {
		if (isset($options['body'])) {
			$this->body($options['body']);
		}

		if (isset($options['status'])) {
			$this->statusCode($options['status']);
		}

		if (isset($options['type'])) {
			//mime type
			$this->type($options['type']);
		}

		if (!isset($options['charset'])) {
			//$options['charset'] = Configure::read('system', 'charset');
		}

		//$this->charset($options['charset']);
	}

	public function send() {
		if (isset($this->_headers['Location']) && $this->_status == 200) {
			$this->statusCode(302);
		}

		$codeMessage = $this->_statusCodes[$this->_status];
		$this->_setCookie();
		$this->_sendHeader("{$this->_protocol} {$this->_status} {$codeMessage}");
		$this->_setContent();
		$this->_setContentLength();
		$this->_setContentType();
		foreach ($this->_headers as $header => $values) {
			foreach ((array)$values as $value) {
				$this->_sendHeader($header, $value);
			}
		}

		//file

		$this->_sendContent($this->_body);
	}

	public function header() {

	}

	public function cookie() {

	}

	public function body($content = null) {
		if ($content == null) {
			return $this->_body;
		}
		$this->_body = $content;
	}

	public function statusCode($code = null) {
		if ($code == null) {
			$code = $this->_status; //default 200
		}
		if (!isset($this->_statusCodes[$code])) {
			//throw
		}

		$this->status = $code;
	}

	public function type() {

	}

	public function charset() {

	}

	private function _setCookie() {
		foreach ($this->_cookies as $name => $value) {
			setcookie($name, $value['value'], $value['expire'], $value['path'], $value['domain'], $value['secure'], $value['httpOnly']);
		}
	}

	private function _sendHeader($name, $value = null) {
		if (!headers_sent()) {
			if ($value == null) {
				header($name);
			} else {
				header("{$name}: {$value}");
			}
		}
	}

	private function _setContent() {

	}

	private function _setContentLength() {

	}

	private function _setContentType() {

	}

	private function _sendContent($content) {
		echo $content;
	}

	public function compress() {
		if(function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
			ob_start('ob_gzhandler');
		} else {
			ob_start();
		}
	}

	public function outputCompressed() {

	}
}
?>
