<?php

namespace Test\Units;

use App\Contracts\DatabaseConnectionInterface;
use PHPUnit\Framework\TestCase;
use  App\Database\PDOconnection;
use App\Exception\MissingArgumentException;
use App\Helpers\Config;

class DatabaseConnectionTest extends TestCase
{

    public function testItCanConnectToDatabaseWithWrongCredentialKeys()
    {
        $this->expectException(MissingArgumentException::class);
        $credentials = [];
        $pdoHandler = new PDOconnection($credentials);
    }

    public function testItCanConnectToDatabaseWithPdoApi()
    {
        $credentials = $this->getCredentials('pdo');
        $pdoHandler = (new PDOconnection($credentials))->connect();
        self::assertInstanceOf(DatabaseConnectionInterface::class, $pdoHandler);
        return $pdoHandler;
    }

    /**
     * @depends testItCanConnectToDatabaseWithPdoApi
     */
    public function testIsIsAValidPdoConnection(DatabaseConnectionInterface $handler)
    {
        self::assertInstanceOf(\PDO::class, $handler->getConnection());
    }


    // overwriting the name of the db and pointing tests to test db
    private function getCredentials(string $type)
    {
        return array_merge(
            Config::get('database', $type),
            ['db_name' => 'bug_app_testing',]
        );
    }
}
