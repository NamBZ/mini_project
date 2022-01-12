<?php

/**
 * 
 */
class Database
{
    protected $connection = null;

    protected $hostname = '';
    protected $user = '';
    protected $password = '';
    protected $dbname = '';

    protected $charset = 'utf8';

    protected $table = '';
    protected $where = '';
    protected $limit = 20;
    protected $offset = 0;
    protected $statement = null;

    function __construct($configs)
    {
        $this->hostname = $configs['hostname'];
        $this->user = $configs['user'];
        $this->password = $configs['password'];
        $this->dbname = $configs['dbname'];

        // Connect DB

        $this->connect();
    }

    function __destruct()
    {
        // Close connect
        $this->disConnect();
    }

    function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    protected function connect()
    {
        $this->connection = new mysqli(
            $this->hostname,
            $this->user,
            $this->password,
            $this->dbname
        );

        $this->connection->set_charset($this->charset);

        if ($this->connection->connect_errno) {
            exit($this->connection->connect_error);
        }
    }

    protected function disConnect()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }


    public function reset()
    {
        $table = '';
        $limit = 20;
        $offset = 0;
    }

    public function table($tableName)
    {
        $this->table = $tableName;
        return $this; // fluent interface
    }

    public function limit($limit = 20)
    {
        $this->limit = $limit;
        return $this; // fluent interface
    }

    public function offset($offset = 0)
    {
        $this->offset = $offset;
        return $this; // fluent interface
    }



    public function getAllLimit()
    {
        $sql = "SELECT * FROM `$this->table` LIMIT ? OFFSET ?";
        $this->statement = $this->connection->prepare($sql);
        $this->statement->bind_param('ii', $this->limit, $this->offset);
        $this->statement->execute();
        $this->reset();

        return $this;
    }

    public function getAll($where = '')
    {
        $sql = "SELECT * FROM `$this->table`";

        if ($where != null) {
            $sql = "SELECT * FROM `$this->table` WHERE $where";
        }
        
        $this->reset();

        return $this->query($sql);
    }

    public function insert($data = [])
    {
        // data = ['title' => xxxx, 'content'=> xxx]

        $fields = implode(', ', array_keys($data));
        $valueMark = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = "INSERT INTO `$this->table`($fields) VALUES($valueMark)";
        $this->statement = $this->connection->prepare($sql);
        $this->statement->bind_param($this->param_mask($values), ...$values);
        $this->statement->execute();
        $this->reset();

        return $this->statement;
    }

    public function updateRow($id, $data = [])
    {
        // create set feilds string
        $keyValues = [];
        foreach ($data as $key => $value) {
            $keyValues[] = $key . '=?';
        }
        $setFields = implode(', ', $keyValues);

        // get values
        $values = array_values($data);
        $values[] = (int)$id;

        $sql = "UPDATE `$this->table` SET $setFields WHERE `id` = ?";

        $this->statement = $this->connection->prepare($sql);
        $this->statement->bind_param($this->param_mask($values), ...$values);
        $this->statement->execute();
        $this->reset();

        return $this->statement;
    }

    public function deleteId($id)
    {
        $sql = "DELETE FROM `$this->table` WHERE `id` = ?";
        $this->statement = $this->connection->prepare($sql);
        $this->statement->bind_param('i', $id);
        $this->statement->execute();
        $this->reset();

        return $this->statement;
    }

    public function query($sql)
    {
    	$this->reset();
    	return $this->connection->query($sql);
    }


    private function param_mask($values)
    {
        $param = '';
        foreach ($values as $value) {
            if (is_string($value))
                $param .= 's';
            elseif (is_float($value))
                $param .= 'd';
            elseif (is_int($value))
                $param .= 'i';
            else
                $param .= 'b';
        }
        return $param;
    }
}