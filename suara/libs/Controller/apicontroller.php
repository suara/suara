<?php
namespace Suara\Libs\Controller;
defined('IN_SUARA') or exit('Permission deiened');

/**
 * API Controller Abstra class
 * 写 API 需要注意，发布外面的时候需要使用final 关键词。
 * @author wolftankk@plu.cn
 */
class APIController {
	private $name;
	public function __construct($base, $name) {
		$this->name = $name;
		list($uri, ) = explode("?", env('REQUEST_URI'));
		$uri = strtolower($uri);

		if (strpos($uri, $base) === 0){
			$actions = trim(substr($uri, strlen($base)), '/');
			$actions = explode("/", $actions);
			$func = array_shift($actions);
			$arguments = $actions;

			$api_methods = $this->getAPIMethods();

			if (in_array($func, $api_methods)) {
				call_user_func_array(array(&$this, $func), $arguments);
			} elseif (in_array('__call', $api_methods) && $func != "__call") {
				call_user_func_array(array(&$this, $func), $arguments);
			} else {
				header('HTTP/1.1 405 Method Not Allowed');
			}
		}
	}

	/**
	 * 获得 API 接口方法
	 */
	private function getAPIMethods() {
		$rc = new ReflectionClass($this->name);
		$methods = $rc->getMethods(ReflectionMethod::IS_FINAL);
		$api_methods = array();
		
		foreach ($methods as $method) {
			if ($method->isFinal()) {
				$api_methods[] = strtolower($method->name);
			}
		}
		return $api_methods;
	}

	/**
	 * 公开显示 API 列表
	 * api名称， 描述， 参数 以及 示例
	 * @TODO 这里需要完善对 api 函数的分析
	 */
	//final public function getAPIList() {
	//	$rc = new ReflectionClass($this->name);
	//	$methods = $rc->getMethods(ReflectionMethod::IS_FINAL);
	//	$api_methods = array();
	//	
	//	foreach ($methods as $method) {
	//		if ($method->isFinal()) {
	//			$api_methods[] = array(
	//				'name' => $method->getName(),
	//			);
	//		}
	//	}
	//}

	/**
	 * 发布JSON到外网
	 */
	protected function release($json) {
		if (is_array($json)) {
			$json = json_encode($json);
		}
		header("Content-Type: application/x-javascript");
		$callback = trim($_GET['callback']);
		if (empty($callback)) {
			$callback = 'callback';
		}
		echo $callback.'('.$json.')';
	}
}

?>
