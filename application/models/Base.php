<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class BaseModel
{
    public static $table;

    public static $columns = [];

    public function __construct()
    {

        $this->db = Database_DB::getInstance();

        $this->db->table = static::$table;

        $this->db->table = static::$columns;
    }
}
