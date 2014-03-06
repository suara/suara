<?php
namespace Suara\Libs\Error;
defined('IN_SUARA') or exit('Permission deiened');

use Suara\Libs\Error\ExceptionRenderer;
class ErrorHandler {
	/**
	 * 处理错误
	 * @see http://www.php.net/manual/zh/function.set-error-handler.php
	 */
	public static function handleError($errno, $errstr, $errfile = null, $errline = null, $errcontext = null) {
		if ( error_reporting() == 0 ) {
			return false;
		}
		$config = \Suara\Libs\Core\Configure::read('system', 'Error');
	}

	public static function handleException(\Exception $e) {
		$config = \Suara\Libs\Core\Configure::read('system', 'Exception');
	}

	private static function _log() {

	}
}
?>
