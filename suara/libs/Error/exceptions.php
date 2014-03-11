<?php
namespace Suara\Libs\Error;

class BaseException extends \RuntimeException {
	protected $_responseHeaders = null;

	public function responseHeader() {

	}

	//getMessage
	//getPrevious
	//getCode
	//getFile
	//getLine
	//getTrace
	//getTraceAsString
	//__toString
	//__clone
}

if (!class_exists('HttpException', false)) {
	class HttpException extends BaseException {
	}
}

//400
class BadRequestException extends HttpException{
	public function __construct($message = null, $code = 400) {
		if (empty($message)) {
			$message = 'Bad Request';
		}
		parent::__construct($message, $code);
	}
}

//401
class UnauthorizedException extends HttpException{

}

//403
class ForbiddenException extends HttpException{

}

//404
class NotFoundException extends HttpException {

}

//405
class MethodNotAllowedException extends HttpException {

}

//500
class InternalErrorException extends HttpException {

}

class Exception extends BaseException {

}

//missing controller exception
class MissingControllerException extends Exception {

}

class MissingActionException extends Exception {

}

class PrivateActionException extends Exception {

}

class ConfigureException extends Exception {

}

class SuaraLogException extends Exception {

}
?>
