<?php


namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;




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

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
    }
}
