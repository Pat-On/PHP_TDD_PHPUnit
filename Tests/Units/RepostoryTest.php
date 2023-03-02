<?php

declare(strict_types=1);


namespace Tests\Units;


use App\Database\QueryBuilder;
use App\Helpers\DbQueryBuilderFactory;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    /** @var QueryBuilder $queryBuilder */
    private $queryBuilder;
    private $bugReportRepository;

    public function setUp(): void
    {
        $this->queryBuilder = DbQueryBuilderFactory::make(
            'database',
            'pdo',
            ['db_name' => 'bug_app_testing']
        );
        $this->queryBuilder->beginTransaction();

        $bugReportRepository = new BugReportRepository($this->queryBuilder);
        parent::setUp();
    }
    public function tearDown(): void
    {
        $this->queryBuilder->rollback();
        parent::tearDown();
    }
    public function testItCanCreateRecordWithEntity()
    {
        $newBugReport = $this->createBugReport();
        self::assertInstanceOf(BugReport::class, $newBugReport);
        self::assertSame('Type 2', $newBugReport->getReportType());
        self::assertSame('https://testing-link.com', $newBugReport->getLink());
        self::assertSame('This is a dummy message', $newBugReport->getMessage());
        self::assertSame('email@test.com', $newBugReport->getEmail());
    }

    public function testItCanUpdateAGivenEntity()
    {
        $newBugReport = $this->createBugReport();
        $bugReport = $this->bugReportRepository->find($newBugReport->getId());
        $bugReport
            ->setMessage('this is from update method')
            ->setLink('https://newlink.com/image.png');
        $updatedReport = $this->bugReportRepository->update($bugReport);

        self::assertInstanceOf(BugReport::class, $newBugReport);
        self::assertSame('this is from update method', $newBugReport->getLink());
        self::assertSame('https://newlink.com/image.png', $newBugReport->getMessage());
    }

    public function testItCanDeleteAGivenEntity()
    {
        $newBugReport = $this->createBugReport();
        $this->bugReportRepository->delete($newBugReport);
        $bugReport = $this->bugReportRepository->find($newBugReport->getId());
        self::assertNull($bugReport);
    }

    private function createBugReport(): BugReport
    {
        $bugReport = new BugReport();
        $bugReport->setReportType('Type 2')
            ->setLink('https://testing-link.com')
            ->setMessage('This is a dummy message') 
            ->setEmail('email@test.com');
        return $this->bugReportRepository->create($bugReport);
    }
}
