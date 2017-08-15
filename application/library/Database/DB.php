<?php

/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/15
 * Time: 9:38
 */
class Database_DB
{
    public function __construct()
    {
        $config = Yaf_Registry::get("config");
        var_dump($config);
    }

}