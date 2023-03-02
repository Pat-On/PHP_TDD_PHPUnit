<?php


namespace App\Repository;


use App\Contracts\RepositoryInterface;
use App\Database\QueryBuilder;

abstract class Repository implements RepositoryInterface
{

    protected static $className;
    protected static $table;

    /** @var QueryBuilder $queryBuilder */
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }


    /**
     * @param int $id
     * @return null|object
     */
    public function find(int $id): ?object
    {
        return $this->findOneBy('id', $id);
    }

    /**
     *
     * @param string $field
     * @param mixed $value
     * @return null|object
     */
    public function findOneBy(string $field, $value): ?object
    {
        $result = $this->queryBuilder
            ->table(static::$table)
            ->select()
            ->where($field, $value)
            ->runQuery()
            ->fetchInto(static::$className);

        return ($result) ? $result[0] : null;
    }

    /**
     *
     * @param array $criteria
     * @return mixed
     */
    public function findBy(array $criteria)
    {
        $this->queryBuilder->table(static::$table);
        foreach ($criteria as $criterion) {
            $this->queryBuilder->where(...$criterion);
        }

        return $this->queryBuilder->runQuery()->fetchInto(static::$className);
    }

    /**
     *
     * @param int $id
     * @return array
     */
    public function findAll(int $id): array
    {
        $result = $this->queryBuilder
            ->table(static::$table)
            ->select()
            ->runQuery()
            ->fetchInto(static::$className);

        return $result;
    }

    /**
     *
     * @param string $query
     * @return mixed
     */
    public function sql(string $query)
    {
        return $this->queryBuilder->raw($query)->fetchInto(static::$className);
    }

    /**
     *
     * @param \App\Entity\Entity $entity
     * @return object
     */
    public function create(\App\Entity\Entity $entity): object
    {
        $id = $this->queryBuilder->table(static::$table)->create($entity->toArray());

        return $this->find($id);
    }

    /**
     *
     * @param \App\Entity\Entity $entity
     * @param array $conditions
     * @return object
     */
    public function update(\App\Entity\Entity $entity, array $conditions = array()): object
    {
        $this->queryBuilder->table(static::$table)->update($entity->toArray());
        foreach ($conditions as $condition) {
            $this->queryBuilder->where(...$condition);
        }

        $this->queryBuilder->where('id', $entity->getId())->runQuery();

        return $this->find($entity->getId());
    }

    /**
     *
     * @param \App\Entity\Entity $entity
     * @param array $conditions
     */
    public function delete(\App\Entity\Entity $entity, array $conditions = array()): void
    {
        $this->queryBuilder->table(static::$table)->delete($entity->toArray());
        foreach ($conditions as $condition) {
            $this->queryBuilder->where(...$condition);
        }

        $this->queryBuilder->where('id', $entity->getId())->runQuery();
    }
}
