<?php
namespace Suara\libs\Log\Engine;

use Suara\libs\Log\LogStreamInterface;

class FileLog implements LogStreamInterface {

	public function write($type, $message) {
		echo $type, $message;
	}
}
?>
