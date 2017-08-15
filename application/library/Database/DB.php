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
    private $_driver;

    private static $_handler = [];

    public function __construct($is_write = 1)
    {
        $db_conf = Yaf_Registry::get("default_db_config");

        ($is_write == 1) ? $db_host = $db_conf['write']['host'] : $db_host = $db_conf['read']['host'];

        $alias = $this->_make_alias($db_conf['driver'],$db_host,$db_conf['port'],$db_conf['username'],$db_conf['database']);

        if(isset(self::$_handler[$alias]) && !empty(self::$_handler[$alias])){
            return self::$_handler[$alias];
        }

        if(!empty($this->_driver)){
            $this->_set_driver($db_conf['driver']);
        }

        var_dump($this->_driver,2222);

        $db = new $this->_driver();

        var_dump($db);
        return true;
    }

    private function _make_alias($driver,$host,$port,$user,$db)
    {
        return implode(":",array($driver,$host,$port,$user,$db));
    }

    private function _set_handler($alias , $object)
    {
        self::$_handler[$alias] = $object;
        return true;
    }

    private function _set_driver($driver)
    {
        $this->_driver = "Database_".ucfirst($driver);
        return true;
    }

}