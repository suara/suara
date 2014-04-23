<?php
namespace Suara\Libs\Log;
use Suara\Libs\Log\ILogStream;
use Suara\Libs\Error\LogException;

class LogEngineCollection {
	private $_loaded = [];

	private $_enabled = [];

	public function load($name, $options = array()) {
		$enable = isset($options['enabled']) ? $options['enabled'] : true;
		$loggerName = $options['engine'];
		unset($options['engine']);

		$className = $this->_getLogger($loggerName);
		$logger = new $className($options);

		if (!$logger instanceof ILogStream) {
			throw new LogException("logger class $loggerName does not implements a write() method.");
		}

		$this->_loaded[$name] = $logger;
		if ($enable) {
			$this->enable($name);
		}
		return $logger;
	}

	public function trigger($callback, $params = [], $options = []) {

	}

	public function __get($name) {

	}

	public function __isset($name) {

	}

	public function enable($name, $prioritize = true) {

	}

	public function prioritize() {

	}

	public function setPrioritize() {

	}

	public function disable($name) {

	}

	public function enabled($name) {

	}

	public function loaded($name) {

	}

	public function unload() {

	}

	protected static function _getLogger($loggerName) {
		if (substr($loggerName, -3) !== "Log") {
			$loggerName .= "Log";
		}

		$class = __NAMESPACE__."\\"."Engine"."\\".$loggerName;

		if (!class_exists($class)) {
			throw new LogException("Cound not load class $loggerName");
		}

		return $class;
	}
}
?>
