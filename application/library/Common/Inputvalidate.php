<?php
/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/11
 * Time: 18:00
 */

class Common_Inputvalidate
{

    public function __set($name , $value)
    {
        $this->$name = $value;
        return true;
    }
}