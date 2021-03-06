<?php
namespace Suara\Libs\Error;
use Suara\Libs\Error\ExceptionRenderer;
use Suara\Libs\Core\Configure;

class ErrorHandler {
	/**
	 * 处理错误
	 * @see http://www.php.net/manual/zh/function.set-error-handler.php
	 */
	public static function handleError($errno, $errstr, $errfile = null, $errline = null, $errcontext = null) {
		if ( error_reporting() == 0 ) {
			return false;
		}

		$config = Configure::read('system', 'Error');
	}

	public static function handleException(\Exception $e) {
		$config = Configure::read('system', 'Exception');

	}

	private static function _log() {

	}
}
?>
