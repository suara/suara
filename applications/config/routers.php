<?php
use Suara\Libs\Routing\Router;


// rule => [defaults, $options]
$routes = [
	'/' => [ ['controller' => 'index', 'action' => 'home']],
	'/houtai/:controller/:action' => [],
	'/tga/:action' => [['controller' => 'tgaapi'], []],
	'/news/content/' => [['controller'=> 'news', 'action' => 'content'], ['page' => 1]], // news/content/2014-03-17/1231.html
];

Router::add('/', ['controller' => 'index', 'action' => 'home']);
Router::add('/api/:controller/:action');
Router::add('/news/:id.html', ['controller'=> 'new', 'action' => 'content'], ['id' => '[0-9]+']);
Router::add('/user/**', ['controller'=> 'user']);
Router::add('/users/*', ['controller'=> 'user']);
Router::add('/api/user/account', ['controller' => 'user', 'action' => 'account', '[method]' => 'GET']);
?>
