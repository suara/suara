<?php
/**
 * @package Core.Configure
 * @author wolftankk@plu.cn
 * 配置文件读取器
 */
namespace Suara\Libs\Core;
use Suara\Libs\Configure\IConfigureReader;
use Suara\Libs\Configure\PhpReader;
use Suara\Libs\Configure\IniReader;

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
	 * 初始化配置同时启动系统
	 * 启动系统流程
	 *
	 * - 在Configure中配置APP
	 * - 引用 config/system.php
	 * - 载入APP配置文件
	 * - 载入config中bootstrap.php
	 * - 错误处理
	 */
	public static function bootstrap($boot = true) {
		if ($boot) {
			if (!include APP_CONFIG_PATH.DIRECTORY_SEPARATOR.'system.php') {
				trigger_error('Cannot found the system php file', E_USER_ERROR);
			}

			$exception = array(
				'handler' => 'Suara\Libs\Error\ErrorHandler::handleException'
			);
			$error = array(
				'handler' => 'Suara\Libs\Error\ErrorHandler::handleError',
				'level'   => E_ALL & ~E_DEPRECATED
			);

			/**
			 * 设定错误跟踪
			 */
			self::_setErrorHandlers($error, $exception);

			//重置
			restore_error_handler();

			self::_setErrorHandlers(
				self::$_values['system']['Error'],
				self::$_values['system']['Exception']
			);
		}
	}

	/**
	 * @param string $type 配置类型 默认含有System
	 * @param mixed $config 所对应的配置参数
	 * @param mixed $value 配置参数值
	 */
	public static function write($type, $config, $value = null) {
		if (!is_array($config)) {
			$config = array($config => $value);
		}
	
		//save
		foreach ($config as $name => $value) {
			self::$_values[$type][$name] = $value;
		}

		if (isset($config['debug']) && function_exists('ini_set')) {
			if (self::$_values[$type]['debug']) {
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
	 * 使用方法:
	 * {{{
	 * Configure::read('system', 'Name') 返回所有Name的值
	 * Configure::read('system', 'Name.key') 返回Name中key的值
	 * }}}
	 *
	 * @param string $type 配置类型 默认含有system
	 * @param string $var  如果var为null，将返回所有$type的配置
	 */
	public static function read($type, $var = null) {
		if ($var === null) {
			return self::$_values[$type];
		}

		if (empty(self::$_values[$type])) {
			return null;
		}

		if (is_string($var) || is_numeric($var)) {
			$parts = explode(".", $var);
		} else {
			$parts = $var;
		}

		$data = self::$_values[$type];
		foreach ($parts as $key) {
			if (is_array($data) && isset($data[$key])) {
				$data = &$data[$key];
			} else {
				return null;
			}
		}

		return $data;
	}

	/**
	 * 将Reader保存在pool中，避免重复创建，造成内存的浪费
	 */
	public static function config($name, IConfigureReader $reader) {
		self::$_readers[$name] = $reader;
	}
	
	/**
	 * 载入配置文件
	 *
	 * 配置文件名称
	 */
	public static function load($key, $config = 'default', $merge = true) {
		$reader = self::_getReader($config);

		if(!$reader) {
			return false;
		}
		$values = $reader->read($key);

		return self::write($key, $values);
	}

	/**
	 * 获取配置文件读取器
	 */
	private static function _getReader($config) {
		if (!isset(self::$_readers[$config])) {
			if ($config !== 'default') {
				return false;
			}

			self::config($config, new PhpReader);
		}

		return self::$_readers[$config];
	}

	/**
	 * 设定Error和Exception处理
	 *
	 * @param array $error Error处理配置
	 * @param array $exception 异常处理配置
	 */
	private static function _setErrorHandlers($error, $exception) {
		$level = -1;
		if (isset($error['level'])) {
			error_reporting($error['level']);
			$level = $error['level'];
		}

		if (!empty($error['handler'])) {
			set_error_handler($error['handler'], $level);
		}

		if (!empty($exception['handler'])) {
			set_exception_handler($exception['handler']);
		}
	}
}
?>
