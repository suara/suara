<?php
namespace Suara\libs\Core;
use Suara\libs\Configure\ConfigureReaderInterface;
defined('IN_SUARA') or exit('Permission deiened');

class Configure {
	protected static $_values = array(
	
	);

	protected static $_readers = array();

	public static function bootstrap($boot = true) {

	}

	public static function write($config, $value = null) {

	}

	/**
	 * 从Configure中读取配置信息
	 *
	 * {{{
	 * Configure::read("Name") 返回所有Name的值
	 * Configure::read("Name.key") 返回Name数组中key的值， 相当于Name[key]
	 * }}}
	 *
	 * @return mixed
	 */
	public static function read($var = null) {
		if ($var === null ) {
			return self::$_values;
		}
	}

	public static function check($var = null) {
	}

	public static function delete($var = null) {

	}

	public static function config($name, ConfigureReaderInterface $reader) {
		self::$_readers[$name] = $reader;
	}

	public static function configured() {

	}

	public static function drop() {

	}

	public static function load($key, $config = 'default', $merge = true) {
		$reader = self::_getReader($config);
	}

	public static function dump() {

	}

	private static function _getReader($config) {
		if (!isset(self::$_readers[$config])) {
			if ($config !== 'default') {
				return false;
			}

			self::config($config, new Suara\libs\Configure\PhpReader());
		}

		return self::$_readers[$config];
	}
}
?>
