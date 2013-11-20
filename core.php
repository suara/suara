<?php
ini_set("display_errors", "0");
error_reporting(E_ALL ^ E_NOTICE);

define("IN_SUARA", true);
date_default_timezone_set("Asia/Shanghai");
define("S_PATH", dirname(__FILE__).DIRECTORY_SEPARATOR);
if (!defined("SUARA_PATH")){
	define("SUARA_PATH", S_PATH."..".DIRECTORY_SEPARATOR);
}
//CACHE
define("CACHE_PATH", SUARA_PATH.'caches'.DIRECTORY_SEPARATOR);
define('ASSET_COMPILE_OUTPUT_DIR', CACHE_PATH.'asset_cache');
define('ASSET_COMPILE_URL_ROOT', '/caches/asset_cache');
define('SACY_WRITE_HEADERS', false);

define("HTTP_REFERER", (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : ''));
define("SITE_PROTOCOL", isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://");
define("SITE_URL", isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');

ini_set("session.save_path", CACHE_PATH."sessions");

// load common func and class
s_core::load_sys_func("globals");
s_core::auto_load_func();
register_template_data("site_config", s_core::load_config("system"));

if(s_core::load_config('system','gzip') && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
	ob_start('ob_gzhandler');
} else {
	ob_start();
}

class s_core {
	/**
	 * Create an app and run
	 */
	public static function Run() {
		return self::load_sys_class('Router');
	}

	/**
	 * 加载系统自带的类
	 */
	public static function load_sys_class($classname, $path = '', $initialize = true) {
		return self::_load_class($classname, $path, $initialize);
	}

	/**
	 * 加载模块中的类
	 */
	public static function load_app_class($classname, $m = "", $initialize= true) {
		$m = empty($m) && defined("ROUTER_M") ? ROUTER_M : $m;
		if (empty($m)) return false;

		return self::_load_class($classname, 'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'classes', $initialize);
	}

	/**
	 * 加载系统数据模型
	 */
	public static function load_sys_model($classname) {
		return self::_load_class($classname, 'models');
	}

	/**
	 * load app model
	 */
	public static function load_app_model($classname, $m ="") {
		$m = empty($m) && defined("ROUTER_M") ? ROUTER_M : $m;
		if (empty($m)) return false;

		return self::_load_class($classname, 'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'models');
	}

	public static function load_sys_func($func, $path = '') {
		return self::_load_func($func, $path);
	}

	/**
	 * load app functions
	 */
	public static function load_app_func($func, $m = "" ) {
		$m = empty($m) && defined("ROUTER_M") ? ROUTER_M : $m;
		if (empty($m)) return false;
		return self::_load_func($func, 'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'functions');
	}

	public static function auto_load_func($path = '') {
		return self::_auto_load_func($path);
	}

	/**
	 * 加载php类文件
	 * @param string $classname 类名
	 * @param string $path 指定类的路径
	 * @param boolean $initialize 是否初始化
	 */
	private static function _load_class($classname, $path = '', $initialize=true) {
		static $classes = array();
		//类文件名全部小写化.
		$file_name = strtolower($classname);
		if (empty($path)) {
			$path = "libs" . DIRECTORY_SEPARATOR . "classes";
		}
		$key = md5($path.DIRECTORY_SEPARATOR.$file_name);
		if (isset($classes[$key])) {
			if (!empty($classes[$key])) {
				return $classes[$key];
			} else {
				return true;
			}
		}

		if (file_exists(S_PATH.$path.DIRECTORY_SEPARATOR.$file_name.".php")) {
			include S_PATH.$path.DIRECTORY_SEPARATOR.$file_name.".php";
			if ($initialize) {
				$classes[$key] = new $classname;
			} else {
				$classes[$key] = true;
			}
			return $classes[$key];
		} else {
			return false;
		}
	}

	/**
	 * 加载函数库
	 * @param $func 需要加载的函数名
	 * @param $path 指定的函数库路径
	 */
	private static function _load_func($func, $path='') {
		static $funcs = array();
		if (empty($path)) {
			$path = "libs" . DIRECTORY_SEPARATOR . "functions";
		}
		$path .= DIRECTORY_SEPARATOR . $func . ".php";
		$key = md5($path);
		if (isset($funcs[$key])) {
			return true;
		}

		if (file_exists(S_PATH.$path)) {
			include S_PATH.$path;
		} else {
			$funcs[$key] = false;
			return false;
		}
		$funcs[$key] = true;
		return true;
	}

	/**
	 * 自动加载函数库
	 *
	 */
	private static function _auto_load_func($path='') {
		if (empty($path)) {
			$path = "libs" . DIRECTORY_SEPARATOR . "functions" . DIRECTORY_SEPARATOR . "autoload";
		}
		$path .= DIRECTORY_SEPARATOR . "*.php";
		$files = glob(S_PATH.$path);
		if (!empty($files) && is_array($files)) {
			foreach ($files as $f) {
				include $f;
			}
		}
	}

	/**
	 * 加载读取配置文件
	 * @param string $file 需要加载的配置文件名
	 * @param string $key 需要获取的配置key
	 * @param string $default 如无法获取到, 即自动返回此默认值
	 * @param boolean $reload 强制重载配置
	 */
	public static function load_config($file, $key='', $default='', $reload=false) {
		static $configs = array();
		if ($reload || !isset($configs[$file])) {
			$file_path = CACHE_PATH . "configs" . DIRECTORY_SEPARATOR . $file . ".php";
			if (file_exists($file_path)) {
				$configs[$file] = include $file_path;
			}
		}

		if (empty($key))  {
			return $configs[$file];
		}elseif (isset($configs[$file][$key])) {
			return $configs[$file][$key];
		} else {
			return $default;
		}
	}
}
?>
