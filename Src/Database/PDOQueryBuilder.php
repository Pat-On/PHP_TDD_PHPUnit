<?php

namespace App\Database;

use PDO;

class PDOQueryBuilder extends QueryBuilder
{
    /**
     * @return mixed
     */
    public function get()
    {
        return $this->statement->fetchAll();
    }

    /**
     * @return mixed
     */
    public function count()
    {
        return $this->statement->rowCount();
    }

    /**
     * @return mixed
     */
    public function lastInsertedId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     *
     * @param mixed $query
     * @return mixed
     */
    public function prepare($query)
    {
        return $this->connection->prepare($query);
    }

    /**
     *
     * @param mixed $statement
     * @return mixed
     */
    public function execute($statement)
    {
        $statement->execute($this->bindings);
        // var_dump($this->bindings);
        $this->bindings = [];
        $this->placeholders = [];
        return $statement;
    }

    /**
     *
     * @param mixed $className
     * @return mixed
     */
    public function fetchInfo($className)
    {
        // basically utilization of model
        return $this->statement->fetchAll(PDO::FETCH_CLASS, $className);
    }
    /**
     * @param mixed $className
     * @return mixed
     */
    public function fetchInto($className)
    {
    }

    /**
     * @return mixed
     */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * @return mixed
     */
    public function affected()
    {
        return $this->count();
    }
}
