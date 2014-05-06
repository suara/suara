<?php
namespace Suara\Libs\Cache\Engine;
use Suara\Libs\Cache\CacheEngine;

use SplFileInfo;

class FileEngine extends CacheEngine {

	/**
	 * 设置文件缓存配置，默认配置
	 * suffix => ".cache"
	 * prefix => "",
	 * local => true,
	 * type  => 'array' // serialize
	 */
	public $setting = [];

	protected $_file = null;

	protected $_init = true;

	public function initialize($settings = []) {
		$settings += [
			'engine'  => 'file',
			'path'    => APP_CACHE_PATH,
			'prefix'  => '',
			'suffix'  => '.cache',
			'lock'    => true,
			'type'    => 'array',
			'isWindows'=> false,
			'mask'    => 0644
		];

		parent::initialize($settings);

		if (DIRECTORY_SEPARATOR == '\\') {
			$this->settings['isWindows'] = true;
		}

		if (substr($this->settings['path'], -1) !== DIRECTORY_SEPARATOR) {
			$this->settings['path'] .= DIRECTORY_SEPARATOR;
		}
	}

	protected function _active() {
		$dir = new SplFileInfo($this->settings['path']);
		if ($this->_init && !($dir->isDir() && $dir->isWritable())) {
			$this->_init = false;
			trigger_error("{$this->settings['path']}无法写入", E_USER_WARNING);
			return false;
		}

		return true;
	}

	public function write($name, $data, $config='', $type='data', $module=ROUTE_M) {
		//$this->get_setting($config);
		//if (empty($type)) {
		//	$type = 'data';
		//}
		//if (empty($module)) {
		//	$module = ROUTE_M;
		//}
		//$filepath = CACHE_PATH.'caches_'.$module.DIRECTORY_SEPARATOR.'caches_'.$type.DIRECTORY_SEPARATOR;
		//$filename = $name.$this->_setting['suffix'];
		//if (!is_dir($filepath)) {
		//	mkdir($filepath, 0777, true);
		//}

		//if ($this->_setting['type'] == 'array') {
			$data = "<?php\nreturn ".var_export($data, true).";\n?>";
		//} elseif ($this->_setting['type'] == 'serialize') {
		//	$data = serialize($data);
		//}

		//if (s_core::load_config('system', 'local_ex')) {
		//	$file_size = file_put_contents($filepath.$filename, $data, LOCK_EX);
		//} else {
		//	$file_size = file_put_contents($filepath.$filename, $data);
		//}

		//return $file_size ? $file_size : false;
	}

	public function read($name, $config ='', $type='data', $module=ROUTE_M) {
		//$this->get_setting($config);
		//if (empty($type)) {
		//	$type = 'data';
		//}
		//if (empty($module)) {
		//	$module = ROUTE_M;
		//}
		//$filepath = CACHE_PATH.'caches_'.$module.DIRECTORY_SEPARATOR.'caches_'.$type.DIRECTORY_SEPARATOR;
		//$filename = $name.$this->_setting['suffix'];

		//if (!file_exists($filepath.$filename)) {
		//	return false;
		//} else {
		//	if ($this->_setting['type'] == 'array') {
		//		$data = @require($filepath.$filename);
		//	} elseif ($this->_setting['type'] == 'serialize') {
		//		$data = unserialize(file_get_contents($filepath.$filename));
		//	}

		//	return $data;
		//}
	}

	public function delete($name, $config='', $type='data', $module=ROUTE_M) {
		//$this->get_setting($config);
		//if (empty($type)) {
		//	$type = 'data';
		//}
		//if (empty($module)) {
		//	$module = ROUTE_M;
		//}
		//$filepath = CACHE_PATH.'caches_'.$module.DIRECTORY_SEPARATOR.'caches_'.$type.DIRECTORY_SEPARATOR;
		//$filename = $name.$this->_setting['suffix'];
		//if (file_exists($filepath.$filename)) {
		//	return @unlink($filepath.$filename) ? true : false;
		//} else {
		//	return false;
		//}
	}

	//public function cacheinfo($name, $config='', $type='data', $module=ROUTE_M) {
	//	$this->get_setting($config);
	//	if (empty($type)) {
	//		$type = 'data';
	//	}
	//	if (empty($module)) {
	//		$module = ROUTE_M;
	//	}
	//	$filepath = CACHE_PATH.'caches_'.$module.DIRECTORY_SEPARATOR.'caches_'.$type.DIRECTORY_SEPARATOR;
	//	$filename = $name.$this->_setting['suffix'];
	//	if (file_exists($filepath.$filename)) {
	//		$res = array(
	//			'filename' => $filename,
	//			'filepath' => $filepath,
	//			'filectime' => filectime($filepath.$filename),
	//			'filemtime' => filemtime($filepath.$filename),
	//			'filesize' => filesize($filepath.$filename)
	//		);
	//		return $res;
	//	} else {
	//		return false;
	//	}
	//}
}
?>
