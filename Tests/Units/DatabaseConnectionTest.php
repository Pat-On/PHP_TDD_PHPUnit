<?php

namespace Test\Units;


use PHPUnit\Framework\TestCase;
use  App\Database\PDOconnection;
use App\Exception\MissingArgumentException;

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
        $credentials = [];
        $pdoHandler = (new PDOconnection($credentials))->connect();
        self::assertNotNull($pdoHandler);
    }
}
