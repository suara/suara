<?php
/**
 * Suara Kernel
 *
 * 每个APP在引用根目录下的bootstrap后，会自动引用此文件。
 * 本文件主需要处理Suara系统基础性的东西，比如自动加载功能、
 * 文件导入功能等等。
 *
 * @package Suara.Core.Kernel
 * @author wolftankk@plu.cn
 *
 */
defined('IN_SUARA') or exit('Permission deiened');

class Kernel {
	protected static $_map = array();

	private static $classTypes = array("Libs", "Plugins", "Apps");
	
	//以后会专门启用
	//public static $legacy = array(
	//	'libs'    => SUARA_CORE_PATH,
	//	'modules' => SUARA_MODULES_PATH,
	//	//'models'  => S_PATH."models".DIRECTORY_SEPARATOR
	//);
	
	/**
	 * Suara内核自动加载类
	 * 在php5.4的时候，引入了namespace概念，因此Suara整套框架体系
	 * 都在在此基础上建立的。通过调用use方法，获得namespace class的路径
	 * 在Suara的namespce命名规则中，以Suara开头，后面是请求的类型，一共
	 * 支持三种: Libs, Plugins, Apps。 该类别将会自动解析成相应的路径。
	 * 目前之后为class所在的相对路径，比如说：
	 * Suara\libs\Core\Configure
	 * 经过自动能解析后会变成 SUARA_CORE_PATH/libs/Core/Configure.php;
	 *
	 * 具体关于namespace可以直接参考php官方文件。
	 * @see http://www.php.net/manual/en/language.namespaces.php
	 *
	 */
	public static function load($className) {
		if ( strpos($className, "Suara") !== 0 ) {
			return false;
		}

		list(, $type, $parts)= explode("\\", $className, 3);

		if (!in_array($type, self::$classTypes)) {
			return false;
		}

		$path = "";
		switch ($type) {
			case "Libs":
				$path = SUARA_CORE_PATH;
				break;
			case "Plugins":
				$path = SUARA_PATH."plugins";
				break;
			case "Apps":
				$path = SUARA_APPS_PATH;
				break;
		}

		$normalizedClassName = str_replace('\\', DIRECTORY_SEPARATOR, $parts);
		$file = $path.$normalizedClassName.".php";

		$hashKey = md5($file);

		if (!empty(self::$_map[$hashKey])) {
			return true;
		}

		if (file_exists($file)) {
			self::$_map[$hashKey] = $file;
			return include $file;
		}

		return false;
	}

	/**
	 * 
	 */
	public static function import($type, $path) {

	}

	protected static function _loadClass() {

	}

	protected static function _loadFile() {

	}

	protected static function _loadVendor() {

	}

	public static function init() {
		register_shutdown_function(array('Kernel', 'shutdown'));
	}

	public static function shutdown() {
		self::_checkFatalError();
	}

	protected static function _checkFatalError() {
	}
}
?>
