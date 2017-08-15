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
}
