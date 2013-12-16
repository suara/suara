<?php
namespace Suara\libs\Error;
defined('IN_SUARA') or exit('Permission deiened');

use Suara\libs\Error\ExceptionRenderer;

class ErrorHandler {
	
	/**
	 * 处理错误
	 * @see http://www.php.net/manual/zh/function.set-error-handler.php
	 */
	public static function handleError($errno, $errstr, $errfile, $errline, $errcontext) {
		echo $errfile."<br/>";
		echo $errline."<br/>";
		echo $errstr;
	}

	public static function handleException($e) {

	}
}
?>
