<?php


declare(strict_types=1);

namespace Tests\Functional;

use App\Repository\BugReportRepository;
use PHPUnit\Framework\TestCase;
use App\Helpers\DbQueryBuilderFactory;
use App\Database\QueryBuilder;
use App\Entity\BugReport;
use App\Helpers\HttpClient;

class CrudTest extends TestCase
{
    /** @var BugReportRepository $repository $ */
    private $repository;


    private $client;


    /** @var QueryBuilder $queryBuilder*/
    private $queryBuilder;

    public function setUp(): void
    {
        $this->queryBuilder = DbQueryBuilderFactory::make('database', 'pdo',   ['db_name' => 'bug_app_testing']);
        $this->queryBuilder->beginTransaction();
        $this->repository = new BugReportRepository($this->queryBuilder);
        $this->client = new HttpClient();
        parent::setUp();
    }

    // public function tearDown(): void
    // {
    //     $this->queryBuilder->rollback();
    //     parent::tearDown();
    // }

    private function getPostData(array $options = []): array
    {
        return array_merge(
            [
                'reportType' => "Audio",
                'message' => 'The video on page has audio issues, please check and fix it',
                'email' => 'test@test.com',
                'link' => "http://thisisthelink.com"

            ],
            $options
        );
    }

    public function testItCanCreateReportUsingPostRequest()
    {
        $postData = $this->getPostData(['add' => true]);
        $this->client->post('http://localhost/PHP_UNIT_TDD/Src/add.php', $postData);

        $results = $this->repository->findBy([
            ['report_type', '=', 'Audio'],
            ['email', '=', 'test@test.com'],
            ['link' => "http://thisisthelink.com"]
        ]);

        /** @var BugReport $bugReport */
        $bugReport = $results[0] ?? [];

        self::assertInstanceOf(BugReport::class, $bugReport);
        self::assertSame('Audio', $bugReport->getReportType());
        self::assertSame("http://thisisthelink.com", $bugReport->getLink());
        self::assertSame('test@test.com', $bugReport->getEmail());

        return $bugReport;
    }

    /**
     * @depends testItCanCreateReportUsingPostRequest
     */
    public function testItCanUpdateReportUsingPostRequest(BugReport $bugReport)
    {
        $postData = $this->getPostData([
            'update' => true,
            'message' => 'Updated Message',
            'link' => "http://updated_link.com",
            'reportId' => $bugReport->getId(),
        ]);

        $this->client->post('http://localhost/PHP_UNIT_TDD/Src/add.php', $postData);

        /** @var BugReport $results */
        $results = $this->repository->find($bugReport->getId());




        self::assertInstanceOf(BugReport::class, $results);
        self::assertSame("http://updated_link.com", $bugReport->getLink());
        self::assertSame('Updated Message', $bugReport->getMessage());

        return $bugReport;
    }


    /**
     * @depends testItCanUpdateReportUsingPostRequest
     */
    public function testItCanDeleteReportUsingPostRequest(BugReport $bugReport)
    {
        $postData = [
            'delete' => true,
            'reportId' => $bugReport->getId(),
        ];

        $this->client->post('http://localhost/PHP_UNIT_TDD/Src/delete.php', $postData);

        /** @var BugReport $results */
        $results = $this->repository->find($bugReport->getId());

        self::assertNull($bugReport);
    }
}
