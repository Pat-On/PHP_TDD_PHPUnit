<?php


namespace Test\Units;

use App\Database\MySQLiConnection;
use App\Database\MySQLiQueryBuilder;
use PHPUnit\Framework\TestCase;
use App\Database\QueryBuilder;
use App\Database\PDOconnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use App\Helpers\DbQueryBuilderFactory;

class QueryBuilderMySQLiTest extends TestCase
{

    /** @var QueryBuilder $queryBuilder*/
    private $queryBuilder;

    public function setUp(): void
    {
        $this->queryBuilder = DbQueryBuilderFactory::make('database', 'mysqli',   ['db_name' => 'bug_app_testing']);


        // the same way is done in phpunit nice! 
        $this->queryBuilder->beginTransaction();

        parent::setUp();
    }

    public function tearDown(): void
    {
        $this->queryBuilder->rollback();
        parent::tearDown();
    }



    public function testItCanCreateRecord()
    {
        $id = $this->insertIntoTable();
        Self::assertNotNull($id);
    }



    public function testItCanPerformRawQuery()
    {
        $this->insertIntoTable();
        $result = $this->queryBuilder->raw('SELECT * FROM reports')->get();
        self::assertNotNull($result);
    }

    public function testItCanPerformSelectQuery()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', $id)
            ->runQuery()
            ->first();

        self::assertNotNull($result);
        self::assertSame((int)$id, $result->id);
    }

    public function testItCanPerformSelectQueryWithMultipleWhereClause()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', $id)
            ->where('report_type', '=', "Report Type 1")
            ->runQuery()
            ->first();

        self::assertNotNull($result);
        self::assertSame((int)$id, $result->id);
        self::assertSame("Report Type 1", $result->report_type);
    }

    private function insertIntoTable()
    {
        $data = [
            'report_type' => "Report Type 1",
            'message' => "This is a message",
            'email' => "email@gmail.com",
            'link' => "https://link.com",
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $id = $this->queryBuilder->table('reports')->create($data);
        return $id;
    }


    public function testItCanFindById()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->table('reports')->select("*")->find($id);
        self::assertNotNull($result);
        self::assertSame((int)$id, $result->id);
        self::assertSame("Report Type 1", $result->report_type);
    }

    public function testItCanFindOneByGivenValue()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->table('reports')->select("*")->findOneBy('report_type', 'Report Type 1');
        self::assertNotNull($result);
        self::assertSame((int)$id, $result->id);
        self::assertSame("Report Type 1", $result->report_type);
    }

    public function testItCanUpdateGivenRecord()
    {
        $id = $this->insertIntoTable();

        $count = $this->queryBuilder->table('reports')->update(
            ['report_type' => 'Report Type 1 Updated']
        )->where('id', $id)->runQuery()->affected();
        self::assertEquals(1, $count);

        $result = $this->queryBuilder->select("*")->find($id);
        self::assertNotNull($result);
        self::assertSame((int)$id, $result->id);
        self::assertSame("Report Type 1 Updated", $result->report_type);
    }

    public function testItCanDeleteGivenId()
    {
        $id = $this->insertIntoTable();

        $count = $this->queryBuilder->table('reports')->delete()->where('id', $id)->runQuery()->affected();
        self::assertEquals(1, $count);

        $result = $this->queryBuilder->select("*")->find($id);
        self::assertNull($result);
    }




    // public function testBindings()
    // {
    //     $query = $this->queryBuilder->table()->select()->where('id', 7)->where('report_type', '=', '100');
    //     self::assertIsArray($query->getPlaceholders());
    //     self::assertIsArray($query->getBindings());

    //     // var_dump($query->getBindings());
    //     // var_dump($query->getPlaceholders());

    //     // exit;
    // }

}
