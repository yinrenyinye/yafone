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

    public function connect(array $db_conf, $president, $charset, $timeout)
    {
        // TODO: Implement connect() method.
    }

    public function query($sql, array $params = [])
    {
        // TODO: Implement query() method.
    }

    public function insert($sql, array $params = [])
    {
        // TODO: Implement insert() method.
    }

    public function update($sql, array $params = [])
    {
        // TODO: Implement update() method.
    }

    public function delete($sql, array $params = [])
    {
        // TODO: Implement delete() method.
    }

    public function count($sql = '')
    {
        // TODO: Implement count() method.
    }

    public function last_insert_id()
    {
        // TODO: Implement last_insert_id() method.
    }

    public function free_result()
    {
        // TODO: Implement free_result() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}