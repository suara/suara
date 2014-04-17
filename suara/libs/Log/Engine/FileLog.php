<?php
namespace Suara\Libs\Log\Engine;
use Suara\Libs\Log\ILogStream;

class FileLog implements ILogStream {
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
		$output = date("Y-m-d H:i:s") . " ". $type . " >".$message;
		$filename = $this->_getFilename($type);


	}

	private function _getFilename($type) {
		$debugTypes = ['notice', 'info', 'debug'];
		
		if (!empty($this->_file)) {
			$filename = $this->_file;
		} elseif ($type == 'error' || $type == 'warning') {
			$filename = 'error.log';
		} elseif (in_array($type, $debugTypes)) {
			$filename = 'debug.log';
		} else {
			$filename = "{$type}.log";
		}

		return $filename;
	}
}
?>
