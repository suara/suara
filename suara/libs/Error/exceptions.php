<?php
class SuaraBaseException extends RuntimeException {
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
	class HttpException extends SuaraBaseException {
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

class SuaraException extends SuaraBaseException {

}

//missing controller exception
class MissingControllerException extends SuaraException {

}

class MissingActionException extends SuaraException {

}

class PrivateActionException extends SuaraException {

}

class ConfigureException extends SuaraException {

}

class SuaraLogException extends SuaraException {

}
?>
