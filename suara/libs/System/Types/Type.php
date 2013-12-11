<?php
namespace Suara\libs\System\Types;

abstract class Type {
	const __default = null;

	abstract public function __construct($initial_value, $strict = false);
}
?>
