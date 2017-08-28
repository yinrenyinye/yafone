<?php

/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/15
 * Time: 16:18
 */
class Common_Filecache
{

    private $_root = '/dev/shm/www-temp/';

    private $_time;

    /**
     *
     * @param string $group
     * @param int $time 当前时间
     */
    public function __construct($group = null, $time = null)
    {
        if (!is_null($group)) {
            $this->_root .= "{$group}/";
        }

        if (!$this->_time) {
            $this->_time = is_null($time) ? time() : $time;
        }
    }


    /**
     * Classic
     *
     * @param string $k
     * @param int $expire
     * @return mixed
     */
    public function get($k, $expire = NULL)
    {
        // 文件未创建
        if (!is_file($_file = $this->_f($k))) {
            return false;
        }

        // 过期检测
        if (is_numeric($expire)) {
            $_expire = ($this->_time - filemtime($_file)) > $expire;
            if ($_expire)
                return false;
        }

        // 读取原数据
        return unserialize($this->_read($_file));
    }

    /**
     * @param string $k
     * @param mixed $data
     */
    public function set($k, $data)
    {
        $_file = $this->_f($k);
        $this->_write($_file, serialize($data));
        return true;
    }

    /**
     * @param string $k
     */
    public function del($k)
    {
        $_file = $this->_f($k);
        if (is_file($_file) && unlink($_file)) {
            return true;
        }
        return false;
    }

    /**
     * 读取文件内容
     * @param string $_file
     * @return mixed
     */
    private function _read($_file)
    {
        $_fh = fopen($_file, "r");
        if ($_fh && flock($_fh, LOCK_SH)) {
            $data = fread($_fh, filesize($_file));
            flock($_fh, LOCK_UN);
            fclose($_fh);

            return $data;
        }

        return false;
    }

    /**
     * 保存结果至文件
     * @param string $_file
     * @param mixed $data
     * @param string $mode
     * @return bool
     */
    private function _write($_file, $data, $mode = 'w+')
    {
        if (!is_dir($_dir = dirname($_file))) {
            mkdir($_dir, 0755, true);
        }

        $_fh = fopen($_file, $mode);
        if ($_fh && flock($_fh, LOCK_EX)) {
            fwrite($_fh, $data);
            flock($_fh, LOCK_UN);
            fclose($_fh);
            return true;
        }

        return false;
    }

    /**
     * 换算 cache 文件路径
     * @param string $k
     * @return string
     */
    private function _f($k)
    {
        $finger = substr(md5($k), 7, 16);
        return $this->_root . "{$finger[0]}{$finger[1]}/{$finger[2]}{$finger[3]}/{$finger[4]}{$finger[5]}/" . substr($finger, 6);
    }
}