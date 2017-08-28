<?php

/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/14
 * Time: 14:36
 */
interface Database_IDatabase
{

    /**
     * 数据库链接
     * @param array $db_conf
     * @param $president
     * @param $charset
     * @param $timeout
     * @return mixed
     */
    public function connect(array $db_conf, $president, $charset, $timeout);

    /**
     * sql语句执行
     * @param $sql
     * @return mixed
     */
    public function query($sql, array $params = []);

    /**
     * 新增数据
     * @param $sql
     * @return mixed
     */
    public function insert($sql, array $params = []);

    /**
     * 修改数据
     * @param $sql
     * @return mixed
     */
    public function update($sql, array $params = []);

    /**
     * 删除数据
     * @param $sql
     * @param array $params
     * @return mixed
     */
    public function delete($sql, array $params = []);

    /**
     * 统计数量
     * @param $sql
     * @return mixed
     */
    public function count($sql);

    /**
     * 获取新增数据ID
     * @return mixed
     */
    public function last_insert_id();

    /**
     * 释放结果集
     * @return mixed
     */
    public function free_result();

    /**
     * 关闭数据库链接
     * @return mixed
     */
    public function close();
}