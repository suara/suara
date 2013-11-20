<?php

class Controller {
	public $name = null;

	public function __construct() {
		if ($this->$name == null) {
			$this->$name = substr(get_class($this), 0, -10);
		}
	}
}

?>
