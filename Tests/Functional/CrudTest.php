<?php


declare(strict_types=1);

namespace Tests\Functional;

use App\Repository\BugReportRepository;
use PHPUnit\Framework\TestCase;
use App\Helpers\DbQueryBuilderFactory;
use App\Database\QueryBuilder;

class CrudTest extends TestCase
{
    /** @var BugReportRepository $repository $ */
    private $repository;


    /** @var QueryBuilder $queryBuilder*/
    private $queryBuilder;

    public function setUp(): void
    {
        $this->queryBuilder = DbQueryBuilderFactory::make('database', 'pdo',   ['db_name' => 'bug_app_testing']);

        $this->queryBuilder->beginTransaction();

        $this->repository = new BugReportRepository($this->queryBuilder);
        parent::setUp();
    }

    public function tearDown(): void
    {
        $this->queryBuilder->rollback();
        parent::tearDown();
    }
}
