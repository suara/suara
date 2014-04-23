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

class Router {
	/**
	 * 路由器规则存放与此
	 */
	public static $routes = [];

	public static $initialized = false;

	private static $_routeClass = 'Route';

	private static $_requests = [];

	private static $_namedConfig = [
		'default' => ['page', 'fields', 'order', 'limit', 'sort', 'direction', 'setup'],
		'greedyNamed' => true,
		'separator' => ':',
		'rules' => false

	];

	/**
	 * 增加一个路由规则
	 *
	 */
	public static function add($route, $defaults = array(), $options = array()) {
		self::$initialized = true;

		//prefix
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

	public static function redirect($route, $url, $options = []) {
		$options['routeClass'] = 'RedirectRoute';
		if (is_string($url)) {
			$url = ['redirect' => $url];
		}

		return self::add($route, $url, $options);
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

		//parse extension url

		//parse url
		for ($i = 0, $len = count(self::$routes); $i < $len; $i++) {
			$route =& self::$routes[$i];

			if ($r = $route->parse($url)) {
				//self::$_currentRoute[] =& $route;
				$output = $r;
				break;
			}
		}

		if (!empty($queryParams) && !isset($output['?'])) {
			$output['?'] = $queryParams;
		}

		return $output;
	}

	public static function setRequestInfo($request) {

	}

	public static function url() {

	}

	/**
	 * load app Route config
	 */
	protected static function loadRouteConfigs() {
		self::$initialized = true;
		include APP_CONFIG_PATH . 'routers.php';
	}
}
?>
