<?php
/**
 * @package Core.Configure
 * @author wolftankk@plu.cn
 * 配置文件读取器
 */
namespace Suara\libs\Core;
use Suara\libs\Configure\ConfigureReaderInterface;
defined('IN_SUARA') or exit('Permission deiened');

class Configure {
	/**
	 * 将所有的配置数据保存在$_values中
	 */
	protected static $_values = array();

	/**
	 * 将所有的配置文件读取器保存在$_readers中
	 */
	protected static $_readers = array();

	/**
	 * @param string $type 配置类型 默认含有System
	 * @param string $key 配置Key
	 * @param mixed $config 所对应的配置参数
	 */
	public static function write($key, $config, $value = null) {
		if (!is_array($config)) {
			$config = array($config = $value);
		}

		//save
		self::$_values[$key] = $config;

		if (isset($config['debug']) && function_exists('ini_set')) {
			if (self::$_values[$key]['debug']) {
				ini_set('display_errors', 1);
			} else {
				ini_set('display_errors', 0);
			}
		}
		
		return true;
	}

	/**
	 * 从Configure中读取配置信息
	 *
	 * @return mixed
	 */
	public static function read($file, $key = '', $default = '') {
		if (!isset($_values[$file])) {
			self::load($file);
		}

		if (empty($key))  {
			return self::$_values[$file];
		}elseif (isset($_values[$file][$key])) {
			return self::$_values[$file][$key];
		} else {
			return $default;
		}
	}

	//public static function check($var = null) {
	//}

	//public static function delete($var = null) {
	//}

	/**
	 * 将Reader保存在pool中，避免重复创建，造成内存的浪费
	 */
	public static function config($name, ConfigureReaderInterface $reader) {
		self::$_readers[$name] = $reader;
	}

	//public static function configured() {
	//}

	/**
	 * 载入配置文件
	 */
	//public static function load($key, $config = 'default', $merge = true) {
	//	$reader = self::_getReader($config);

	//	if(!$reader) {
	//		return false;
	//	}
	//	$values = $reader->read($key);

	//	//if ($merge) {
	//	//	$key = array_keys($values);
	//	//}

	//	return self::write($key, $values);
	//}

	/**
	 * 获取配置文件读取器
	 */
	private static function _getReader($config) {
		if (!isset(self::$_readers[$config])) {
			if ($config !== 'default') {
				return false;
			}

			self::config($config, new \Suara\libs\Configure\PhpReader);
		}

		return self::$_readers[$config];
	}
}
?>
