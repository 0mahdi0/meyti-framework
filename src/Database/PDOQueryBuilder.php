<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exceptions\MethodRunStepNotInOrderException;

use PDO;

class PDOQueryBuilder
{
    protected $table;
    protected $connection;
    protected $conditions;
    protected $value;
    protected $values;
    protected $statement;
    protected $join;
    protected $limit;

    public function __construct(DatabaseConnectionInterface $connection)
    {
        $this->connection = $connection->getConnection();
    }
    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $arguments);
        } else {
            throw new MethodRunStepNotInOrderException();
        }
    }
    protected function table(string $table)
    {
        $this->table = $table;
        return $this;
    }
    protected function get(array $columns = ['*'])
    {
        $columns = implode(",", $columns);
        $sql = "SELECT {$columns} FROM {$this->table} {$this->conditionrtrim()}" . $this->join . $this->limit;
        $this->execute($sql);
        return $this->statement->fetchAll();
    }
    protected function create(array $data)
    {
        $placeholder = [];
        foreach ($data as $column => $value) {
            $placeholder[] = '?';
        }
        $fields = implode(',', array_keys($data));
        $placeholder = implode(',', $placeholder);
        $this->values = array_values($data);
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholder})";
        $this->execute($sql);
        return (int)$this->connection->lastInsertId();
    }
    protected function where(string $column, string $value)
    {
        $this->conditions .= "{$column}=? AND ";
        $this->values[] = $value;
        return $this;
    }
    protected function where_or(string $column, string $value)
    {
        $this->conditions .= "{$column}=? OR ";
        $this->values[] = $value;
        return $this;
    }
    protected function where_like(string $column, string $pattern = "%")
    {
        $this->conditions .= "{$column} LIKE '{$pattern}' AND ";
        return $this;
    }
    protected function join(string $table, array $condition)
    {
        $fields = [];
        foreach ($condition as $column => $value) {
            $fields[] = "{$column}={$value} AND ";
        }
        $fields = implode(',', $fields);
        $fields = rtrim($fields, 'AND ');
        $this->join .= " INNER JOIN {$table} ON {$fields} ";
        return $this;
    }
    protected function limit(string $count)
    {
        $this->limit = " LIMIT {$count} ";
        return $this;
    }
    protected function update(array $data)
    {
        $fields = [];
        foreach ($data as $column => $value) {
            $fields[] = "{$column}='{$value}'";
        }
        $fields = implode(',', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} {$this->conditionrtrim()}";
        $this->execute($sql);
        return $this->statement->rowCount();
    }
    protected function delete()
    {
        $sql = "DELETE FROM {$this->table} {$this->conditionrtrim()}";
        $this->execute($sql);
        return $this->statement->rowCount();
    }
    protected function first(array $columns = ['*'])
    {
        $data = $this->get($columns);
        return empty($data) ? null : $data[0];
    }
    protected function last(array $columns = ['*'])
    {
        $data = $this->get($columns);
        return empty($data) ? null : end($data);
    }
    protected function find(int $id)
    {
        return $this->where("id", $id)->first();
    }
    protected function findBy(string $column, string $value)
    {
        return $this->where($column, $value)->first();
    }
    public function truncateAllTable()
    {
        $this->execute("SHOW TABLES");
        foreach ($this->statement->fetchAll(PDO::FETCH_COLUMN) as $table) {
            $this->execute("TRUNCATE TABLE `{$table}`");
        }
    }
    private function conditionrtrim()
    {
        $conditionrtrim = rtrim(rtrim($this->conditions, 'AND '), 'OR ');
        return ($conditionrtrim == "") ? "" : "WHERE " . $conditionrtrim;
    }
    private function execute(string $sql)
    {
        $this->statement = $this->connection->prepare($sql);
        $this->statement->execute($this->values);
        $this->values = [];
        return $this;
    }
}
