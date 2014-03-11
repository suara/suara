<?php
namespace Suara\Libs\Configure;
use Suara\Libs\Configure\IConfigureReader;

class PhpReader implements IConfigureReader {
	protected $_path = null;

	public function __construct($path = null) {
		if (!$path) {
			$path = CONFIG_PATH;
		}

		$this->_path = CONFIG_PATH;
	}
	
	public function read($key) {
		if (strpos($key, '..') !== false) {
			throw new \ConfigureException("Cannot load configuration files with ../ in them.");
		}

		$file = $this->_getFilePath($key);

		if (!is_file($file)) {
			throw new \ConfigureException("Could not load this configuration file: ". $file);
		}

		include $file;
		if (!isset($config)) {
			throw new \ConfigureException('No variable $config found in '.$file); 
		}

		return $config;
	}

	public function dump($key, $data) {
		$contents = "<?php\n" . '$config = ' . var_export($data, true) . "; ?>";

		$filename = $this->_getFilePath($key);

		return file_put_contents($filename, $contents);
	}

	public function _getFilePath($key) {
		if (substr($key, -4) == ".php") {
			$key = substr($key, -4);
		}

		$key = $key . ".php";

		$file = $this->_path . $key;
		
		return $file;
	}
}
?>
