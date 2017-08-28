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
    /**
     * 注册基本配置
     */
    public function _initConfig()
    {
        $arrConfig = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('default_app_config', $arrConfig['application']);
        Yaf_Registry::set('default_db_config', $arrConfig['database']);
    }

    /**
     * 自动加载扩展类
     */
    public function _initLoader()
    {
        Yaf_Loader::import('vendor/autoload.php');
    }

    /**
     * 注册插件
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initPlugin(Yaf_Dispatcher $dispatcher)
    {
        $objSamplePlugin = new SamplePlugin();
        $dispatcher->registerPlugin($objSamplePlugin);
    }

    /**
     * 注册路由规则
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
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

    /**
     * 注册自定义视图
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initView(Yaf_Dispatcher $dispatcher)
    {

    }
}
