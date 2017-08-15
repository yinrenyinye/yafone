<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class CommentModel extends BaseModel
{

    public static $table = 'comment';

    public function count()
    {
        return $this->_db->count();
    }

    public function get_list($pagesize,$offset)
    {
        return $this->_db->query("SELECT * FROM `".$this->table_name()."` LIMIT {$offset},{$pagesize}")->getAll();
    }

    public function table_name()
    {
        return self::$table;
    }
}
