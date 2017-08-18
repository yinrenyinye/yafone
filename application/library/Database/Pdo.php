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
            $res = $this->statement->execute($params);
        } else {
            $res = $this->statement->execute();
        }

        return $res;
    }

    public function update($sql, array $params = [])
    {
        // TODO: Implement update() method.
        $this->statement = $this->pdo->prepare($sql);

        if (!empty($params)) {
            $res = $this->statement->execute($params);
        } else {
            $res = $this->statement->execute();
        }

        return $res;
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

    public function getFields($name)
    {
        return $this->$name;
    }

    public function createFields($name, $value)
    {
        $this->fields[$name] = $value;
        return true;
    }

    public function save($params)
    {
        if(is_array($params[0])){
            $sql = "";
            $sql .= "UPDATE `".$this->table."` SET";
            $column_value = [];
            $set_sql = "";
            foreach($this->fields as $ks => $vs){
                $set_sql .= " `".$ks."`=? ,";
                $column_value[] = $vs;
            }

            $sql .= rtrim($set_sql,",")." WHERE ";

            $w_sql = "";
            foreach($params[0] as $kw => $vw){
                $w_sql .= " `".$kw."`=? AND";
                $column_value[] = $vw;
            }
            $sql .= rtrim($w_sql,"AND");
        }

        if(is_string($params[0]) && strpos($params[0],"=") !== false){
            $sql = "";
            $sql .= "UPDATE `".$this->table."` SET";
            $column_value = [];
            $set_sql = "";
            foreach($this->fields as $k => $v){
                $set_sql .= " `".$k."`=? ,";
                $column_value[] = $v;
            }

            $sql .= rtrim($set_sql,",")." WHERE ".$params[0];
        }

        if(is_numeric($params[0]) && intval($params[0]) > 0){
            $sql = "";
            $sql .= "UPDATE `".$this->table."` SET";
            $column_value = [];
            $set_sql = "";
            foreach($this->fields as $k => $v){
                $set_sql .= " `".$k."`=? ,";
                $column_value[] = $v;
            }

            $sql .= rtrim($set_sql,",")." WHERE `id`=".intval($params[0]);
        }

        if(!empty($sql) && !empty($column_value)){
            $this->update($sql,$column_value);
            return true;
        }else{
            return $this->_error = "Invalid params!";
        }
    }

    public function create()
    {
        $sql = "";
        $sql .= "INSERT INTO `".$this->table."` (";
        $i_sql = "";
        $v_sql = "";
        $column_value = [];
        foreach ($this->fields as $k=>$v){
            $i_sql .= "`{$k}`,";
            $v_sql .= "?,";
            $column_value[] = $v;
        }
        $sql .= rtrim($i_sql,",").") VALUES (".rtrim($v_sql,",").")";
        if(!empty($i_sql) && !empty($v_sql) && !empty($column_value)){
            $this->insert($sql,$column_value);
            return true;
        }else{
            return $this->_error = "Invalid params!";
        }
    }

    private function _checkConf(array $conf)
    {
        foreach ($this->_defultConf as $item) {
            if (!isset($conf[$item]) || empty($conf[$item])) {
                $this->_error = "Config '" . $item . "' is must have!";
                return false;
            }
        }

        return true;
    }
}