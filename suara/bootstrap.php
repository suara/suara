<?php
/**
 * Suara Bootstrap
 * @author wolftankk@plu.cn
 * php version > 5.3
 */

namespace Suara;

ini_set("display_errors", "1");
error_reporting(E_ALL ^ E_NOTICE);
define("IN_SUARA", true);
date_default_timezone_set("Asia/Shanghai");

//suara目录
define("S_PATH", dirname(__FILE__).DIRECTORY_SEPARATOR);

//suara库目录
if (!defined("SUARA_CORE_PATH")) {
	define('SUARA_CORE_PATH', S_PATH."libs".DIRECTORY_SEPARATOR);
}

if (!defined("SUARA_APPS_PATH")) {
	define('SUARA_APPS_PATH', S_PATH."applications".DIRECTORY_SEPARATOR);
}

//suara 应用或者模块目录
if (!defined("SUARA_MODULES_PATH")) {
	define('SUARA_MODULES_PATH', SUARA_APPS_PATH."modules".DIRECTORY_SEPARATOR);
}

//suara 模板层
if (!defined("SUARA_TEMPLATE_PATH")) {
	define('SUARA_TEMPLATE_PATH', SUARA_APPS_PATH."templates".DIRECTORY_SEPARATOR);
}

//最外层的根目录
if (!defined("SUARA_PATH")){
	define("SUARA_PATH", realpath(S_PATH."..").DIRECTORY_SEPARATOR);
}

//cache目录 用于存放缓存文件，sessions
define("CACHE_PATH", SUARA_PATH.'caches'.DIRECTORY_SEPARATOR);
ini_set("session.save_path", CACHE_PATH."sessions");

//logs目录 用于存放记录文件
define('LOGS_PATH', SUARA_PATH.'logs'.DIRECTORY_SEPARATOR);

//wwwroot
define('WWWROOT_PATH', SUARA_APPS_PATH.'wwwroot'.DIRECTORY_SEPARATOR);

//verdors
define('VENDORS_PATH', SUARA_PATH.'vendors'.DIRECTORY_SEPARATOR);


require SUARA_CORE_PATH."Core".DIRECTORY_SEPARATOR."Kernel.php";
require SUARA_CORE_PATH."Error".DIRECTORY_SEPARATOR."exceptions.php";
spl_autoload_register(array("Kernel", "load"));

use Suara\libs\Error\ErrorHandler;
use Suara\libs\Core\Configure;

//define('ASSET_COMPILE_OUTPUT_DIR', CACHE_PATH.'asset_cache');
//define('ASSET_COMPILE_URL_ROOT', '/caches/asset_cache');
//define('SACY_WRITE_HEADERS', false);
//load common func and class
//s_core::load_sys_func("globals");
//s_core::auto_load_func();
//register_template_data("site_config", s_core::load_config("system"));
//if(s_core::load_config('system','gzip') && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
//	ob_start('ob_gzhandler');
//} else {
//	ob_start();
//}
?>
