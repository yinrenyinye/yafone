<?php

/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/15
 * Time: 9:38
 */
class Database_DB
{
    /** @var string database driver */
    private static $_driver;

    private static $_instance;

    public static $_handler = [];

    private function __construct()
    {

    }

    /**
     * 实例化数据库类
     * @param int $_mode
     * @param array $_db_conf
     * @return mixed
     */
    public static function getInstance($_mode = 1, array $_db_conf = [])
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance->connect($_mode, $_db_conf);
    }

    /**
     * 数据库链接
     * @param $_mode
     * @param $_db_conf
     * @return mixed
     */
    public static function connect($_mode, $_db_conf)
    {
        if (empty($_db_conf)) {
            $_db_conf = Yaf_Registry::get("default_db_config");
        }

        ($_mode == 1) ? $db_host = $_db_conf['write']['host'] : $db_host = $_db_conf['read']['host'];

        $alias = self::_make_alias($_db_conf['driver'], $db_host, $_db_conf['port'], $_db_conf['username'], $_db_conf['database']);

        if (isset(self::$_handler[$alias]) && !empty(self::$_handler[$alias])) {
            return self::$_handler[$alias];
        }

        if (empty(self::$_driver)) {
            self::_set_driver($_db_conf['driver']);
        }

        $db_driver = new self::$_driver();

        $db_link = $db_driver->connect([
            'host' => $db_host,
            'port' => $_db_conf['port'],
            'user' => $_db_conf['username'],
            'pwd' => $_db_conf['password'],
            'db' => $_db_conf['database'],
            'charset' => $_db_conf['charset'],
            'president' => $_db_conf['president'],
            'timeout' => $_db_conf['timeout']
        ]);

        return self::_set_handler($alias, $db_link);
    }

    /**
     * 数据库类别名
     * @param $driver
     * @param $host
     * @param $port
     * @param $user
     * @param $db
     * @return string
     */
    private function _make_alias($driver, $host, $port, $user, $db)
    {
        return implode(":", array($driver, $host, $port, $user, $db));
    }

    /**
     * 注册数据库类
     * @param $alias
     * @param $object
     * @return mixed
     */
    private function _set_handler($alias, $object)
    {
        return self::$_handler[$alias] = $object;
    }

    /**
     * 设置数据库驱动模式
     * @param $driver
     * @return bool
     */
    private function _set_driver($driver)
    {
        self::$_driver = "Database_" . ucfirst($driver);
        return true;
    }
}