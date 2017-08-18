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
}
