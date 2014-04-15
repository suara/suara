<?php
namespace Suara\Libs\Controller;
use Suara\Libs\Http\Request as Request;

class Controller {
	public function __construct($request = null, $response = null) {

	}

	public function invodeAction(Request $request) {
		try {
			$method = new \ReflectionMethod($this, $request->params['action']);
			if ($this->isPrivateAction($method, $request)) {
				//throw 
			}

			return $method->invokeArgs($this, $request->params['pass']);
		} catch (\ReflectionException $e) {
			//$e
		}
	}

	private function isPrivateAction(\ReflectionMethod $method, Request $request) {
		$privateAction = ($method->name[0] === '_' || !$method->isPublic());


		return $privateAction;
	}
}

?>
