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
    public $tableName;

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

    /**
     * 数据库链接
     * @param array $db_conf
     * @param bool $president
     * @param string $charset
     * @param int $timeout
     * @return $this|string
     */
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

    /**
     * 执行sql语句
     * @param $sql
     * @param array $params
     * @return array
     */
    public function query($sql, array $params = [])
    {
        // TODO: Implement query() method.
        return $this->_execute($sql, $params);
    }

    /**
     * 插入数据
     * @param $sql
     * @param array $params
     * @return array
     */
    public function insert($sql, array $params = [])
    {
        // TODO: Implement insert() method.
        return $this->_execute($sql, $params, 'insert');
    }

    /**
     * 修改数据
     * @param $sql
     * @param array $params
     * @return array
     */
    public function update($sql, array $params = [])
    {
        // TODO: Implement update() method.
        return $this->_execute($sql, $params, 'update');
    }

    /**
     * 删除数据
     * @param $sql
     * @param array $params
     * @return array
     */
    public function delete($sql, array $params = [])
    {
        // TODO: Implement delete() method.
        return $this->_execute($sql, $params, 'delete');
    }

    /**
     * 获取单条结果
     * @return mixed
     */
    public function row_one()
    {
        return $this->statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * 获取所有结果
     * @return mixed
     */
    public function row_all()
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * 查询数据条数
     * @param string $sql
     * @return mixed
     */
    public function count($sql = '')
    {
        // TODO: Implement count() method.
        if (empty($sql)) {
            return $this->query("SELECT count(*) AS `total` FROM `{$this->tableName}`")->row_one();
        } else {
            return $this->query($sql)->row_one();
        }
    }

    /**
     * 返回新增数据ID
     * @return mixed
     */
    public function last_insert_id()
    {
        // TODO: Implement last_insert_id() method.
        return $this->pdo->lastInsertId();
    }

    /**
     * 开启事务
     * @return bool
     */
    public function begin_transaction()
    {
        $this->pdo->beginTransaction();
        return true;
    }

    /**
     * 事务提交
     * @return bool
     */
    public function transaction_commit()
    {
        $this->pdo->commit();
        return true;
    }

    /**
     * 事务回滚
     * @return bool
     */
    public function transaction_rollback()
    {
        $this->pdo->rollback();
        return true;
    }

    /**
     * 释放结果集
     * @return bool
     */
    public function free_result()
    {
        $this->statement->free_result();
        return true;
    }

    /**
     * 关闭链接
     * @return bool
     */
    public function close()
    {
        // TODO: Implement close() method.
        $this->pdo->close();
        return true;
    }

    /**
     * 数据修改
     * @param $params
     * @return bool|string
     */
    public function save($params)
    {
        if (is_array($params[0])) {
            $sql = "";
            $sql .= "UPDATE `" . $this->tableName . "` SET";
            $column_value = [];
            $set_sql = "";
            foreach ($this->fields as $ks => $vs) {
                $set_sql .= " `" . $ks . "`=? ,";
                $column_value[] = $vs;
            }

            $sql .= rtrim($set_sql, ",") . " WHERE ";

            $w_sql = "";
            foreach ($params[0] as $kw => $vw) {
                $w_sql .= " `" . $kw . "`=? AND";
                $column_value[] = $vw;
            }
            $sql .= rtrim($w_sql, "AND");

            if (!empty($sql) && !empty($w_sql) && !empty($column_value)) {
                $this->update($sql, $column_value);
                return true;
            } else {
                return $this->_error = "Invalid params!";
            }
        }

        if (is_string($params[0]) && strpos($params[0], "=") !== false) {
            $sql = "";
            $sql .= "UPDATE `" . $this->tableName . "` SET";
            $column_value = [];
            $set_sql = "";
            foreach ($this->fields as $k => $v) {
                $set_sql .= " `" . $k . "`=? ,";
                $column_value[] = $v;
            }

            $sql .= rtrim($set_sql, ",") . " WHERE " . $params[0];
        }

        if (is_numeric($params[0]) && intval($params[0]) > 0) {
            $sql = "";
            $sql .= "UPDATE `" . $this->tableName . "` SET";
            $column_value = [];
            $set_sql = "";
            foreach ($this->fields as $k => $v) {
                $set_sql .= " `" . $k . "`=? ,";
                $column_value[] = $v;
            }

            $sql .= rtrim($set_sql, ",") . " WHERE `id`=" . intval($params[0]);
        }

        if (!empty($sql) && !empty($column_value)) {
            $this->update($sql, $column_value);
            return true;
        } else {
            return $this->_error = "Invalid params!";
        }
    }

    /**
     * 新增数据
     * @return bool|string
     */
    public function create()
    {
        $sql = "";
        $sql .= "INSERT INTO `" . $this->tableName . "` (";
        $i_sql = "";
        $v_sql = "";
        $column_value = [];

        foreach ($this->fields as $k => $v) {
            $i_sql .= "`{$k}`,";
            $v_sql .= "?,";
            $column_value[] = $v;
        }

        $sql .= rtrim($i_sql, ",") . ") VALUES (" . rtrim($v_sql, ",") . ")";

        if (!empty($i_sql) && !empty($v_sql) && !empty($column_value)) {
            $this->insert($sql, $column_value);
            return true;
        } else {
            return $this->_error = "Invalid params!";
        }
    }

    /**
     * 删除数据
     * @param $params
     * @return bool|string
     */
    public function destory($params)
    {
        if (is_array($params)) {
            if (is_numeric($params[0]) && intval($params[0]) > 0) {
                $sql = "DELETE FROM `" . $this->tableName . "` WHERE `id`=?";
                $column_value = $params;
                $this->delete($sql, $column_value);
            } else {
                return $this->_error = "Invalid params!";
            }
        }

        if (is_numeric($params) && intval($params) > 0) {
            $sql = "";
            $column_value = [$params];
            $this->delete($sql, $column_value);
        } else {
            return $this->_error = "Invalid params!";
        }

        return true;
    }

    /**
     * 获取表中的字段
     * @param $name
     * @return mixed
     */
    public function getFields($name)
    {
        return $this->$name;
    }

    /**
     * 生成表中的字段
     * @param $name
     * @param $value
     * @return bool
     */
    public function createFields($name, $value)
    {
        $this->fields[$name] = $value;
        return true;
    }

    /**
     * 执行sql语句，返回结果
     * @param $sql
     * @param array $params
     * @param string $operation
     * @return $this|array
     */
    private function _execute($sql, array $params = [], $operation = 'query')
    {
        try {
            if ('query' === $operation) {
                $this->statement = $this->pdo->prepare($sql);

                if (!empty($params)) {
                    $this->statement->execute($params);
                } else {
                    $this->statement->execute();
                }

                return $this;
            } else {
                $this->statement = $this->pdo->prepare($sql);

                if (!empty($params)) {
                    $res = $this->statement->execute($params);
                } else {
                    $res = $this->statement->execute();
                }

                return $res;
            }
        } catch (PDOException $e) {
            $this->transaction_status = false;
            return $this->_error = array("errno" => $e->getCode(), "message" => $e->getMessage());
        }
    }

    /**
     * 检查配置项
     * @param array $conf
     * @return bool
     */
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