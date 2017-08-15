<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database_DB::getInstance();

        $called_class = get_called_class();

        var_dump($called_class);
    }
}
