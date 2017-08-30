<?php

define('APPLICATION_PATH', realpath(dirname(__FILE__)."/../"));
define('STATICS_PATH','/'.__DIR__.'/statics/backend');

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
?>
