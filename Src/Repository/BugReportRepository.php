<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\Repository;

use App\Entity\BugReport;

class BugReportRepository extends Repository
{
    protected static $table = "reports";

    protected static $className = BugReport::class;


    // this is the place where you can add some extra custom methods
}
