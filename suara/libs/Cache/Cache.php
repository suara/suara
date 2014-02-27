<?php
namespace Suara\Libs\Cache;

final class Cache{
	//private static $cache_factory;

	//protected $cache_config = array();
	//protected $cache_list = array();

	//private $cache_class_path = "";

	//public function __construct() {
	//	$this->cache_class_path = "libs".DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."caches";
	//}

	//public static function get_instance($cache_config = '') {
	//	if (cache_factory::$cache_factory == '' || $cache_config == '') {
	//		cache_factory::$cache_factory = new cache_factory();
	//		if (!empty($cache_config)) {
	//			cache_factory::$cache_factory->cache->config = $cache_config;
	//		}
	//	}

	//	return cache_factory::$cache_factory;
	//}

	//public function get_cache($cache_name) {
	//	if (!isset($this->cache_list[$cache_name]) || !is_object($this->cache_list[$cache_name])) {
	//		$this->cache_list[$cache_name] = $this->load($cache_name);
	//	}

	//	return $this->cache_list[$cache_name];
	//}

	//public function load($cache_name) {
	//	$object = null;
	//	if (isset($this->cache_config[$cache_name]['type'])) {
	//		switch ($this->cache_config[$cache_name]['type']) {
	//		case 'file':
	//			s_core::load_sys_class('file_cache', $this->cache_class_path, 0);
	//			$object = new file_cache($this->cache_config[$cache_name]);
	//			break;
	//		case 'memcache':
	//			s_core::load_sys_class('memcache_cache', $this->cache_class_path, 0);
	//			$object = new memcache_cache($this->cache_config[$cache_name]);
	//			break;
	//		case 'apc':
	//			s_core::load_sys_class('apc_cache', $this->cache_class_path, 0);
	//			$object = new apc_cache($this->cache_config[$cache_name]);
	//			break;
	//		case 'redis':
	//			s_core::load_sys_class('redis_cache', $this->cache_class_path, 0);
	//			$object = new redis_cache($this->cache_config[$cache_name]);
	//			break;
	//		default:
	//			s_core::load_sys_class('file_cache', $this->cache_class_path, 0);
	//			$object = new file_cache($this->cache_config[$cache_name]);
	//		}
	//	} else {
	//		$object = s_core::load_sys_class('file_cache', $this->cache_class_path);
	//	}

	//	return $object;
	//}
}

?>
