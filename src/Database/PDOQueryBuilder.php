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
    protected $orderby;

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
    protected function addTable(string $table, array $columns = [])
    {
        $columnDefinitions = [];
        foreach ($columns as $columnName => $columnType) {
            $columnName = filter_var($columnName, 513);
            $columnType = filter_var($columnType, 513);
            $columnDefinitions[] = "{$columnName} {$columnType}";
        }
        $table = filter_var($table, 513);
        $columnDefinitions = implode(', ', $columnDefinitions);
        $this->execute("CREATE TABLE IF NOT EXISTS {$table} ({$columnDefinitions})");
        $this->purgeCache();
        return $this;
    }
    protected function removeTable(string $table)
    {
        $table = filter_var($table, 513);
        $this->execute("DROP TABLE IF EXISTS {$table}");
        $this->purgeCache();
        return $this;
    }
    protected function RenameTable(string $oldTable, string $newTable)
    {
        $oldTable = filter_var($oldTable, 513);
        $newTable = filter_var($newTable, 513);
        $this->execute("ALTER TABLE {$oldTable} RENAME TO {$newTable}");
        $this->purgeCache();
        return $this;
    }
    protected function editTable(string $Table, array $newColumns = [])
    {
        $Table = filter_var($Table, 513);

        foreach ($newColumns as $columnName => $columnType) {
            $columnName = filter_var($columnName, 513);
            $columnType = filter_var($columnType, 513);
            $this->execute("ALTER TABLE {$Table} CHANGE {$columnName} {$columnName} {$columnType}");
        }
        $this->purgeCache();
        return $this;
    }

    protected function table(string $table)
    {
        $this->table = filter_var($table, 513);
        return $this;
    }
    protected function get(array $columns = ['*'])
    {
        $columns = implode(",", $columns);
        $sql = "SELECT {$columns} FROM {$this->table} {$this->conditionrtrim()}" . $this->join . $this->orderby . $this->limit;
        $this->execute($sql);
        $fetchData = $this->statement->fetchAll();
        $this->purgeCache();
        return $fetchData;
    }
    protected function forceget(string $sql, bool $fetch = true)
    {
        $this->execute($sql);
        if ($fetch) {
            $fetchData = $this->statement->fetchAll();
            $this->purgeCache();
            return $fetchData;
        }
    }
    protected function create(array $data)
    {
        $placeholder = [];
        foreach ($data as $column => $value) {
            $placeholder[] = '?';
            $data[$column] = filter_var($value, 513);
        }
        $fields = implode(',', array_keys($data));
        $placeholder = implode(',', $placeholder);
        $this->values = array_values($data);
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholder})";
        $this->execute($sql);
        $this->purgeCache();
        return (int)$this->connection->lastInsertId();
    }
    protected function where(string $column, string $value)
    {
        $column = filter_var($column, 513);
        $this->conditions .= " {$column}=? AND ";
        $this->values[] = filter_var($value, 513);
        return $this;
    }
    protected function wheresign(string $column, string $value, string $sign)
    {
        $column = filter_var($column, 513);
        $sign = filter_var($sign, 513);
        $this->conditions .= " {$column}{$sign}? AND ";
        $this->values[] = filter_var($value, 513);
        return $this;
    }
    protected function where_or(string $column, string $value)
    {
        $column = filter_var($column, 513);
        $this->conditions .= " {$column}=? OR ";
        $this->values[] = filter_var($value, 513);
        return $this;
    }
    protected function wheresign_or(string $column, string $value, string $sign)
    {
        $column = filter_var($column, 513);
        $sign = filter_var($sign, 513);
        $this->conditions .= " {$column}{$sign}? OR ";
        $this->values[] = filter_var($value, 513);
        return $this;
    }
    protected function where_like(string $column, string $pattern = "%")
    {
        $column = filter_var($column, 513);
        $pattern = filter_var($pattern, 513);
        $this->conditions .= " {$column} LIKE '{$pattern}' AND ";
        return $this;
    }
    protected function where_like_or(string $column, string $pattern = "%")
    {
        $column = filter_var($column, 513);
        $pattern = filter_var($pattern, 513);
        $this->conditions .= " {$column} LIKE '{$pattern}' OR ";
        return $this;
    }
    protected function join(string $table, array $condition)
    {
        $table = filter_var($table, 513);
        $fields = [];
        foreach ($condition as $column => $value) {
            $column = filter_var($column, 513);
            $value = filter_var($value, 513);
            $fields[] = " {$column}={$value} AND ";
        }
        $fields = implode(',', $fields);
        $fields = rtrim($fields, 'AND ');
        $this->join .= " INNER JOIN {$table} ON {$fields} ";
        return $this;
    }
    protected function limit(string $count)
    {
        $count = filter_var($count, 513);
        $this->limit = " LIMIT {$count} ";
        return $this;
    }
    protected function order(string $column, string $type = "ASC")
    {
        if (!in_array($type, ["ASC", "DESC"])) {
            return $this;
        }
        $column = filter_var($column, 513);
        $this->orderby = " ORDER BY {$column} {$type} ";
        return $this;
    }
    protected function update(array $data)
    {
        $fields = [];
        foreach ($data as $column => $value) {
            $column = filter_var($column, 513);
            $value = filter_var($value, 513);
            $fields[] = "{$column}='{$value}'";
        }
        $fields = implode(',', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} {$this->conditionrtrim()}";
        $this->execute($sql);
        $rowCount =  $this->statement->rowCount();
        $this->purgeCache();
        return $rowCount;
    }
    protected function delete()
    {
        $sql = "DELETE FROM {$this->table} {$this->conditionrtrim()}";
        $this->execute($sql);
        $rowCount =  $this->statement->rowCount();
        $this->purgeCache();
        return $rowCount;
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
        $column = filter_var($column, 513);
        $value = filter_var($value, 513);
        return $this->where($column, $value)->first();
    }
    public function truncateAllTable()
    {
        $this->execute("SHOW TABLES");
        foreach ($this->statement->fetchAll(PDO::FETCH_COLUMN) as $table) {
            $this->execute("TRUNCATE TABLE `{$table}`");
        }
        $this->purgeCache();
    }
    private function conditionrtrim(): string
    {
        $conditionrtrim = $this->conditions ? rtrim(rtrim($this->conditions, 'AND '), 'OR ') : null;
        return ($conditionrtrim == "") ? "" : "WHERE" . $conditionrtrim;
    }
    private function purgeCache(): void
    {
        $this->statement = null;
        $this->limit = null;
        $this->orderby = null;
    }
    private function execute(string $sql)
    {
        $this->conditions = '';
        $this->statement = $this->connection->prepare($sql);
        $this->statement->execute($this->values);
        $this->values = [];
        return $this;
    }
}
