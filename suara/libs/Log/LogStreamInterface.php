<?php
namespace Suara\Libs\Log;

/**
 * LogStreamInterface
 */
interface LogStreamInterface {
	/**
	 * 每个Logger必须要都要有write的实现
	 * @param string $type
	 * @param string $message
	 *
	 * @return void
	 */
	public function write($type, $message);
}
?>
