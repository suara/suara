<?php
namespace Suara\libs\Log\Engine;

use Suara\libs\Log\LogStreamInterface;

class FileLog implements LogStreamInterface {
	protected $_defaults = array(
		'path' => LOGS_PATH,
		'file' => null,
		'types'=> null,
		'scopes'=>array(),
		'rotate'=>10,
		'size' => 10485760,
		'mask' => null
	);

	protected $_path = null;

	protected $_file = null;

	protected $_size = null;

	public function config($config = array()) {

	}

	public function write($type, $message) {
		echo $type, $message;
	}

	private function _getFilename($type) {
		$debugTypes = array('notice', 'info', 'debug');

	}
}
?>
