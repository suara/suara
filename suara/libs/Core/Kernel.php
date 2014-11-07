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
namespace Suara\Libs\Core;

class Kernel {

	/**
	 * Save registered namespace and path
	 * for vendors
	 */
	protected static $_namespaces = [];


	/**
	 * save all loaded classes
	 */
	protected static $_map = [];

//	private static $classTypes = [
//		"Libs" => SUARA_CORE_PATH, 
//		"Plugins" => SUARA_PLUGINS_PATH, 
//		"Apps" => SUARA_APPS_PATH
//	];

	public static $booting = false;
	
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
	 * @param string $className need autoload class
	 */
	public static function load($className) {
		//判断是否是属于Suara的内库
		//if ( strpos($className, "Suara") !== 0 ) {
		//	return false;
		//}

		//list(, $type, $parts)= explode("\\", $className, 3);

		//$path = "";
		//if (!empty(self::$classTypes[$type])) {
		//	$path = self::$classTypes[$type];
		//} else {
		//	return false;
		//}

		//$normalizedClassName = str_replace('\\', DIRECTORY_SEPARATOR, $parts);
		//$file = $path.$normalizedClassName.".php";

		//$hashKey = md5($file);
		//if (!empty(self::$_map[$hashKey])) {
		//	return true;
		//}

		//if (file_exists($file)) {
		//	self::$_map[$hashKey] = $file;
		//	return include $file;
		//}

		//return false;
	}

	/**
	 * 载入指定的文件或者其他
	 * 提供载入额外的class, file, vendor
	 *
	 * @param string $type 需要载入文件的类型
	 * @param string $name
	 * @param string $file
	 */
	//public static function import($type = null, $name = null, $file = null) {
	//	if (!empty($name) && !empty($file)) {
	//		return;
	//	}

	//	$ltype = strtolower($type);
	//	$returnValue = false;

	//	$hashKey = md5($file);
	//	if (!empty(self::$_map[$hashKey])) {
	//		return true;
	//	}

	//	if ($ltype == 'file') {
	//		$returnValue = self::_loadFile($file);
	//	}

	//	if ($returnValue) {
	//		self::$_map[$hashKey] = $file;
	//	}

	//	return $returnValue;
	//}

//	/**
//	 * _load special file
//	 */
//	protected static function _loadFile($path) {
//		if (!file_exists($path)) {
//			return false;
//		}
//
//		if (!is_readable($path)) {
//			return false;
//		}
//
//		return include $path;
//	}
//
//	//protected static function _loadClass() {
//	//}
//
//	//protected static function _loadVendor() {
//	//}
//
//	public static function init() {
//		register_shutdown_function(['Suara\Libs\Core\Kernel', 'shutdown']);
//	}
//
//	public static function shutdown() {
//		self::_checkFatalError();
//	}
//
//	protected static function _checkFatalError() {
//	}
}
?>
