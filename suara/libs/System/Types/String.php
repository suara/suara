<?php
namespace Suara\Libs\System\Types;

/**
 * 由于php原来是没有oop的，所以string直接使用函数做处理的，
 * 因此在这里将会重新做一个优化，将它oop化，使用起来更加简单。
 */

class String extends Type{
	const __default = 0;

	private $value;

	public function __construct($initial_value, $strict = false) {
		$this->value = $initial_value;
	}

	public function __tostring() {
		return $this->value;
	}

	public function strpos($offset = 0) {
		return strpos($this->value, $offset);
	}
}

?>
