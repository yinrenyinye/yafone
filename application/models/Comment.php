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
        return $this->db->count();
    }

    public function get_list()
    {
        return true;
    }
}
