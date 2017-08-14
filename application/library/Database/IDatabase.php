<?php

/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/14
 * Time: 14:36
 */
interface Database_IDatabase
{
    public function connect($host,$db,$user,$pwd,$options = []);

    public function query($sql);

    public function insert($sql);

    public function update($sql);

    public function delete($sql);

    public function get_last_insert_id();

    public function close();
}