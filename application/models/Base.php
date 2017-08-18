<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class BaseModel
{
    protected $_db;

    protected static $tableName;

    protected static $columns = [];

    protected static $is_write = 1;

    protected static $db_conf = [];

    public function __construct()
    {

        $this->_db = Database_DB::getInstance(self::$is_write, self::$db_conf);

        $this->_db->table = static::$tableName;

        $this->_db->columns = static::$columns;
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->_db->createFields($name, $value);
        return true;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->_db->getFields($name);
    }

    public function __call($name, $params)
    {
        // TODO: Implement __call() method.
        foreach($this->_db->fields as $k => $item){
            if(!isset(static::$columns[$k])){
                echo 'The column '.$k.' not exists in table '.static::$tableName;
                return false;
            }
        }
        return $this->_db->$name($params);
    }
}
