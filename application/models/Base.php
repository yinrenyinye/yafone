<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class BaseModel
{
    protected $_db;

    public static $table;

    public static $columns = [];

    public function __construct()
    {

        $this->_db = Database_DB::getInstance();

        $this->_db->table = static::$table;

        $this->_db->columns = static::$columns;
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.

        if(in_array($name,self::$columns)){
            $this->_db->columns[$name] = $value;
        }
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
    }
}
