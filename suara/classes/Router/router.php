<?php
class Router {
    public function __construct() {
    }

    /**
     * 加入Router
     */
    public static function connect($route, $defaults = array(), $options = array()) {
	self::$initialized = true;
    }

    public static function redirect() {

    }

    public static function parse($url) {

    }

    protected static function _loadRoutes() {

    }
}
?>
