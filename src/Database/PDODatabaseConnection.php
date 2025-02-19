<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exceptions\Config\ConfigNotValidException;
use App\Exceptions\DatabaseConnectionException;
use PDO;
use PDOException;

class PDODatabaseConnection implements DatabaseConnectionInterface
{
    protected $connection;
    protected $config;
    protected $type;
    const REQUIRED_CONFIG_KEYS = [
        'driver',
        'host',
        'database',
        'db_user',
        'db_password'
    ];
    const REQUIRED_CONFIG_KEYS_SQLLITE = [
        'driver',
        'path'
    ];
    public function __construct(array $config, string $type = 'mysql')
    {
        if ($type === 'mysql' && !$this->isConfigValid($config)) {
            throw new ConfigNotValidException();
        }

        if ($type === 'sqlite' && !$this->isConfigValidSqlite($config)) {
            throw new ConfigNotValidException();
        }

        $this->config = $config;
        $this->type = $type;
    }
    public function connect()
    {
        $dsn = $this->generateDsn($this->config, $this->type);
        try {
            $this->connection = new PDO(...$dsn);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException($e->getMessage());
        }
        return $this;
    }
    public function getConnection()
    {
        return $this->connection;
    }
    private function generateDsn(array $config, string $type)
    {
        $generateDsn = [];
        if ($type === 'sqlite') {
            $dsn = "{$config['driver']}:{$config['path']}";
            $generateDsn = [$dsn];
        } else {
            $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
            $generateDsn = [$dsn, $config['db_user'], $config['db_password']];
        }
        return $generateDsn;
    }
    private function isConfigValid(array $config)
    {
        $matches = array_intersect(self::REQUIRED_CONFIG_KEYS, array_keys($config));
        return count($matches) === count(self::REQUIRED_CONFIG_KEYS);
    }
    private function isConfigValidSqlite(array $config)
    {
        $matches = array_intersect(self::REQUIRED_CONFIG_KEYS_SQLLITE, array_keys($config));
        return count($matches) === count(self::REQUIRED_CONFIG_KEYS_SQLLITE);
    }
}
