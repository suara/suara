<?php
abstract class cache_engine {
    public $setting = array();

    public function init($setting=array()) {
    }

    public function gc($expires = null) {}

    abstract public function set($key, $value, $duration);

    abstract public function get($key);

    abstract public function delete($key);

    abstract public function flush();

    public function settings() {
	return $this->settings;
    }
}

?>
