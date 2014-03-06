<?php
use Suara\libs\Core\Configure;

Configure::write('system', 'debug', 2);
Configure::write('system', 'Error', array(
	'handler' => 'Suara\libs\Error\ErrorHandler::handleError',
	'level'   => E_ALL & ~E_DEPRECATED,
	'trace'   => true
));
Configure::write('system', 'Exception', array(
	'handler' => 'Suara\libs\Error\ErrorHandler::handleException',
	'renderer'=> 'Suara\libs\Error\ExceptionRenderer',
	'log'     => true
));

Configure::write('system', 'charset', 'utf-8');

//$config = array(
//    'cookie_pre' => '',
//    'cookie_domain' => '.plu.cn',
//    'cookie_path' => '',
//    'auth_key' => 'ufns92391bnoyu981',
//    'valid_key' => '',
//    'gzip' => 1
//);

?>
