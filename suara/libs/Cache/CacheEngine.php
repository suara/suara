<?php
namespace Suara\Libs\Cache;

abstract class CacheEngine {
	/**
	 * 配置清单
	 */
	public $settings = [];
	protected $_groupPrefix = '';

	public function initialize($settings = []) {
		$settings += [
			'prefix'   => '',
			'duration' => 3600,
			'probability' => 100,
			'groups' => []
		];

		$this->settings = $settings;

		return true;
	}

	public function gc($expires = null){
	}

	abstract public function write($key, $value, $duration);

	abstract public function read($key);

	/**
	 * 给指定的$key值递增1
	 */
	abstract public function increment($key, $offset = 1);

	/**
	 * 给指定的$key值递减1
	 */
	abstract public function decrement($key, $offset = 1);

	abstract public function delete($key);

	abstract public function clear($check);

	public function clearGroup($group) {
		return false;
	}

	public function groups() {
		return $this->settings['groups'];
	}

	public function settings() {
		return $this->settings;
	}

	public function key($key) {
		if (empty($key)) {
			return false;
		}

		$prefix = '';
		
		return $prefix.$key;
	}
}
?>
