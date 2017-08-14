<?php

/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/14
 * Time: 14:36
 */
interface Database_Database
{
    public function connect($host,$db,$user,$pwd,$options = []);

    public function query($sql);

    public function close();
}