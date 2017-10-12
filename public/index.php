<?php
error_reporting(E_ALL);
// 启动xhprof性能数据收集
xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

define('APPLICATION_PATH', realpath(dirname(__FILE__)."/../"));
define('B_STATIC_PATH','/statics/backend/');

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");
$application->bootstrap()->run();

// 结束收集
$xhprof_data = xhprof_disable();
$XHPROF_ROOT = "../xhprof";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
$xhprof_runs = new XHProfRuns_Default();

// 将此次收集的性能数据存放到xhprof.output_dir目录
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_yaf");
?>
