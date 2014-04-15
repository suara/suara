<?php
namespace Suara\Libs\Controller;

/**
 * API Controller Abstra class
 * 写 API 需要注意，发布外面的时候需要使用final 关键词。
 * @author wolftankk@plu.cn
 */
class APIController extends Controller{
	public function __construct($request, $response) {
		//$this->name = $name;
		//list($uri, ) = explode("?", env('REQUEST_URI'));
		//$uri = strtolower($uri);

		//if (strpos($uri, $base) === 0){
		//	$actions = trim(substr($uri, strlen($base)), '/');
		//	$actions = explode("/", $actions);
		//	$func = array_shift($actions);
		//	$arguments = $actions;

		//	$api_methods = $this->getAPIMethods();

		//	if (in_array($func, $api_methods)) {
		//		call_user_func_array(array(&$this, $func), $arguments);
		//	} elseif (in_array('__call', $api_methods) && $func != "__call") {
		//		call_user_func_array(array(&$this, $func), $arguments);
		//	} else {
		//		header('HTTP/1.1 405 Method Not Allowed');
		//	}
		//}
		parent::__construct($request, $response);
	}

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
