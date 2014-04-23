<?php
namespace Suara\Libs\Log;
use Suara\Libs\Log\LogEngineCollection;

/**
 * 用于记录系统运行时候的相关信息到文件中。
 * 你可以在Config中配置每个模块记录的位置
 *
 * ### 写入日志
 * 使用 SuaraLog::write() 写入日志, 更多详情请查看该文档后面的内容.
 *
 * ### 日志等级
 * SuaraLog支持RFC 5424文档中所有归档的日志等级。
 * RFC 5424 links: http://tools.ietf.org/html/rfc5424
 *
 * ### 记录范围
 */

class Log {

	/**
	 * Default log levels enum
	 * http://tools.ietf.org/html/rfc5424
	 */
	protected static $_defaultLevels = array(
		'emergency' => LOG_EMERG, // 0 system is unusable
		'alert'     => LOG_ALERT, // 1 action must be taken immediately
		'critical'  => LOG_CRIT,  // 2 critical conditions
		'error'     => LOG_ERR,   // 3 error conditions
		'warning'   => LOG_WARNING,//4 warning conditions
		'notice'    => LOG_NOTICE,// 5 normal but significant conditions
		'info'		=> LOG_INFO,  // 6 infomational messages
		'debug'     => LOG_DEBUG, // 7 debug-level messages
	);

	protected static $_levels;

	protected static $_levelMap;

	protected static $_collection;

	protected static function _init() {
		self::$_levels = self::defaultLevels();
		self::$_collection = new LogEngineCollection;
	}

	public static function config($key, $config) {

	}

	public static function configured() {
		if (empty(self::$_collection)) {
			self::_init();
		}
		self::$_collection->loaded();
	}

	public static function levels() {

	}

	public static function defaultLevels() {
		self::$_levelMap = self::$_defaultLevels;
		self::$_levels = array_flip(self::$_levelMap);

		return self::$_levels;
	}

	public static function drop() {

	}

	public static function stream() {

	}

	private static function _autoConfig() {
		self::$_collection->load('default', [
			'engine' => 'File',
			'path'   => APP_LOGS_PATH
		]);
	}

	/**
	 * ### 类型:
	 *  
	 * - LOG_EMERG
	 * - LOG_ALERT
	 * - LOG_CRIT
	 * - LOG_ERR
	 * - LOG_WARNING
	 * - LOG_NOTICE
	 * - LOG_INFO
	 * - LOG_DEBUG
	 *
	 * ### 使用方法:
	 */
	public static function write($type, $message, $scope = array()) {
		if (empty(self::$_collection)) {
			self::_init();
		}
	
		self::_autoConfig();
	}

	public static function emergency($message, $scope = array()) {
		return self::write(self::$_levelMap['emergency'], $message, $scope);
	}

	public static function alert($message, $scope = array()) {
		return self::write(self::$_levelMap['alert'], $message, $scope);
	}

	public static function critical($message, $scope = array()) {
		return self::write(self::$_levelMap['critical'], $message, $scope);
	}

	public static function error($message, $scope = array()) {
		return self::write(self::$_levelMap['error'], $message, $scope);
	}

	public static function warning($message, $scope = array()) {
		return self::write(self::$_levelMap['warning'], $message, $scope);
	}

	public static function notice($message, $scope = array()) {
		return self::write(self::$_levelMap['notice'], $message, $scope);
	}

	public static function info($message, $scope = array()) {
		return self::write(self::$_levelMap['info'], $message, $scope);
	}
}
?>
