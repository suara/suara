<?php
namespace Suara\Libs\Routing\Routes;

class Route {
	//一个路由规则中每个区段的标识
	public $keys = [];

	private $template = null;
	private $defaults = [];
	private $options = [];

	//是否启用贪婪模式。 当规则中出现 *
	private $_greedy = false;

	//最后变成生成的路由规则
	private $_compiledRoute = null;

	//目前支持的类型
	private $_headerMap = array(
		'method' => 'request_method',
		'type'   => 'content_type',
		'server' => 'server_name'
	);
	
	public function __construct($template, $defaults = [], $options = []) {
		$this->template = $template;
		$this->defaults = (array)$defaults;
		$this->options = (array)$options;
	}

	public function compiled() {
		return !empty($this->_compiledRoute);
	}

	public function compile() {
		if ($this->compiled()) {
			return $this->_compiledRoute;
		}

		$this->_writeRoute();
		return $this->_compiledRoute;
	}

	private function _writeRoute() {
		if (empty($this->template) || $this->template == '/') {
			$this->_compiledRoute = '#^/*$#';
			$this->keys = array();
			return;
		}

		$route = $this->template;
		$names = $routeParams = array();
		$parsed = preg_quote($this->template, '#');

		#这里如果是中文可能会出问题
		preg_match_all('#:([A-Za-z0-9_-]+[A-Z0-9a-z])#', $route, $namedElements);

		foreach ($namedElements[1] as $i => $name) {
			$search = '\\' . $namedElements[0][$i];
			//正则匹配模式
			if (isset($this->options[$name])) {
				$option = null;
				if (array_key_exists($name, $this->defaults)) {
					$option = '?';
				}
				$param = '/\\' . $namedElements[0][$i];
				if (strpos($parsed, $param) !== false) {
					$routeParams[$param] = '(?:/(?<'.$name.'>'.$this->options[$name].')'.$option . ')' . $option;
				} else {
					$routeParams[$search] = '(?:(?<'.$name.'>'.$this->options[$name].')'.$option. ')' . $option;
				}
			} else {
				$routeParams[$search] = '(?:(?<' . $name . '>[^/]+))';
			}
			$names[] = $name;
		}

		// ** 匹配
		if (preg_match('#\/\*\*$#', $route)) {
			$parsed = preg_replace('#/\\\\\*\\\\\*$#', '(?:/(?<_trailing_>.*))?', $parsed);
			$this->_greedy = true;
		}
		// *
		if (preg_match('#\/\*$#', $route)) {
			$parsed = preg_replace('#/\\\\\*$#', '(?:/(?<_args_>.*))?', $parsed);
			$this->_greedy = true;
		}
		krsort($routeParams);
		$parsed = str_replace(array_keys($routeParams), array_values($routeParams), $parsed);
		$this->_compiledRoute = '#^' . $parsed . '[/]*$#';
		$this->keys = $names;

		foreach ($this->keys as $key) {
			unset($this->default[$key]);
		}
		$keys = $this->keys;
		sort($keys);
		$this->keys = array_reverse($keys);
	}

	public function parse($url) {
		if (!$this->compiled()) {
			$this->compile();
		}

		if (!preg_match($this->_compiledRoute, urldecode($url), $route)) {
			return false;
		}	

		foreach ($this->defaults as $key => $value) {
			$key = (string) $key;
			if ($key[0] === '[' && preg_match('#^\[(\w+)\]$#', $key, $header)) {
				if (isset($this->_headerMap[$header[1]])) {
					$header = $this->_headerMap[$header[1]];
				} else {
					$header = 'http_' . $header[1];
				}

				$header = strtoupper($header);

				$value = (array)$value;
				$isMatched = false;

				foreach ($value as $v) {
					if (\Suara\env($header) === $v) {
						$isMatched = true;
					}
				}

				if (!$isMatched) {
					return false;
				}
			}
		}

		array_shift($route);
		$count = count($this->keys);
		for ($i = 0; $i <= $count; $i++) {
			//移除 0 1 2
			unset($route[$i]);
		}
		$route['pass'] = $route['named'] = array();

		foreach ($this->defaults as $key => $val) {
			if (isset($route[$key])) {
				continue;
			}
			if (is_numeric($key)) {
				$route['pass'][] = $val;
				continue;
			}

			$route[$key] = $val;
		}

		if (isset($route['_args_'])) {
			//list($pass, $named) = $this->_parseArgs($route['_args_'], $route);
		}

		if (isset($route['_tailing_'])) {
			$route['pass'][] = $route['_tailing_'];
			unset($route['_tailing_']);
		}

		//调整pass参数的位置
		if (isset($this->options['pass'])) {
			$j = count($this->options['pass']);
			while ($j--) {
				if (isset($route[$this->options['pass'][$j]])) {
					array_unshift($route['pass'], $route[$this->options['pass'][$j]]);
				}
			}
		}

		return $route;
	}

	/**
	 * 这里主要用于比如分页
	 * /news/content/12/page:2
	 * 这里是一个特殊的处理模式，所以需要配置named greedy
	 */
	//private function _parseArgs($args, $context) {
	//	$pass = $named = [];
	//	$args = explode('/', $args);

	//	foreach ($args as $param) {

	//	}
	//}
}
?>
