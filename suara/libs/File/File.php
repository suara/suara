<?php
namespace Suara\Libs\File;

use Suara\Libs\File\Folder;

class File {
	public function __construct($path, $create = false, $mode = 0755) {

	}

	public function open($mode = 'r', $force = false) {
		
		$this->handle = new \SplFileObject($this->path, $mode);

		print_r($this->handle);

		return false;
	}
}
?>
