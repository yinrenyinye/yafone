<?php

/**
 * Created by PhpStorm.
 * User: miclefengzss
 * Date: 2017/8/14
 * Time: 23:22
 */
class Database_Mysqli implements Database_IDatabase
{

    public $table;

    public function connect(array $db_conf,$president,$charset,$timeout)
    {
        // TODO: Implement connect() method.
    }

    public function query($sql)
    {
        // TODO: Implement query() method.
    }

    public function insert($sql)
    {
        // TODO: Implement insert() method.
    }

    public function update($sql)
    {
        // TODO: Implement update() method.
    }

    public function delete($sql)
    {
        // TODO: Implement delete() method.
    }

    public function last_insert_id()
    {
        // TODO: Implement last_insert_id() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}