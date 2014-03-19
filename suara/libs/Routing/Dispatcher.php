<?
namespace Suara\Libs\Routing;

use Suara\Libs\Http\Request as Request;


class Dispatcher {

	public function dispatch(Request $request) {
		$controller = $this->_getController($request);
	}

	protected function _getController($request) {
		$className = $this->_loadController($request);
		if (!$className) {
			return false;
		}
		$reflection = new ReflectionClass($className);
		if ($reflection->isAbstract() || $reflection->isInterface()) {
			return false;
		} else {
			return $reflection->newInstance($request);
		}
	}

	protected function _loadController($request) {
		$controller = null;
		if (!empty($request->params['controller'])) {
			$controller = $request->params['controller'];//自动变化大小写
		}
		
		if ($controller) {
			$class = $controller . "Controller";
			#use Suara\Libs\Controller\
			
			if (class_exists($class)) {
				return $class;
			}
		}
		//$loadfile = S_PATH.'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.$filename.".php";
		//if (file_exists($loadfile)) {
		//	$className = strtolower($filename);
		//	include $loadfile;
		//	if (class_exists($className)) {
		//		if ($reflection->isAbstract() || $reflection->isInterface()) {
		//			header("HTTP/1.0 404 Not Found");
		//		} else {
		//			return $reflection->newInstance();
		//		}
		//	} else {
		//		header("HTTP/1.0 404 Not Found");
		//	}
		//} else {
		//	header("HTTP/1.0 404 Not Found");
		//}
	}
}

?>
