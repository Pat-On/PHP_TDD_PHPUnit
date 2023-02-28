<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exception\DatabaseConnectionException;
use mysqli, mysqli_driver;
use Throwable;

class MySQLiConnection extends AbstractConnection implements DatabaseConnectionInterface
{

    const REQUIRED_CONNECTION_KEY = [
        'host',
        'db_name',
        'db_username',
        'db_user_password',
        'default_fetch'
    ];


    /**
     * @return mixed
     */
    public function connect(): MySQLiConnection
    {
        // TODO: depreciated - fix it at the end of the course
        $driver = new mysqli_driver;
        $driver->report_mode = MYSQLI_REPORT_STRICT;

        $credentials = $this->parseCredentials($this->credentials);

        try {
            $this->connection = new mysqli(...$credentials);
        } catch (Throwable $e) {
            throw new DatabaseConnectionException(
                $e->getMessage(),
                $this->credentials,
                500
            );
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    /**
     *
     * @param array $credentials
     * @return array
     */
    protected function parseCredentials(array $credentials): array
    {
        return [
            $credentials['host'],
            $credentials['db_username'],
            $credentials["db_user_password"],
            $credentials['db_name']
        ];
    }
}
