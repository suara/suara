<?php
/**
 * Suara Bootstrap (http://suaraphp.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link          http://suaraphp.com
 * @package       Suara.Libs.Cache.Engine
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @author        wolftankk@gmail.com		  
 */
namespace Suara\Libs\Cache\Engine;
use Suara\Libs\Cache\CacheEngine;

class RedisEngine {
	private $redis = null;
	private $config = '';

	public function __construct($config) {
		if (!class_exists('Redis')) {
			return false;
		}
		$this->config = $config;

		return $this->connect();
	}

	protected function connect() {
		$is_successed = false;

		try {
			$this->redis = new Redis();
			if ($this->config['pconnect']) {
				$is_successed = $this->redis->pconnect($this->config['hostname'], $this->config['port'], $this->config['timeout']);
			} else {
				$is_successed = $this->redis->connect($this->config['hostname'], $this->config['port'], $this->config['timeout']);
			}
		} catch (RedisException $e) {
			return false;
		}
		if ($is_successed && !empty($this->config['password'])) {
			$is_successed = $this->redis->auth($this->config['password']);
		}

		return $is_successed;
	}

	public function __destruct() {
		if (!$this->config['pconnect']) {
			$this->redis->close();
		}
	}

	public function __call($name, $arguments) {
		$methods = get_class_methods($this->redis);
		if (in_array($name, $methods)) {
			return call_user_func_array(array($this->redis, $name), $arguments);
		}
	}


	public function get($name) {
		$value = $this->redis->get($name);
		if (ctype_digit($value)) {
			$value = intval($value);
		}

		if ($value !== false && is_string($value)) {
			$value = json_decode($value, true);
		}

		return $value;	
	}

	public function set($name, $value, $ttl=0, $ext1='', $ext2='') {
		if (!is_int($value)) {
			$value = json_encode($value);
		}
		if ($ttl == 0) {
			return $this->redis->set($name, $value);
		}
		return $this->redis->setex($name, $ttl, $value);
	}

	public function delete($key) {
		return $this->redis->delete($key) > 0;
	}

	public function flush() {
		return $this->redis->flushAll();
	}
}
?>
