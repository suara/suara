<?php
namespace Suara\Libs\System\Types;

abstract class Type {
	const __default = null;
	public $_type = null;

	public function __construct($initial_value, $strict = false){
	}

	public function getType(){
		return $this->_type;
	}
}
?>
