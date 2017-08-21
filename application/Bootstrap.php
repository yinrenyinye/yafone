<?php

/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract
{

    public function _initConfig()
    {
        //把配置保存起来
        $arrConfig = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('default_app_config', $arrConfig['application']);
        Yaf_Registry::set('default_db_config', $arrConfig['database']);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher)
    {
        //注册一个插件
        $objSamplePlugin = new SamplePlugin();
        $dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        //在这里注册自己的路由协议,默认使用简单路由
        $router = $dispatcher::getInstance()->getRouter();

        $simpleRoute = new Yaf_Route_Simple('m', 'c', 'a');
        $router->addRoute('simple_route', $simpleRoute);

        $route = new Yaf_Route_Rewrite(
            'product/index',
            array(
                'controller' => 'product',
                'action' => 'index'
            )
        );
        //使用路由器装载路由协议
        $router->addRoute('product/index', $route);
    }

    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        //在这里注册自己的view控制器，例如smarty,firekylin
    }
}
