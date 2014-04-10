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

use Suara\Libs\Routing\Routes as Routes;

//路由器模式
// $router = [
//   '/' => [controller, action, addtionsParams]
//   '/:controller/'
//   '/v1/:controller/:action'
//
// ]

class Router {
	/**
	 * 路由器规则存放与此
	 */
	public static $routes = [];


	public static $initialized = false;

	private static $_routeClass = 'Route';

	/**
	 * 增加一个路由规则
	 *
	 * 最标准的模式是 /:controller/:action/*
	 * /:controller/:action/:date/:id.html    array('date' => '[0-9]{4}-[0-9]{2}-[0-9]{2}', 'id' => '[0-9]+')
	 * prefix
	 */
	public static function add($route, $defaults = array(), $options = array()) {
		self::$initialized = true;

		if (empty($defaults['action'])) {
			$defaults += ['action' => 'init'];
		}

		$routeClass = self::$_routeClass;

		if (isset($options['routeClass'])) {

		}

		$routeClass = __NAMESPACE__."\\Routes\\".$routeClass;
		self::$routes[] = new $routeClass($route, $defaults, $options);

		return self::$routes;
	}

	public static function parse($url) {
		if (!self::$initialized) {
			self::loadRouteConfigs();
		}

		$ext = null;
		$output = null;

		if (strlen($url) && strpos($url, '/') !== 0) {
			$url = '/' . $url;
		}

		//query
		if (strpos($url, '?') !== false) {
			list($url, $queryParams) = explode("?", $url, 2);
			parse_str($queryParams, $queryParams);
		}

		//parse url
		for ($i = 0, $len = count(self::$routes); $i < $len; $i++) {
			$route =& self::$routes[$i];

			if ($r = $route->parse($url)) {
				$output = $r;

				break;
			}
		}

		return $output;
	}

	/**
	 * load app Route config
	 */
	protected static function loadRouteConfigs() {
		//load config
		self::$initialized = true;
		include APP_CONFIG_PATH . 'routers.php';
	}
}
?>
