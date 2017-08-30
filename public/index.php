<?php

define('APPLICATION_PATH', realpath(dirname(__FILE__)."/../"));
define('B_STATIC_PATH','/statics/backend/');

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
?>
