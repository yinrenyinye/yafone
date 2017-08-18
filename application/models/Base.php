<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class BaseModel
{
    protected $_db;

    protected static $table;

    protected static $columns = [];

    protected static $is_write = 1;

    protected static $db_conf = [];

    public function __construct()
    {

        $this->_db = Database_DB::getInstance(self::$is_write,self::$db_conf);

        $this->_db->table = static::$table;

        $this->_db->columns = static::$columns;
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->_db->$name = $value;
        return true;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->_db->$name;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $this->_db->$name($arguments);
        return true;
    }
}
