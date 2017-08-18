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
     * exmaple [ 'id' => ['field' => 1,'type' => 'int','index' => 'primary']]
     */
    public $columns = [];

    /** @var array set table columns value */
    public $fields = [];

    /** @var object the pdo object */
    public $pdo;

    /** @var object the pdo statement */
    public $statement;

    /** @var array database connect config */
    protected $_defultConf = [
        'host',
        'port',
        'user',
        'pwd',
        'db'
    ];

    /** @var string error message */
    protected $_error;

    public function connect(array $db_conf, $president = true, $charset = 'UTF8', $timeout = 2)
    {
        // TODO: Implement connect() method.
        if (!$this->_checkConf($db_conf)) return $this->_error;

        if (isset($db_conf['charset']) && !empty($db_conf['charset'])) $charset = $db_conf['charset'];

        try {
            $this->pdo = new \PDO(
                "mysql:host={$db_conf['host']};port={$db_conf['port']};dbname={$db_conf['db']};charset={$charset};",
                $db_conf['user'],
                $db_conf['pwd'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_PERSISTENT => (isset($db_conf['president']) && !empty($db_conf['president'])) ? $db_conf['president'] : $president,
                    \PDO::ATTR_TIMEOUT => (isset($db_conf['timeout']) && !empty($db_conf['timeout'])) ? $db_conf['timeout'] : $timeout,
                    1002 => "SET NAMES {$charset}",
                ]
            );
            return $this;
        } catch (Exception $e) {
            return $e->getCode() . " : " . $e->getMessage();

        }
    }

    public function query($sql, array $params = [])
    {
        // TODO: Implement query() method.
        $this->statement = $this->pdo->prepare($sql);

        if (!empty($params)) {
            $this->statement->execute($params);
        } else {
            $this->statement->execute();
        }

        return $this;
    }

    public function insert($sql, array $params = [])
    {
        // TODO: Implement insert() method.
        $this->statement = $this->pdo->prepare($sql);

        if (!empty($params)) {
            $this->statement->execute($params);
        } else {
            $this->statement->execute();
        }

        return $this;
    }

    public function update($sql)
    {
        // TODO: Implement update() method.
    }

    public function delete($sql)
    {
        // TODO: Implement delete() method.
    }

    public function getOne()
    {
        return $this->statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function count($sql = '')
    {
        // TODO: Implement count() method.
        if (empty($sql)) {
            return $this->query("SELECT count(*) AS `total` FROM `{$this->table}`")->getOne();
        } else {
            return $this->query($sql)->getOne();
        }
    }

    public function last_insert_id()
    {
        // TODO: Implement last_insert_id() method.
        return $this->pdo->lastInsertId();
    }

    public function free_result()
    {
        $this->statement->free_result();
        return true;
    }

    public function close()
    {
        // TODO: Implement close() method.
        $this->pdo->close();
        return true;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $method = "_".$name;
        return $this->$method($arguments);
    }

    private function _save($arguments = '')
    {

    }

    private function _create($arguments = '')
    {

    }

    private function _getFields($arguments)
    {
        return $this->$arguments;
    }

    private function _createFields($arguments)
    {
        $this->fields[key($arguments)] = current($value);
        return true;
    }

    private function _checkConf(array $conf)
    {
        foreach ($this->_defultConf as $item) {
            if (!isset($conf[$item]) || empty($conf[$item])) {
                $this->_error = "config '" . $item . "' is must have!";
                return false;
            }
        }

        return true;
    }
}