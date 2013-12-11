<?php
namespace Suara\libs\Log\SuaraLog;

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

class SuaraLog {

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

	protected static function _init() {
		self::$_levels = self::$_defaultLevels;
	}

	public static function config($key, $config) {

	}

	public static function configured() {

	}

	public static function levels() {

	}

	public static function defaultLevels() {

	}

	public static function drop() {

	}

	public static function stream() {

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

	}

	public static function emergency($message, $scope = array()) {

	}

	public static function alert($message, $scope = array()) {

	}

	public static function critical($message, $scope = array()) {

	}

	public static function error($message, $scope = array()) {

	}

	public static function warning($message, $scope = array()) {

	}

	public static function notice($message, $scope = array()) {

	}

	public static function info($message, $scope = array()) {

	}
}
?>
