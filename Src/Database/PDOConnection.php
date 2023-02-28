<?php


namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exception\DatabaseConnectionException;
use PDOException, PDO;


class PDOconnection extends AbstractConnection implements DatabaseConnectionInterface
{

    const REQUIRED_CONNECTION_KEYS =  [
        'driver',
        'host',
        'db_name',
        'db_username',
        'db_user_password',
        'default_fetch'
    ];

    /**
     * @return PDOconnection
     */
    public function connect()
    {
        $credentials = $this->parseCredentials($this->credentials);

        try {
            $this->connection = new PDO(...$credentials);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                $this->credentials['default_fetch']
            );
        } catch (PDOException $e) {
            throw new DatabaseConnectionException($e->getMessage(), $this->credentials, 500);
        }


        return $this;
    }

    /**
     * @return mixed
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }


    /**
     * @param array $credentials
     * @return array
     */
    protected function parseCredentials(array $credentials): array
    {
        // data source name
        $dsn = sprintf(
            '%s:host=%s;dbname=%s',
            $credentials['driver'],
            $credentials["host"],
            $credentials['db_name']
        );

        // order is important
        return [$dsn, $credentials['db_username'], $credentials['db_user_password']];
    }
}
