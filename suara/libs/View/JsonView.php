<?php
namespace Suara\Libs\View;

use Suara\Libs\View\IViewRenderer as IViewRenderer;

class JsonView implements IViewRenderer {
	public $viewVars = [];
	public function __construct($controller) {
		
		$controller->response->type('json');
	}

	public function render($view = null, $layout = null, $options = null) {
		$return = null;

		if (is_array($this->viewVars['data'])) {
			$return = json_encode($this->viewVars['data']);
		} else {
			$return = $this->viewVars['data'];
		}

		if (!empty($this->viewVars['_jsonp'])) {
			$jsonpParam = $this->viewVars['_jsonp'];
			if ($this->viewVars['_jsonp'] === true) {
				$jsonpParam = 'callback';
			}

			if (isset($this->request->query[$jsonpParam])) {
				$return = sprintf("%s(%s)", $this->request->query[$jsonpParam], $return);
				$this->response->type('js');
			}
		}

		return $return;
	}
}
?>
