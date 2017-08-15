<?php

/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/14
 * Time: 14:39
 */
class Database_Pdo implements Database_IDatabase
{
    /** @var string database table name */
    public $table;

    /**
     * @var array database table columns
     * exmaple [ 'id' => ['data_type','length','is_unique(0|1)']]
     */
    public $columns = [];

    /** @var object the pdo object*/
    protected $_pdo;

    /** @var object the pdo statement*/
    protected $_statement;

    /** @var array database connect config */
    protected $_defultConf = [
        'host' => '127.0.0.1',
        'port' => '3306',
        'user' => 'root',
        'pwd' => '',
        'db' => ''
    ];

    /** @var string error message*/
    protected $_error;

    public function connect(array $db_conf ,$president = true, $charset = 'utf8', $timeout = 2)
    {
        // TODO: Implement connect() method.
        if(!$this->_checkConf($db_conf)) return $this->_error;

        if(!isset($db_conf['charset']) || empty($db_conf['charset'])) $charset = $db_conf['charset'];

        try{
            $this->_pdo = new \PDO(
                "mysql:host={$db_conf['host']};dbname={$db_conf};charset={$charset};",
                $db_conf['user'],
                $db_conf['pwd'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_PERSISTENT => (!isset($db_conf['president']) || empty($db_conf['president']))? $president : $db_conf['president'],
                    \PDO::ATTR_TIMEOUT => (isset($db_conf['president']) && !empty($db_conf['president']))? $db_conf['president'] : $timeout
                ]
            );

            return $this->_pdo;
        }catch (Exception $e){
            return $e->getCode()." : ".$e->getMessage();
        }
    }

    public function query($sql)
    {
        // TODO: Implement query() method.
        $this->_statement = $this->_pdo->prepare($sql);
        $this->_statement->execute();
        var_dump(111);
        return $this->_statement;
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

    public function row()
    {
        var_dump(22);
        return $this->_statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function total()
    {
        return $this->_statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function last_insert_id()
    {
        // TODO: Implement last_insert_id() method.
        return $this->_pdo->lastInsertId();
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function __set($params , $value)
    {

    }

    public function __get($params)
    {

    }

    private function _checkConf(array $conf)
    {
        foreach ($this->_defultConf as $k => $item) {
            if(!isset($conf[$k]) || empty($conf[$k])){

                $this->_error = "config '".$k."' is must have!";
                return false;
            }
        }

        return true;
    }
}