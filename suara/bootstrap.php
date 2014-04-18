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
namespace Suara;
define("IN_SUARA", true);

define('START_TIME', microtime(true));

/**
 * S_PATH
 * Suara Library Path
 * TODO: PHP 5.6 remove
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
 * Suara plugins path
 */
if (!defined("SUARA_PLUGINS_PATH")) {
	define('SUARA_PLUGINS_PATH', SUARA_PATH."plugins".DIRECTORY_SEPARATOR);
}

define('VENDORS_PATH', SUARA_PATH.'vendors'.DIRECTORY_SEPARATOR);

const S_PATH = S_PATH;
const SUARA_CORE_PATH = SUARA_CORE_PATH;
const SUARA_PATH = SUARA_PATH;
const SUARA_APPS_PATH = SUARA_APPS_PATH;
const SUARA_PLUGINS_PATH = SUARA_PLUGINS_PATH;

/**
 * app template
 * 应用的模板路径
 * @TODO 需要启用
 */
if (!defined("SUARA_TEMPLATE_PATH")) {
	define('SUARA_TEMPLATE_PATH', SUARA_APPS_PATH."templates".DIRECTORY_SEPARATOR);
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
//modules
//if (!defined("APP_MODULE_PATH")) {
//	define('APP_MODULE_PATH', SUARA_APPS_PATH.'wwwroot'.DIRECTORY_SEPARATOR);
//}

//global funcs
include_once S_PATH.'globals.php';

//载入核心启动文件
require SUARA_CORE_PATH."Core".DIRECTORY_SEPARATOR."Kernel.php";
//将系统异常加载进来
require SUARA_CORE_PATH."Error".DIRECTORY_SEPARATOR."exceptions.php";
//启用spl自动加载功能
spl_autoload_register(array("Suara\Libs\Core\Kernel", "load"));
//启用错误处理
use Suara\Libs\Error\ErrorHandler;
//启用配置文件调用
use Suara\Libs\Core\Configure;

//booting..
//Configure::bootstrap(isset($boot) ? $boot : true);

//设置默认sessions所报存的目录
//ini_set("session.save_path", CACHE_PATH."sessions");
?>
