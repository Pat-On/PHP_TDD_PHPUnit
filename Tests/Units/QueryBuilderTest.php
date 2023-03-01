<?php


namespace Test\Units;

use PHPUnit\Framework\TestCase;
use App\Database\QueryBuilder;
use App\Database\PDOconnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;

class QueryBuilderTest extends TestCase
{

    /** @var QueryBuilder $queryBuilder*/
    private $queryBuilder;

    public function setUp(): void
    {
        $credentials = array_merge(
            Config::get('database', 'pdo'),
            ['db_name' => 'bug_app_testing']
        );

        // var_dump($credentials);

        $pdo = new PDOConnection(
            $credentials
        );

        $this->queryBuilder = new PDOQueryBuilder(
            $pdo->connect()
        );
        parent::setUp();
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

    public function testItCanCreateRecord()
    {
        $data = [
            'report_type' => "Report Type 1",
            'message' => "This is a message",
            'email' => "email@gmail.com",
            'link' => "https://link.com",
            'created_at' => date('Y-m-d H:i:s'),


        ];
        $id = $this->queryBuilder->table('reports')->create($data);
        Self::assertNotNull($id);
    }



    public function testItCanPerformRawQuery()
    {
        $result = $this->queryBuilder->raw('SELECT * FROM reports')->get();
        Self::assertNotNull($result);
    }

    public function testItCanPerformSelectQuery()
    {
        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', 1)
            ->first();

        // var_dump($result->query);
        // exit;

        Self::assertNotNull($result);
        Self::assertSame(1, (int)$result->id);
    }

    public function testItCanPerformSelectQueryWithMultipleWhereClause()
    {
        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', 1)
            ->where('report_type', '=', "Report Type 1")
            ->first();
        Self::assertNotNull($result);
        Self::assertSame(1, (int)$result->id);
        Self::assertSame("Report Type 1", $result->report_type);
    }
}
