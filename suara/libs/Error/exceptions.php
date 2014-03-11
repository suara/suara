<?php
namespace Suara\Libs\Error;

class BaseException extends \RuntimeException {
	protected $_responseHeaders = null;

	public function responseHeader($header = null, $value = null) {
		if ($header) {
			if (is_array($header)) {
				return $this->_responseHeaders = $header;
			}
			$this->_responseHeaders = array($header => $value);
		}

		return $this->_responseHeaders;
	}
}

if (!class_exists('HttpException', false)) {
	class HttpException extends BaseException{
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
	public function __construct($message = null, $code = 401) {
		if (empty($message)) {
			$message = 'Unauthorized';
		}

		parent::__construct($message, $code);
	}
}

//403
class ForbiddenException extends HttpException{
	public function __construct($message = null, $code = 403) {
		if (empty($message)) {
			$message = 'Forbidden';
		}

		parent::__construct($message, $code);
	}
}

//404
class NotFoundException extends HttpException {
	public function __construct($message = null, $code = 404) {
		if (empty($message)) {
			$message = 'Not Found';
		}

		parent::__construct($message, $code);
	}
}

//405
class MethodNotAllowedException extends HttpException {
	public function __construct($message = null, $code = 405) {
		if (empty($message)) {
			$message = 'Method Not Allowed';
		}
		parent::__construct($message, $code);
	}
}

//500
class InternalErrorException extends HttpException {
	public function __construct($message = null, $code = 500) {
		if (empty($message)) {
			$message = 'Internal Server Error';
		}
		parent::__construct($message, $code);
	}
}

/**
 *
 */
class Exception extends BaseException {

}

//missing controller exception
class MissingControllerException extends Exception {

	public function __construct($message, $code = 404) {
		parent::__construct($message, $code);
	}
}

class MissingActionException extends Exception {

}

class PrivateActionException extends Exception {

}

class ConfigureException extends Exception {

}

class LogException extends Exception {

}

class RouterException extends Exception {

}
?>
