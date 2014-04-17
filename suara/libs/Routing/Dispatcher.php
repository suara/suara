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
use Suara\Libs\Http\Response as Response;
use Suara\Libs\Routing\Router as Router;
use Suara\Libs\Controller\Controller as Controller;

use Suara\Libs\Error\MissingControllerException;

class Dispatcher {
	public function dispatch(Request $request, Response $response) {
		$this->parseParams($request);

		$controller = $this->_getController($request, $response);

		if (!($controller instanceof Controller)) {
			throw new MissingControllerException("{$controller} is not exists");
			exit;
		}

		//controller 调用$action
		$response = $this->_invoke($controller, $request, $response);
		
		if (isset($request->params['return'])) {
			return $response->body();
		}

		//输出
		$response->send();
	}

	public function _invoke(Controller $controller, Request $request, Response $response) {
		$controller->invodeAction($request);


		return $response;
	}

	public function parseParams($request) {
		//Router::setRuquestInfo($request);
		$params = Router::parse($request->url);
		$request->addParams($params);
	}

	protected function _getController($request, $response) {
		$className = $this->_loadController($request);
		if (!$className) {
			return false;
		}
		$reflection = new \ReflectionClass($className);
		if ($reflection->isAbstract() || $reflection->isInterface()) {
			return false;
		} else {
			return $reflection->newInstance($request, $response);
		}
	}

	protected function _loadController($request) {
		$controller = null;
		if (!empty($request->params['controller'])) {
			$controller = $request->params['controller'];//自动变化大小写
		}
		
		if ($controller) {
			$class = $controller . "Controller";

			$class = "Suara\\Apps\\Controllers\\$class";

			if (class_exists($class)) {
				return $class;
			}
		}

		return false;
	}
}

?>
