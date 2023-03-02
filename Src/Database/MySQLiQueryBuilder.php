<?php

namespace App\Database;

use App\Exception\NotFoundException;
use App\Exception\InvalidArgumentException;

class MySQLiQueryBuilder extends QueryBuilder
{

    private $resultSet;

    private $results;

    const PARAM_TYPE_INT = "i";
    const PARAM_TYPE_STRING = "s";
    const PARAM_TYPE_DOUBLE = "d";

    /**
     * @return mixed
     */
    public function get()
    {
        $results = [];

        if (!$this->resultSet) {
            $this->resultSet = $this->statement->get_result();
            // $this->results = $this->resultSet->fetchAll(MYSQLI_ASSOC);

            while ($object = $this->resultSet->fetch_object()) {
                $results[] = $object;
            }
            $this->results = $results;
        }

        return $this->results;
    }

    /**
     * @return mixed
     */
    public function count()
    {
        if (!$this->resultSet) {
            $this->get();
        }

        return $this->resultSet ? $this->resultSet->num_rows : 0;
    }

    /**
     * @return mixed
     */
    public function lastInsertedId()
    {
        return $this->connection->insert_id;
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
        if (!$statement) {
            throw new InvalidArgumentException('MySQLi statement is false');
        }

        if ($this->bindings) {
            $bindings = $this->parseBindings($this->bindings);
            $reflectionObj = new \ReflectionClass('mysqli_stmt');
            $method = $reflectionObj->getMethod('bind_param');
            $method->invokeArgs($statement, $bindings);
        }
        $statement->execute();
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
        $results = [];
        $this->resultSet = $this->statement->get_result();
        while ($object = $this->resultSet->fetch_object($className)) {
            $results[] = $object;
        }

        return $this->results = $results;
    }

    private function parseBindings(array $params)
    {
        $bindings = [];
        $count = count($params);
        if ($count === 0) {
            return $this->bindings;
        }

        $bindingTypes = $this->parseBindingTypes(); //"sids"
        $bindings[] = &$bindingTypes;
        for ($index = 0; $index < $count; $index++) {
            $bindings[] = &$params[$index];
        }
        return $bindings;
    }

    public function parseBindingTypes()
    {
        $bindingTypes = [];

        foreach ($this->bindings as $binding) {
            if (is_int($binding)) {
                $bindingTypes[] = self::PARAM_TYPE_INT;
            }
            if (is_string($binding)) {
                $bindingTypes[] = self::PARAM_TYPE_STRING;
            }
            if (is_float($binding)) {
                $bindingTypes[] = self::PARAM_TYPE_DOUBLE;
            }
        }
        // ['s', 'd', 's',] -> sds
        return implode('', $bindingTypes);
    }
}
