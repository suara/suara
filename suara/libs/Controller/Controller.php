<?php
namespace Suara\Libs\Controller;
use Suara\Libs\Http\Request as Request;
use Suara\Libs\Http\Response as Response;

class Controller {
	public $name = null;

	public $methods = [];

	public $request = null;

	public $response = null;

	public $viewVars = [];

	public $view = null;

	public $viewClass = 'SmartyView';

	public function __construct($request = null, $response = null) {
		if ($this->name === null) {
			$currentClassName = explode('\\', get_class($this));
			$currentClassName = array_pop($currentClassName);
			$this->name = substr($currentClassName, 0, -10);
		}

		$childrenMethods = get_class_methods($this);
		$parentMethods = get_class_methods(__CLASS__);

		$this->methods = array_diff($childrenMethods, $parentMethods);

		if ($request instanceof Request) {
			$this->setRequest($request);
		}

		if ($response instanceof Response) {
			$this->response = $response;
		}
	}

	//调用实际方法
	public function invodeAction(Request $request) {
		try {
			$method = new \ReflectionMethod($this, $request->params['action']);
			if ($this->isPrivateAction($method, $request)) {
			}

			return $method->invokeArgs($this, $request->params['pass']);
		} catch (\ReflectionException $e) {
		}
	}

	private function isPrivateAction(\ReflectionMethod $method, Request $request) {
		$privateAction = ($method->name[0] === '_' || !$method->isPublic());


		return $privateAction;
	}

	public function setRequest(Request $request) {
		$this->request = $request;
	}

	/**
	 * 页面最终输出
	 */
	public function render() {
		$class = 'Suara\\Libs\\View\\'.$this->viewClass;
		$this->view = new $class($this);
	}
}

?>
