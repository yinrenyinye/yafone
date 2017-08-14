<?php

/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/14
 * Time: 14:39
 */
class Database_Pdo implements Database_IDatabase
{
    public function connect($host, $db, $user, $pwd, $options = [])
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

    public function get_last_insert_id()
    {
        // TODO: Implement get_last_insert_id() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}