<?php

namespace App\Models;

use App\Exceptions\MainException;
use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;

class ModelsMain
{
    protected $queryBuilder;
    protected $pdoConnection;

    public function __construct($DbConfig = DbConfig)
    {
        $this->pdoConnection = new PDODatabaseConnection($DbConfig);
        $this->queryBuilder = new PDOQueryBuilder($this->pdoConnection->connect());
    }
    public function __call($method, $args)
    {
        foreach ($args as $argkey => $argval) {
            $args[$argkey] = $this->CheckDataSanitization($argval);
        }

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        } else {
            throw new \BadMethodCallException("Method $method does not exist");
        }
    }
    protected function GetDataById(int|string $ID, string $TableName): object|array|null
    {
        try {
            return $this->queryBuilder->table($TableName)->find($ID);
        } catch (MainException) {
            throw new MainException('Exception On Get Function List By ID', 110);
        }
    }
    protected function GetDataByTarget(string $Column, string $Value, string $TableName): object|array|null
    {
        try {
            return $this->queryBuilder->table($TableName)->findBy($Column, $Value);
        } catch (MainException) {
            throw new MainException('Exception On Get Function List By ID', 111);
        }
    }
    protected function GetListByTarget(array $listOfData, string $column)
    {
        try {
            $GetListByTarget = $this->queryBuilder->table('functionlist');
            if (!empty($listOfData)) {
                foreach ($listOfData as $listData) {
                    $GetListByTarget->where_or($column, $listData);
                }
            }
            $GetListByTarget = $GetListByTarget->get();
            return $GetListByTarget;
        } catch (MainException) {
            throw new MainException('Exception On Get Functions Lists By ID', 111);
        }
    }
    protected function EditData(int|string $ID, string $TableName, array $Data): object|array
    {
        try {
            $this->queryBuilder->table($TableName)->where("id", $ID)->update($Data);
            return $this->GetDataById($ID, $TableName);
        } catch (MainException) {
            throw new MainException('Exception On Edit Function List', 112);
        }
    }
    protected function AddData(string $TableName, array $Data): int
    {
        try {
            return $this->queryBuilder->table($TableName)->create($Data);
        } catch (MainException) {
            throw new MainException('Exception On Add Function List', 113);
        }
    }
    protected function RemoveData(int|string $ID, string $TableName): void
    {
        try {
            $this->queryBuilder->table($TableName)->where("id", $ID)->delete();
        } catch (MainException) {
            throw new MainException('Exception On Remove Function List', 114);
        }
    }
    protected function Addlog($user_id, $text, $category, $target_id)
    {
        return $this->AddData("logs", ["user_id" => $user_id, "textlog" => $text, "ip" => RealIpAddr(), "category" => $category, "target_id" => $target_id, "time" => time()]);
    }
    protected function GetLog($by, $val): array|null
    {
        if (in_array($by, ["id", "user_id", "category"])) {
            return $this->queryBuilder->table('logs')->where($by, $val)->get();
        }
        return null;
    }

    public function CheckDataSanitization($data)
    {
        switch (gettypeMap($data)) {
            case 'int': {
                    return filter_var($data, FILTER_VALIDATE_INT);
                }
            case 'string': {
                    if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $data)) {
                        return $data;
                    }
                    if (json_decode($data) == null) {
                        $data = filter_var($data, 513);
                        $data = str_replace(["&#34;", "&#39;"], "", $data);
                    }
                    return $data;
                }
            default:
                return $data;
        }
    }
}
