<?php
/**
 * Suara Bootstrap
 * @author wolftankk@plu.cn
 * @description php版本需要在5.4以上才可以正常使用
 */
namespace Suara;
define("IN_SUARA", true);
date_default_timezone_set("Asia/Shanghai");

/**
 * S_PATH
 * Suara Core Path
 */
define("S_PATH", dirname(__FILE__).DIRECTORY_SEPARATOR);

/**
 * SUARA_CORE_PATH
 * Suara Core lib path
 */
if (!defined("SUARA_CORE_PATH")) {
	define('SUARA_CORE_PATH', S_PATH."libs".DIRECTORY_SEPARATOR);
}

/**
 * SUARA_PATH
 * 根目录路径
 */
if (!defined("SUARA_PATH")){
	define("SUARA_PATH", realpath(S_PATH."..").DIRECTORY_SEPARATOR);
}

/**
 * SUARA_APPS_PATH
 * Suara application path
 */
if (!defined("SUARA_APPS_PATH")) {
	define('SUARA_APPS_PATH', SUARA_PATH."applications".DIRECTORY_SEPARATOR);
}

/**
 * SUARA_MODULES_PATH
 * 单个应用所在的目录
 */
if (!defined("SUARA_MODULES_PATH")) {
	define('SUARA_MODULES_PATH', SUARA_APPS_PATH."modules".DIRECTORY_SEPARATOR);
}

/**
 * SUARA_TEMPLATE_PATH
 * 应用的模板路径
 */
if (!defined("SUARA_TEMPLATE_PATH")) {
	define('SUARA_TEMPLATE_PATH', SUARA_APPS_PATH."templates".DIRECTORY_SEPARATOR);
}

//cache目录 用于存放缓存文件，sessions
define("CACHE_PATH", SUARA_PATH.'caches'.DIRECTORY_SEPARATOR);

//logs目录 用于存放记录文件
define('LOGS_PATH', SUARA_PATH.'logs'.DIRECTORY_SEPARATOR);

//config目录
define('CONFIG_PATH', SUARA_PATH.'config'.DIRECTORY_SEPARATOR);

//wwwroot
define('WWWROOT_PATH', SUARA_APPS_PATH.'wwwroot'.DIRECTORY_SEPARATOR);

//verdors
define('VENDORS_PATH', SUARA_PATH.'vendors'.DIRECTORY_SEPARATOR);

//设置默认sessions所报存的目录
ini_set("session.save_path", CACHE_PATH."sessions");

//global funcs
require S_PATH.'globals.php';
//载入核心启动文件
require SUARA_CORE_PATH."Core".DIRECTORY_SEPARATOR."Kernel.php";
//将系统异常加载进来
require SUARA_CORE_PATH."Error".DIRECTORY_SEPARATOR."exceptions.php";

//启用spl自动加载功能
spl_autoload_register(array("Kernel", "load"));

//启用错误处理
use Suara\libs\Error\ErrorHandler;
//启用配置文件调用
use Suara\libs\Core\Configure;

if (!defined('SITE_URL')) {
	$s = null;
	if (env('HTTPS')) {
		$s = "s";
	}

	$httpHost = env('HTTP_HOST');

	if (isset($httpHost)) {
		define('SITE_URL', 'http' . $s . "://" . $httpHost);
	}
	unset($s, $httpHost);
}

Configure::bootstrap(isset($boot) ? $boot : true);

//register_template_data("site_config", s_core::load_config("system"));
//if(s_core::load_config('system','gzip') && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
//	ob_start('ob_gzhandler');
//} else {
//	ob_start();
//}
?>
