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

		if (!empty(self::$classMap[$key])) {
			return true;
		}
			
		if (file_exists($file)) {
			self::$classMap[$key] = $file;
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

	///**
	// * 加载读取配置文件
	// * @param string $file 需要加载的配置文件名
	// * @param string $key 需要获取的配置key
	// * @param string $default 如无法获取到, 即自动返回此默认值
	// * @param boolean $reload 强制重载配置
	// */
	//public static function load_config($file, $key='', $default='', $reload=false) {
	//	static $configs = array();
	//	if ($reload || !isset($configs[$file])) {
	//		$file_path = CACHE_PATH . "configs" . DIRECTORY_SEPARATOR . $file . ".php";
	//		if (file_exists($file_path)) {
	//			$configs[$file] = include $file_path;
	//		}
	//	}

	//	if (empty($key))  {
	//		return $configs[$file];
	//	}elseif (isset($configs[$file][$key])) {
	//		return $configs[$file][$key];
	//	} else {
	//		return $default;
	//	}
	//}
}
?>
