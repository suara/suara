<?php
include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."suara".DIRECTORY_SEPARATOR."bootstrap.php";

use Suara\Libs\Routing\Dispatcher;
use Suara\Libs\Web\Request;
use Suara\Libs\Web\Response;

//(new Dispatcher())->dispatch(new Request(), new Response());

$request = new Request();

print_r($request);
?>
