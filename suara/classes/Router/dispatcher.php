<?php
s_core::load_sys_class('Router', '', false);

class Dispatcher {
    public function __construct() {
	$request = new Request();
	//$this->dispatch();
    }

    public function parseParams() {
    }

    public function dispatch($request) {
	$this->parseParams();
    }

    protected function _getController() {

    }
    
    /**
     * 加载控制器
     */
    protected function _loadController($filename = '', $module = '') {
        //if (empty($filename)) $filename = ROUTE_C;
        //if (empty($m)) $m = ROUTE_M;
        //$loadfile = S_PATH.'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.$filename.".php";
        //if (file_exists($loadfile)) {
        //    $className = strtolower($filename);
        //    include $loadfile;
        //    if (class_exists($className)) {
        //	$reflection = new ReflectionClass($className);
        //	if ($reflection->isAbstract() || $reflection->isInterface()) {
        //	    header("HTTP/1.0 404 Not Found");
        //	} else {
        //	    return $reflection->newInstance();
        //	}
        //    } else {
        //        header("HTTP/1.0 404 Not Found");
        //    }
        //} else {
        //    header("HTTP/1.0 404 Not Found");
        //}
    }
}

?>
