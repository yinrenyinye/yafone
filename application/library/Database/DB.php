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

    public static function getInstance($is_write = 1,array $db_conf = [])
    {
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance->connect($is_write,$db_conf);
    }

    public static function connect($is_write,$db_conf)
    {
        if(empty($db_conf)){
            $db_conf = Yaf_Registry::get("default_db_config");
        }

        ($is_write == 1) ? $db_host = $db_conf['write']['host'] : $db_host = $db_conf['read']['host'];

        $alias = self::_make_alias($db_conf['driver'],$db_host,$db_conf['port'],$db_conf['username'],$db_conf['database']);

        if(isset(self::$_handler[$alias]) && !empty(self::$_handler[$alias])){
            return self::$_handler[$alias];
        }

        if(empty(self::$_driver)){
            self::_set_driver($db_conf['driver']);
        }

        $db_driver = new self::$_driver();

        $db_link = $db_driver->connect([
            'host' => $db_host,
            'port' => $db_conf['port'],
            'user'=> $db_conf['username'],
            'pwd'=> $db_conf['password'],
            'db' => $db_conf['database'],
            'charset' => $db_conf['charset'],
            'president' => $db_conf['president'],
            'timeout' => $db_conf['timeout']
        ]);

        return self::_set_handler($alias,$db_link);
    }

    private function _make_alias($driver,$host,$port,$user,$db)
    {
        return implode(":",array($driver,$host,$port,$user,$db));
    }

    private function _set_handler($alias , $object)
    {
        return self::$_handler[$alias] = $object;
    }

    private function _set_driver($driver)
    {
        self::$_driver = "Database_".ucfirst($driver);
        return true;
    }
}