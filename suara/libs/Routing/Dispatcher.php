<?php
/**
 * Suara Bootstrap (http://suaraphp.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link          http://suaraphp.com
 * @package       Suara.Libs.Routing
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @author        wolftankk@gmail.com		  
 */
namespace Suara\Libs\Routing;
use Suara\Libs\Http\Request as Request;
use Suara\Libs\Routing\Router as Router;

class Dispatcher {


	public function dispatch(Request $request) {

		Router::parse($request->url);

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
	}
}

?>
