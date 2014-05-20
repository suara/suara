<?php
namespace Suara\Libs\System\Types;

use Countable;

/**
 * Like python Tuple ('333', '111', '222')
 */
class Tuple extends Type implements Countable {
	public $_type = "tuple";

	public function __construct() {

	}
}
?>
