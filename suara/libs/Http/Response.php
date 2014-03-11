<?php
namespace Suara\Libs\Http;


class Response {

	protected $_protocol = 'HTTP/1.1';

	protected $_status = 200;

	protected $_contentType = 'text/html';

	protected $_headers = array();

	protected $_cookies = array();

	protected $_body = null;
	
	public function __construct() {

	}

	public function send() {

	}

	public function header() {

	}

	public function cookie() {

	}

	private function _setCookie() {

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
