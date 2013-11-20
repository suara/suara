<?php
class file_cache {
	protected $_setting = array(
		'suffix' => '.cache',
		'type'   => 'array' // array serialize null(string)
	);
	protected $filepath = '';

	public function __construct($config = '') {
		$this->get_setting($config);
	}

	public function set($name, $data, $config='', $type='data', $module=ROUTE_M) {
		$this->get_setting($config);
		if (empty($type)) {
			$type = 'data';
		}
		if (empty($module)) {
			$module = ROUTE_M;
		}
		$filepath = CACHE_PATH.'caches_'.$module.DIRECTORY_SEPARATOR.'caches_'.$type.DIRECTORY_SEPARATOR;
		$filename = $name.$this->_setting['suffix'];
		if (!is_dir($filepath)) {
			mkdir($filepath, 0777, true);
		}

		if ($this->_setting['type'] == 'array') {
			$data = "<?php\nreturn ".var_export($data, true).";\n?>";
		} elseif ($this->_setting['type'] == 'serialize') {
			$data = serialize($data);
		}

		if (s_core::load_config('system', 'local_ex')) {
			$file_size = file_put_contents($filepath.$filename, $data, LOCK_EX);
		} else {
			$file_size = file_put_contents($filepath.$filename, $data);
		}

		return $file_size ? $file_size : false;
	}

	public function get($name, $config ='', $type='data', $module=ROUTE_M) {
		$this->get_setting($config);
		if (empty($type)) {
			$type = 'data';
		}
		if (empty($module)) {
			$module = ROUTE_M;
		}
		$filepath = CACHE_PATH.'caches_'.$module.DIRECTORY_SEPARATOR.'caches_'.$type.DIRECTORY_SEPARATOR;
		$filename = $name.$this->_setting['suffix'];

		if (!file_exists($filepath.$filename)) {
			return false;
		} else {
			if ($this->_setting['type'] == 'array') {
				$data = @require($filepath.$filename);
			} elseif ($this->_setting['type'] == 'serialize') {
				$data = unserialize(file_get_contents($filepath.$filename));
			}

			return $data;
		}
	}

	public function delete($name, $config='', $type='data', $module=ROUTE_M) {
		$this->get_setting($config);
		if (empty($type)) {
			$type = 'data';
		}
		if (empty($module)) {
			$module = ROUTE_M;
		}
		$filepath = CACHE_PATH.'caches_'.$module.DIRECTORY_SEPARATOR.'caches_'.$type.DIRECTORY_SEPARATOR;
		$filename = $name.$this->_setting['suffix'];
		if (file_exists($filepath.$filename)) {
			return @unlink($filepath.$filename) ? true : false;
		} else {
			return false;
		}
	}

	public function get_setting($config = "") {
		if ($config && is_array($config)) {
			$this->_setting = array_merge($this->_setting, $config);
		}
	}

	public function cacheinfo($name, $config='', $type='data', $module=ROUTE_M) {
		$this->get_setting($config);
		if (empty($type)) {
			$type = 'data';
		}
		if (empty($module)) {
			$module = ROUTE_M;
		}
		$filepath = CACHE_PATH.'caches_'.$module.DIRECTORY_SEPARATOR.'caches_'.$type.DIRECTORY_SEPARATOR;
		$filename = $name.$this->_setting['suffix'];
		if (file_exists($filepath.$filename)) {
			$res = array(
				'filename' => $filename,
				'filepath' => $filepath,
				'filectime' => filectime($filepath.$filename),
				'filemtime' => filemtime($filepath.$filename),
				'filesize' => filesize($filepath.$filename)
			);
			return $res;
		} else {
			return false;
		}
	}
}
?>
