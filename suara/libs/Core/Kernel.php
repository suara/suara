<?php
defined('IN_SUARA') or exit('Permission deiened');
/**
 * Suara Kernel
 * management load class, module, model..
 */
class Kernel {
	protected static $classMap = array();

	private static $classTypes = array("libs", "modules", "models");

	/**
	 * 处理自动加载类,通过调用use方法，获得namespace class的路径
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
		case "libs":
			$path = SUARA_CORE_PATH;
			break;
		case "modules":
			$path = SUARA_MODULES_PATH;
			break;
		case "models":
			$path = S_PATH."models".DIRECTORY_SEPARATOR;
			break;
		}

		$normalizedClassName = str_replace('\\', DIRECTORY_SEPARATOR, $parts);
		$file = $path.$normalizedClassName.".php";

		$hashKey = md5($file);

		if (!empty(self::$classMap[$hashKey])) {
			return true;
		}

		if (file_exists($file)) {
			self::$classMap[$hashKey] = $file;
			return include $file;
		}

		return false;
	}

	/**
	 * like last Suara 
	 */
	public static function import() {

	}

	protected static function _loadClass() {

	}

	protected static function _loadFile() {

	}

	protected static function _loadVendor() {

	}
}
?>
