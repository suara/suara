<?php
include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."suara".DIRECTORY_SEPARATOR."bootstrap.php";

use Suara\Libs\Routing\Dispatcher;
use Suara\Libs\Http\Request;
use Suara\Libs\Http\Response;
(new Dispatcher())->dispatch(new Request(), new Response());
?>
