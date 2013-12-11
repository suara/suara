<?php
namespace Suara\libs\Configure;
use Suara\libs\Configure\ConfigureReaderInterface;

class PhpReader implements ConfigureReaderInterface {
	protected $_path = null;

	public function __construct($path = null) {
		if (!$path) {
			$path = CONFIG_PATH;
		}

		$this->_path = CONFIG_PATH;
	}
	
	public function read($key) {
		if (strpos($key, '..') !== false) {
			throw new ConfigureException("Cannot load configuration files with ../ in them.");
		}

		$file = $this->_getFilePath($key);

		if (!$file->isFile()) {
			throw new ConfigureException("Could not load this configuration file: ". $file->getFilename());
		}

		include $file;
		if (!isset($config)) {
			throw new ConfigureException('No variable $config found in '.$file->getFilename()); 
		}

		return $config;
	}

	public function dump($key, $data) {
		$contents = "<?php\n" . '$config = ' . var_export($data, true) . "; ?>";

		$filename = $this->_getFilePath($key)->getFilename();

		return file_put_contents($filename, $contents);
	}

	public function _getFilePath($key) {
		$file = $this->_path . $key;
		
		return SplFileInfo($file);
	}
}
?>
