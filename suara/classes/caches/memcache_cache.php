<?php
class memcache_cache {
	private $memcache = null;
	private $config = '';
	public function __construct($config) {
		$this->memcache = new Memcache;
		$this->config = $config;

		$this->connect();
	}

	public function connect() {
		if ($this->config['pconnect']) {
			$this->memcache->pconnect($this->config['hostname'], $this->config['port'], $this->config['timeout']);
		} else {
			$this->memcache->connect($this->config['hostname'], $this->config['port'], $this->config['timeout']);
		}
	}

	public function __destruct() {
		if ($this->config['pconnect']) {
			$this->memcache->close();
		}
	}

	public function get($name) {
		$value = $this->memcache->get($name);
		return $value;
	}

	public function set($name, $value, $ttl=0, $ext1='', $ext2='') {
		return $this->memcache->set($name, $value, false, $ttl);
	}

	public function delete($name) {
		return $this->memcache->delete($name);
	}

	public function flush() {
		return $this->memcache->flush();
	}
}

?>
