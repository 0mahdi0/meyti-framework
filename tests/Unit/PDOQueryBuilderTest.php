<?php

namespace Tests\Unit;

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class PDOQueryBuilderTest extends TestCase
{
    private $queryBuilder;
    public function setUp(): void
    {
        $pdoConnection = new PDODatabaseConnection($this->getConfig());
        $this->queryBuilder = new PDOQueryBuilder($pdoConnection->connect());
        parent::setUp();
    }
    public function testItCanCreateData()
    {
        $result = $this->insertIntoDb();
        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
    }
    public function testItCanGetAllWithAndWhereData()
    {
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb(['user' => 'meyti hosseini']);
        $result = $this->queryBuilder
            ->table('bugs')
            ->where('user', 'john')
            ->get();
        $this->assertIsArray($result);
        $this->assertEquals(6, count($result));
    }
    public function testItCanGetAllWithOrWhereData()
    {
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb(['user' => 'meyti hosseini']);
        $result = $this->queryBuilder
            ->table('bugs')
            ->where_or('user', 'john')
            ->get();
        $this->assertIsArray($result);
        $this->assertEquals(4, count($result));
    }
    public function testItCanGetAllWithLikeWhereData()
    {
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb(['email' => 'hosseini@mail.com']);
        $result = $this->queryBuilder
            ->table('bugs')
            ->where_like('email', 'john%')
            ->get();
        $this->assertIsArray($result);
        $this->assertEquals(5, count($result));
    }
    public function testItCanGetAllWithOutWhereData()
    {
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $result = $this->queryBuilder
            ->table('bugs')
            ->get();
        $this->assertIsArray($result);
        $this->assertEquals(7, count($result));
    }
    public function testItCanGetAllWithMiltWhereData()
    {
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb(['user' => 'meyti hosseini']);
        $this->insertIntoDb(['user' => 'meyti hosseini']);
        $this->insertIntoDb(['email' => 'hosseini@mail.com']);
        $result = $this->queryBuilder
            ->table('bugs')
            ->where('user', 'john')
            ->where_like('email', 'john%')
            ->get();
        $this->assertIsArray($result);
        $this->assertEquals(4, count($result));
    }
    public function testItCanJoinTableAndGetData()
    {
        $this->insertIntoDb([], true);
        $this->insertIntoDb([]);
        $this->insertIntoDb(['user' => 'meyti hosseini']);
        $this->insertIntoDb(['email' => 'hosseini@mail.com'], true);
        $result = $this->queryBuilder
            ->table('bugs')
            ->join('bugs_checker', ["bugs.id" => "bugs_checker.id"])
            ->get();
        $this->assertIsArray($result);
        $this->assertEquals(2, count($result));
    }
    public function testItCanGetLimitedData()
    {
        $this->insertIntoDb([]);
        $this->insertIntoDb(['user' => 'meyti hosseini']);
        $this->insertIntoDb(['email' => 'hosseini@mail.com']);
        $result = $this->queryBuilder
            ->table('bugs')
            ->limit('2')
            ->get();
        $this->assertIsArray($result);
        $this->assertEquals(2, count($result));
    }
    public function testItCanUpdateData()
    {
        $this->insertIntoDb();
        $result = $this->queryBuilder
            ->table('bugs')
            ->where('user', 'john')
            ->update(['email' => 'john@gmail.com', 'name' => 'First After Update']);
        $this->assertEquals(1, $result);
    }
    public function testItCanDeleteRecord()
    {
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $result = $this->queryBuilder
            ->table('bugs')
            ->where('user', 'john')
            ->delete();
        $this->assertEquals(4, $result);
    }
    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
    private function insertIntoDb(array $newData = [], bool $checker = false)
    {
        $data = array_merge([
            'name' => 'First Bug Report',
            'link' => 'http://link.com',
            'user' => 'john',
            'email' => 'john@gmail.com'
        ], $newData);
        if ($checker) {
            $this->queryBuilder->table('bugs_checker')->create($data);
        }
        return $this->queryBuilder->table('bugs')->create($data);
    }
    public function tearDown(): void
    {
        $this->queryBuilder->truncateAllTable();
        parent::tearDown();
    }
}
