<?php
use Suara\Libs\Routing\Router;


// rule => [defaults, $options]
$routes = [
	'/' => [ ['controller' => 'index', 'action' => 'home']],
	'/houtai/:controller/:action' => [],
	'/tga/:action' => [['controller' => 'tgaapi'], []],
	'/news/content/' => [['controller'=> 'news', 'action' => 'content'], ['page' => 1]], // news/content/2014-03-17/1231.html
];

Router::add('');
?>
