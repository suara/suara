<?php

namespace Suara\Libs\Http;

class HttpHeaders extends \ArrayObject {
	public function __construct() {
		if (function_exists('getallheaders')) {
			$headers = getallheaders();
			foreach ($headers as $name => $value) {
				$this[$name] = $value;
			}
		} else {
			foreach ($_SERVER as $name => $value) {
				if (substr($name, 0, 5) == 'HTTP_') {
					$name = str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))));
					$this[$name] = $value;
				}
			}
		}
	}

	public function has($name) {
		return parent::offsetExists($name);
	}

	public function get($name) {
		if ($this->has($name)) {
			return parent::offsetGet($name);
		}

		return null;
	}
}

?>
