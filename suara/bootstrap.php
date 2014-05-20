<?php
/**
 * Suara Bootstrap (http://suaraphp.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link          http://suaraphp.com
 * @package       Suara
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @author        wolftankk@gmail.com		  
 */

/**
 * @namespace Suara
 *
 * Suaraphp framework init
 */
namespace Suara;
/**
 * @const IN_SUARA
 */
define("IN_SUARA", true);

/**
 * @const START_TIME  booting time
 */
define('START_TIME', microtime(true));

/**
 * @const S_PATH Suara Library Path
 */
define("S_PATH", dirname(__FILE__).DIRECTORY_SEPARATOR);

/**
 * @const SUARA_CORE_PATH Suara Core lib path
 */
if (!defined("SUARA_CORE_PATH")) {
	define('SUARA_CORE_PATH', S_PATH."libs".DIRECTORY_SEPARATOR);
}

/**
 * @const SUARA_PATH 根目录路径
 */
if (!defined("SUARA_PATH")){
	define("SUARA_PATH", realpath(S_PATH."..").DIRECTORY_SEPARATOR);
}

/**
 * @const SUARA_APPS_PATH Suara application path
 * 每个apps目录下包含有config logs template caches
 */
if (!defined("SUARA_APPS_PATH")) {
	define('SUARA_APPS_PATH', SUARA_PATH."applications".DIRECTORY_SEPARATOR);
}

/**
 * @const Suara plugins path
 */
if (!defined("SUARA_PLUGINS_PATH")) {
	define('SUARA_PLUGINS_PATH', SUARA_PATH."plugins".DIRECTORY_SEPARATOR);
}

/**
 * @const Suara verdors path
 */
if (!defined("SUARA_VENDORS_PATH")) {
	define('SUARA_VENDORS_PATH', SUARA_PATH.'vendors'.DIRECTORY_SEPARATOR);
}

const S_PATH = S_PATH;
const SUARA_CORE_PATH = SUARA_CORE_PATH;
const SUARA_PATH = SUARA_PATH;
const SUARA_APPS_PATH = SUARA_APPS_PATH;
const SUARA_PLUGINS_PATH = SUARA_PLUGINS_PATH;
const SUARA_VENDORS_PATH = SUARA_VENDORS_PATH;

/**
 * application目录定义，在每个app目录下可以直接配置这些参数
 */
if (!defined("APP_TEMPLATE_PATH")) {
	define('APP_TEMPLATE_PATH', SUARA_APPS_PATH."templates".DIRECTORY_SEPARATOR);
}
//app cache目录 用于存放缓存文件，sessions
if (!defined("APP_CACHE_PATH")) {
	define("APP_CACHE_PATH", SUARA_APPS_PATH.'caches'.DIRECTORY_SEPARATOR);
}
//logs目录 用于存放记录文件
if (!defined("APP_LOGS_PATH")) {
	define('APP_LOGS_PATH', SUARA_APPS_PATH.'logs'.DIRECTORY_SEPARATOR);
}
//config目录
if (!defined("APP_CONFIG_PATH")) {
	define('APP_CONFIG_PATH', SUARA_APPS_PATH.'config'.DIRECTORY_SEPARATOR);
}
//wwwroot
if (!defined("APP_WWWROOT_PATH")) {
	define('APP_WWWROOT_PATH', SUARA_APPS_PATH.'wwwroot'.DIRECTORY_SEPARATOR);
}

//global funcs
include_once S_PATH.'globals.php';

exit;
//载入核心启动文件
//require SUARA_CORE_PATH."Core".DIRECTORY_SEPARATOR."Kernel.php";
////将系统异常加载进来
//require SUARA_CORE_PATH."Error".DIRECTORY_SEPARATOR."exceptions.php";
////启用spl自动加载功能
//spl_autoload_register(array("Suara\Libs\Core\Kernel", "load"));
////启用错误处理
//use Suara\Libs\Error\ErrorHandler;
////启用配置文件调用
//use Suara\Libs\Core\Configure;
//
////booting..
//$boot = false;
//Configure::bootstrap(isset($boot) ? $boot : true);
//
//date_default_timezone_set('Asia/Shanghai');
?>
